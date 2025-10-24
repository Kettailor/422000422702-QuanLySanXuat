import axios from 'axios';
import { config } from '../config/env.js';

const forward = async ({ method, path, token }) => {
  const url = `${config.reportingServiceUrl}${path}`;
  const response = await axios({
    method,
    url,
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return response.data;
};

export const getSummaryReport = async (req, res, next) => {
  try {
    const data = await forward({ method: 'get', path: '/reports/summary', token: req.token });
    res.json(data);
  } catch (error) {
    next(error);
  }
};

export const getOeeReport = async (req, res, next) => {
  try {
    const data = await forward({ method: 'get', path: '/reports/oee', token: req.token });
    res.json(data);
  } catch (error) {
    next(error);
  }
};
