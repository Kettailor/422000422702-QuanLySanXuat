import express from 'express';
import morgan from 'morgan';
import axios from 'axios';
import { MongoClient } from 'mongodb';

const app = express();
const port = process.env.PORT || process.env.REPORTING_PORT || 5000;
const productionApi = process.env.PRODUCTION_API_URL || 'http://localhost:4000';
const mongoUri = process.env.MONGO_URI || 'mongodb://localhost:27017/reports';

const client = new MongoClient(mongoUri);

const asyncHandler = (fn) => (req, res, next) => Promise.resolve(fn(req, res, next)).catch(next);

app.use(express.json());
app.use(morgan('dev'));

app.get('/health', asyncHandler(async (_req, res) => {
  await client.db().command({ ping: 1 });
  res.json({ status: 'ok', service: process.env.SERVICE_NAME || 'reporting-service' });
}));

app.get('/reports/summary', asyncHandler(async (_req, res) => {
  const [workOrdersResponse, linesResponse, performanceResponse, logs] = await Promise.all([
    axios.get(`${productionApi}/work-orders`),
    axios.get(`${productionApi}/production-lines`),
    axios.get(`${productionApi}/performance/daily`),
    client
      .db()
      .collection('machine_logs')
      .find()
      .sort({ timestamp: -1 })
      .limit(100)
      .toArray(),
  ]);

  res.json({
    workOrders: workOrdersResponse.data,
    productionLines: linesResponse.data,
    dailyPerformance: performanceResponse.data,
    machineLogs: logs,
  });
}));

app.get('/reports/oee', asyncHandler(async (_req, res) => {
  const [performanceResponse, workOrdersResponse] = await Promise.all([
    axios.get(`${productionApi}/performance/daily`),
    axios.get(`${productionApi}/work-orders`),
  ]);

  const workOrders = workOrdersResponse.data;
  const totalPlanned = workOrders.reduce((acc, order) => acc + (order.plannedquantity || order.plannedQuantity || 0), 0);
  const totalCompleted = workOrders.reduce(
    (acc, order) => acc + (order.completedquantity || order.completedQuantity || 0),
    0
  );
  const totalScrap = workOrders.reduce((acc, order) => acc + (order.scrapquantity || order.scrapQuantity || 0), 0);

  const shiftOee = performanceResponse.data.map((shift) => {
    const planned = shift.plannedoutput || shift.plannedOutput || 0;
    const actual = shift.actualoutput || shift.actualOutput || 0;
    const downtime = shift.downtimeminutes || shift.downtimeMinutes || 0;
    const availability = planned === 0 ? 0 : Math.max(0, (planned - downtime) / planned);
    const performance = planned === 0 ? 0 : actual / planned;
    const quality = totalCompleted === 0 ? 0 : (totalCompleted - totalScrap) / totalCompleted;
    const oee = Number((availability * performance * quality * 100).toFixed(2));

    return {
      shiftDate: shift.shiftdate || shift.shiftDate,
      shiftName: shift.shiftname || shift.shiftName,
      plannedOutput: planned,
      actualOutput: actual,
      downtimeMinutes: downtime,
      availability: Number((availability * 100).toFixed(2)),
      performance: Number((performance * 100).toFixed(2)),
      quality: Number((quality * 100).toFixed(2)),
      oee,
    };
  });

  res.json({
    summary: {
      totalPlanned,
      totalCompleted,
      totalScrap,
      overallOee:
        shiftOee.length === 0
          ? 0
          : Number((shiftOee.reduce((acc, item) => acc + item.oee, 0) / shiftOee.length).toFixed(2)),
    },
    shifts: shiftOee,
  });
}));

app.use((err, _req, res, _next) => {
  console.error(err.message);
  res.status(500).json({ message: 'Reporting service error', details: err.message });
});

async function bootstrap() {
  await client.connect();
  app.listen(port, () => {
    console.log(`Reporting Service listening on port ${port}`);
  });
}

bootstrap().catch((err) => {
  console.error('Failed to start reporting service', err);
  process.exit(1);
});
