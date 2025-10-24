import { asyncHandler } from '../utils/asyncHandler.js';
import * as performanceService from '../services/performanceService.js';

export const getDailyPerformance = asyncHandler(async (_req, res) => {
  const performance = await performanceService.listDailyPerformance();
  res.json(performance);
});
