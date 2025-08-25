const { strict: assert } = require('assert');

const { ERRORS } = require('../constants');
const { HttpError } = require('../imports');

const leadFlow = (request, h) => {
  const { user } = request.auth.credentials;
  const { allowLeadFlow } = user.fields.settings;

  assert(
    allowLeadFlow.diagnosisPerformanceEnergy,
    HttpError.forbidden(ERRORS.NO_ACCESS),
  );

  return h.continue;
};

module.exports = leadFlow;
