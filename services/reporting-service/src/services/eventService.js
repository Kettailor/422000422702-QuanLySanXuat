import { getDatabase } from '../config/mongo.js';

export const recordEvent = async (event) => {
  const db = getDatabase();
  await db.collection('work_order_events').insertOne({ ...event, receivedAt: new Date().toISOString() });
};
