import axios from 'axios';
import { config } from '../config/env.js';

const forward = async ({ method, path, data, token }) => {
  const url = `${config.productionServiceUrl}${path}`;
  const response = await axios({
    method,
    url,
    data,
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return response.data;
};

export const getWorkOrders = async (req, res, next) => {
  try {
    const data = await forward({ method: 'get', path: '/work-orders', token: req.token });
    res.json(data);
  } catch (error) {
    next(error);
  }
};

export const getWorkOrderByCode = async (req, res, next) => {
  try {
    const data = await forward({
      method: 'get',
      path: `/work-orders/${encodeURIComponent(req.params.orderCode)}`,
      token: req.token,
    });
    res.json(data);
  } catch (error) {
    next(error);
  }
};

export const createWorkOrder = async (req, res, next) => {
  try {
    const data = await forward({
      method: 'post',
      path: '/work-orders',
      data: req.body,
      token: req.token,
    });
    res.status(201).json(data);
  } catch (error) {
    next(error);
  }
};

export const getProductionLines = async (req, res, next) => {
  try {
    const data = await forward({ method: 'get', path: '/production-lines', token: req.token });
    res.json(data);
  } catch (error) {
    next(error);
  }
};

export const getLineWorkOrders = async (req, res, next) => {
  try {
    const data = await forward({
      method: 'get',
      path: `/production-lines/${encodeURIComponent(req.params.lineCode)}/work-orders`,
      token: req.token,
    });
    res.json(data);
  } catch (error) {
    next(error);
  }
};

export const getDailyPerformance = async (req, res, next) => {
  try {
    const data = await forward({ method: 'get', path: '/performance/daily', token: req.token });
    res.json(data);
  } catch (error) {
    next(error);
  }
};
