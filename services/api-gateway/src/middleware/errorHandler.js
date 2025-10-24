export const errorHandler = (err, _req, res, _next) => {
  const status = err.response?.status || err.status || 500;
  const message = err.message || 'Gateway error';
  const details = err.response?.data || err.details || undefined;

  if (status >= 500) {
    console.error(message, details || err);
  }

  res.status(status).json({
    message,
    details,
  });
};
