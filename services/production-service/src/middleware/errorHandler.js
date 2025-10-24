export const errorHandler = (err, _req, res, _next) => {
  const status = err.status || 500;
  if (status >= 500) {
    console.error(err.message, err.stack);
  }
  res.status(status).json({
    message: err.message || 'Production service error',
    details: err.details,
  });
};
