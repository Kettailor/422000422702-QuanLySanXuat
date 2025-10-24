export const config = {
  port: Number(process.env.GATEWAY_PORT || process.env.PORT || 3000),
  serviceName: process.env.SERVICE_NAME || 'api-gateway',
  productionServiceUrl: process.env.PRODUCTION_SERVICE_URL || 'http://localhost:4000',
  reportingServiceUrl: process.env.REPORTING_SERVICE_URL || 'http://localhost:5000',
  corsOrigins: (process.env.CLIENT_ORIGINS || 'http://localhost:5173,http://localhost:4173')
    .split(',')
    .map((origin) => origin.trim())
    .filter(Boolean),
  session: {
    secret: process.env.SESSION_SECRET || 'qlsx-session-secret',
    name: process.env.SESSION_NAME || 'qlsx.sid',
    maxAge: Number(process.env.SESSION_MAX_AGE || 1000 * 60 * 60),
  },
  auth: {
    username: process.env.AUTH_USERNAME || 'admin',
    password: process.env.AUTH_PASSWORD || 'admin123',
  },
  jwt: {
    secret: process.env.JWT_SECRET || 'qlsx-jwt-secret',
    expiresIn: process.env.JWT_EXPIRES_IN || '1h',
  },
};
