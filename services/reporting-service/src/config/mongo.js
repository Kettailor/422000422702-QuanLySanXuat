import { MongoClient } from 'mongodb';
import { config } from './env.js';

export const client = new MongoClient(config.mongoUri);

export const getDatabase = () => client.db();

export const healthCheck = async () => {
  await getDatabase().command({ ping: 1 });
};
