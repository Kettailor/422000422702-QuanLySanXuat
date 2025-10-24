export const config = {
  port: Number(process.env.PORT || process.env.PRODUCTION_PORT || 4000),
  serviceName: process.env.SERVICE_NAME || 'production-service',
  database: {
    host: process.env.POSTGRES_HOST || 'localhost',
    port: Number(process.env.POSTGRES_PORT || 5432),
    name: process.env.POSTGRES_DB || 'production',
    user: process.env.POSTGRES_USER || 'prod_admin',
    password: process.env.POSTGRES_PASSWORD || 'prod_admin',
  },
  reportingServiceUrl: process.env.REPORTING_SERVICE_URL || 'http://localhost:5000',
  jwt: {
    secret: process.env.JWT_SECRET || 'qlsx-jwt-secret',
  },
};
