import axios from 'axios';
import { config } from './env.js';

export const createProductionClient = (token) =>
  axios.create({
    baseURL: config.productionServiceUrl,
    headers: {
      Authorization: `Bearer ${token}`,
    },
    timeout: 5000,
  });
