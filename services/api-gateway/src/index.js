import express from 'express';
import morgan from 'morgan';
import axios from 'axios';

const app = express();
const port = process.env.GATEWAY_PORT || 3000;
const productionServiceUrl = process.env.PRODUCTION_SERVICE_URL || 'http://localhost:4000';
const reportingServiceUrl = process.env.REPORTING_SERVICE_URL || 'http://localhost:5000';

app.use(express.json());
app.use(morgan('dev'));

app.get('/health', (_req, res) => {
  res.json({ status: 'ok', service: process.env.SERVICE_NAME || 'api-gateway' });
});

app.get('/api/production/work-orders', async (_req, res, next) => {
  try {
    const response = await axios.get(`${productionServiceUrl}/work-orders`);
    res.json(response.data);
  } catch (error) {
    next(error);
  }
});

app.get('/api/production/work-orders/:orderCode', async (req, res, next) => {
  try {
    const response = await axios.get(`${productionServiceUrl}/work-orders/${req.params.orderCode}`);
    res.json(response.data);
  } catch (error) {
    next(error);
  }
});

app.post('/api/production/work-orders', async (req, res, next) => {
  try {
    const response = await axios.post(`${productionServiceUrl}/work-orders`, req.body);
    res.status(201).json(response.data);
  } catch (error) {
    next(error);
  }
});

app.get('/api/production/lines', async (_req, res, next) => {
  try {
    const response = await axios.get(`${productionServiceUrl}/production-lines`);
    res.json(response.data);
  } catch (error) {
    next(error);
  }
});

app.get('/api/production/lines/:lineCode/work-orders', async (req, res, next) => {
  try {
    const response = await axios.get(
      `${productionServiceUrl}/production-lines/${req.params.lineCode}/work-orders`
    );
    res.json(response.data);
  } catch (error) {
    next(error);
  }
});

app.get('/api/production/performance/daily', async (_req, res, next) => {
  try {
    const response = await axios.get(`${productionServiceUrl}/performance/daily`);
    res.json(response.data);
  } catch (error) {
    next(error);
  }
});

app.get('/api/reports/summary', async (_req, res, next) => {
  try {
    const response = await axios.get(`${reportingServiceUrl}/reports/summary`);
    res.json(response.data);
  } catch (error) {
    next(error);
  }
});

app.use((err, _req, res, _next) => {
  console.error(err.message);
  res.status(err.response?.status || 500).json({
    message: 'Gateway error',
    details: err.message,
  });
});

app.listen(port, () => {
  console.log(`API Gateway listening on port ${port}`);
});
