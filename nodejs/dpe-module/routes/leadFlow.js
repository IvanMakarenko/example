const guards = require('../guards');
const schemas = require('../schemas');
const { LeadFlowController } = require('../controllers');
const { ROUTES, STRATEGIES_NAMES, SWAGGER_TAGS } = require('../constants');
const { useUserTimezone } = require('../imports');

module.exports = (server, deps) => {
  const ctrl = new LeadFlowController({
    cache: deps.cache,
  });

  server.route([
    {
      path: ROUTES.LEAD_FLOW.DIAGNOSIS_PERFORMANCE_ENERGY.API.FEED,
      method: 'GET',
      handler: async (request) => {
        const user = request.auth.credentials.user;

        return ctrl.actionSearch(user, request.query);
      },
      options: {
        auth: STRATEGIES_NAMES.session,
        pre: [{ method: guards.leadFlow }],
        description: 'API get leads feed from Diagnosis Performance Energy',
        tags: [SWAGGER_TAGS.api, SWAGGER_TAGS.diagnosisPerformanceEnergy],
        validate: {
          query: schemas.LeadSearchQuerySchema,
        },
        response: {
          schema: schemas.LeadResponseSchema,
        },
      },
    },

    {
      path: ROUTES.LEAD_FLOW.DIAGNOSIS_PERFORMANCE_ENERGY.API.FEED_DOWNLOAD,
      method: 'GET',
      handler: async (request, h) => {
        useUserTimezone(request);
        const user = request.auth.credentials.user;

        const {
          buffer,
          contentType,
          filename,
        } = await ctrl.actionDownload(user, request.query, request.app.i18next);

        return h.response(buffer)
          .header('Content-Type', contentType)
          .header(
            'Content-Disposition',
            `attachment; filename="${filename}"; filename*=UTF-8''${encodeURIComponent(filename)}`,
          );
      },
      options: {
        auth: STRATEGIES_NAMES.session,
        pre: [{ method: guards.leadFlowDownload }],
        description: 'Download leads feed table from Diagnosis Performance Energy',
        tags: [SWAGGER_TAGS.api, SWAGGER_TAGS.diagnosisPerformanceEnergy],
        validate: {
          query: schemas.LeadDownloadQuerySchema,
        },
      },
    },
  ]);
};
