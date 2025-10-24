import { asyncHandler } from '../utils/asyncHandler.js';
import * as reportService from '../services/reportService.js';

export const getSummaryReport = asyncHandler(async (req, res) => {
  const report = await reportService.getSummary(req.token);
  res.json(report);
});

export const getOeeReport = asyncHandler(async (req, res) => {
  const report = await reportService.getOeeReport(req.token);
  res.json(report);
});
