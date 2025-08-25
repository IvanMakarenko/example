const { ALLOWED_IN_COUNTRY_IDS, FEATURE_FLAG, LEAD_FLOW } = require('../constants');
const { userHelper } = require('../imports');

class GetStateUseCase {
  static execute(user) {
    const access = userHelper.isActive(user) || userHelper.isTrial(user);
    const isCountryAllowed = user.fields.countryIds.some(countryId => ALLOWED_IN_COUNTRY_IDS.includes(+countryId));
    const isFeatureFlag = user.fields.featureFlags?.[FEATURE_FLAG] || false;

    if (access && isCountryAllowed && isFeatureFlag) {
      return LEAD_FLOW.STATES.ACTIVE;
    }
    return LEAD_FLOW.STATES.INACTIVE;
  }
}

module.exports = GetStateUseCase;
