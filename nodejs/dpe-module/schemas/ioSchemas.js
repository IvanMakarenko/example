const { Joi } = require('@app/schemas');

const { LeadEntitySchema } = require('./entityDataSchemas');
const { DPE, LEAD_FLOW, PAGINATION } = require('../constants');

const LeadResponseSchema = Joi.object({
  results: Joi.array().items(LeadEntitySchema).required(),
  count: Joi.number().integer().required(),
}).label('LeadResponseSchema');

const LeadSearchQuerySchema = Joi.object({
  page: Joi.number().integer().min(1).default(PAGINATION.default.page),
  pageSize: Joi.number().integer().min(1).max(100)
    .default(PAGINATION.default.pageSize),
  locations: Joi.string().empty('').optional(),
  customLoc: Joi.string().empty('').optional(),
  streetCenter: Joi.string().empty('').optional(),
  streetName: Joi.string().empty('').optional(),
  typeGroups: Joi.string().optional(),
  energyRatings: Joi.string().optional(),
  livingArea__gte: Joi.number().integer().min(0).optional(),
  livingArea__lte: Joi.number().integer().min(0).optional(),
  view: Joi.string().valid(...Object.values(DPE.VIEWS)).default(DPE.VIEWS.LIST),
  createdAt__gte: Joi.date().iso().optional(),
  createdAt__lte: Joi.date().iso().optional(),
});

const LeadDownloadQuerySchema = LeadSearchQuerySchema.append({
  format: Joi.string().valid(...LEAD_FLOW.DOWNLOAD_FORMATS).required(),
});

module.exports = {
  LeadResponseSchema,
  LeadSearchQuerySchema,
  LeadDownloadQuerySchema,
};
