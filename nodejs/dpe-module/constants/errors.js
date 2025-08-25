const { StatusCodes } = require('http-status-codes');

class PaginationCacheExpiredError extends Error {
  constructor(message = 'Pagination cache expired') {
    super(message);
    this.name = 'PaginationCacheExpiredError';
    this.message = 'Pagination cache entry has expired or is missing';
    this.statusCode = StatusCodes.MOVED_TEMPORARILY;
    this.errorCode = 'page';
  }
}

const ERRORS = Object.freeze({
  NO_ACCESS: 'Method Not Allowed for user, no access.',
  USER_TRIAL: 'Method Not Allowed for user, with trial access.',
  PaginationCacheExpiredError,
});

module.exports = ERRORS;
