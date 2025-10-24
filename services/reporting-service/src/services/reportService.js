import { createProductionClient } from '../config/httpClient.js';
import { getDatabase } from '../config/mongo.js';

export const getSummary = async (token) => {
  const client = createProductionClient(token);
  const db = getDatabase();

  const [workOrdersResponse, linesResponse, performanceResponse, events] = await Promise.all([
    client.get('/work-orders'),
    client.get('/production-lines'),
    client.get('/performance/daily'),
    db
      .collection('work_order_events')
      .find()
      .sort({ 'metadata.occurredAt': -1 })
      .limit(100)
      .toArray(),
  ]);

  return {
    workOrders: workOrdersResponse.data,
    productionLines: linesResponse.data,
    dailyPerformance: performanceResponse.data,
    recentEvents: events,
  };
};

export const getOeeReport = async (token) => {
  const client = createProductionClient(token);

  const [performanceResponse, workOrdersResponse] = await Promise.all([
    client.get('/performance/daily'),
    client.get('/work-orders'),
  ]);

  const workOrders = workOrdersResponse.data;
  const totalPlanned = workOrders.reduce((acc, order) => acc + (order.plannedQuantity || order.plannedquantity || 0), 0);
  const totalCompleted = workOrders.reduce((acc, order) => acc + (order.completedQuantity || order.completedquantity || 0), 0);
  const totalScrap = workOrders.reduce((acc, order) => acc + (order.scrapQuantity || order.scrapquantity || 0), 0);

  const shiftOee = performanceResponse.data.map((shift) => {
    const planned = shift.plannedOutput || shift.plannedoutput || 0;
    const actual = shift.actualOutput || shift.actualoutput || 0;
    const downtime = shift.downtimeMinutes || shift.downtimeminutes || 0;

    const availability = planned === 0 ? 0 : Math.max(0, (planned - downtime) / planned);
    const performance = planned === 0 ? 0 : actual / planned;
    const quality = totalCompleted === 0 ? 0 : (totalCompleted - totalScrap) / totalCompleted;

    return {
      shiftDate: shift.shiftDate || shift.shiftdate,
      shiftName: shift.shiftName || shift.shiftname,
      plannedOutput: planned,
      actualOutput: actual,
      downtimeMinutes: downtime,
      availability: Number((availability * 100).toFixed(2)),
      performance: Number((performance * 100).toFixed(2)),
      quality: Number((quality * 100).toFixed(2)),
      oee: Number((availability * performance * quality * 100).toFixed(2)),
    };
  });

  return {
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
  };
};
