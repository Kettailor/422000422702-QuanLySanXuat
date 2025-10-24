export const config = {
  port: Number(process.env.PORT || process.env.REPORTING_PORT || 5000),
  serviceName: process.env.SERVICE_NAME || 'reporting-service',
  productionServiceUrl: process.env.PRODUCTION_API_URL || 'http://localhost:4000',
  mongoUri: process.env.MONGO_URI || 'mongodb://localhost:27017/reports',
  jwt: {
    secret: process.env.JWT_SECRET || 'qlsx-jwt-secret',
  },
};
