import axios from 'axios';
import { config } from '../config/env.js';
import { signServiceToken } from '../utils/jwt.js';

const EVENT_ENDPOINT = '/internal/events';

export const publishWorkOrderCreated = async (order) => {
  if (!order) return;

  const token = signServiceToken({ service: config.serviceName, event: 'work-order.created' });

  await axios.post(
    `${config.reportingServiceUrl}${EVENT_ENDPOINT}`,
    {
      type: 'work-order.created',
      payload: order,
      metadata: {
        source: config.serviceName,
        occurredAt: new Date().toISOString(),
      },
    },
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      timeout: 2000,
    }
  );
};
