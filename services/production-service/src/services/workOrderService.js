import * as workOrderModel from '../models/workOrderModel.js';
import * as productionLineModel from '../models/productionLineModel.js';
import { publishWorkOrderCreated } from '../messaging/eventPublisher.js';

export const listWorkOrders = () => workOrderModel.findAll();

export const getWorkOrder = async (orderCode) => {
  const order = await workOrderModel.findByCode(orderCode);
  return order;
};

export const createWorkOrder = async ({
  orderCode,
  lineCode,
  productCode,
  plannedQuantity,
  completedQuantity = 0,
  scrapQuantity = 0,
  status,
  dueTime,
}) => {
  if (!orderCode || !lineCode || !productCode || typeof plannedQuantity !== 'number' || !status) {
    const error = new Error('Missing required fields for work order');
    error.status = 400;
    throw error;
  }

  const line = await productionLineModel.findLineByCode(lineCode);
  if (!line) {
    const error = new Error('Invalid line code');
    error.status = 400;
    throw error;
  }

  const inserted = await workOrderModel.insert({
    orderCode,
    lineId: line.id,
    productCode,
    plannedQuantity,
    completedQuantity,
    scrapQuantity,
    status,
    dueTime,
  });

  if (!inserted) {
    const error = new Error('Work order already exists');
    error.status = 409;
    throw error;
  }

  const order = await workOrderModel.findByCode(orderCode);

  queueMicrotask(() => {
    publishWorkOrderCreated(order).catch((err) => console.error('Failed to publish work order event', err));
  });

  return order;
};
