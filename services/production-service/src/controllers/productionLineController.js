import { asyncHandler } from '../utils/asyncHandler.js';
import * as productionLineService from '../services/productionLineService.js';

export const getProductionLines = asyncHandler(async (_req, res) => {
  const lines = await productionLineService.listProductionLines();
  res.json(lines);
});

export const getLineWorkOrders = asyncHandler(async (req, res) => {
  const orders = await productionLineService.listWorkOrdersForLine(req.params.lineCode);
  res.json(orders);
});
