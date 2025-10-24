import { asyncHandler } from '../utils/asyncHandler.js';
import * as workOrderService from '../services/workOrderService.js';

export const getWorkOrders = asyncHandler(async (_req, res) => {
  const orders = await workOrderService.listWorkOrders();
  res.json(orders);
});

export const getWorkOrderByCode = asyncHandler(async (req, res) => {
  const order = await workOrderService.getWorkOrder(req.params.orderCode);
  if (!order) {
    res.status(404).json({ message: 'Work order not found' });
    return;
  }

  res.json(order);
});

export const createWorkOrder = asyncHandler(async (req, res) => {
  const order = await workOrderService.createWorkOrder(req.body || {});
  res.status(201).json(order);
});
