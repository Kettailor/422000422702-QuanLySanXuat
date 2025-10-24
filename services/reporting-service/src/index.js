import app from './app.js';
import { client } from './config/mongo.js';
import { config } from './config/env.js';

async function bootstrap() {
  await client.connect();
  app.listen(config.port, () => {
    console.log(`Reporting Service listening on port ${config.port}`);
  });
}

bootstrap().catch((err) => {
  console.error('Failed to start reporting service', err);
  process.exit(1);
});
