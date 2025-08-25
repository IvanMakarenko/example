const { strict: assert } = require('assert');

const leadFlow = require('./leadFlow');
const { ERRORS } = require('../constants');
const { HttpError, userHelper } = require('../imports');

const leadFlowDownload = (request, h) => {
  const { user } = request.auth.credentials;
  const access = userHelper.isActive(user);

  assert(access, HttpError.forbidden(ERRORS.USER_TRIAL));

  return leadFlow(request, h);
};

module.exports = leadFlowDownload;
