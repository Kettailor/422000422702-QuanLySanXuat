import * as productionLineModel from '../models/productionLineModel.js';

export const listProductionLines = () => productionLineModel.findAll();

export const listWorkOrdersForLine = (lineCode) => productionLineModel.findWorkOrdersForLine(lineCode);
