const { Joi } = require('@app/schemas');

const LeadFlowControllerDepsSchema = Joi.object({
  cache: Joi.object().options({ allowUnknown: true }),
}).options({ presence: 'required' });

const LeadFlowControllerSchema = Joi.object({
  actionSearch: Joi.func().arity(2),
  actionDownload: Joi.func().arity(3),
}).options({
  presence: 'required',
  allowUnknown: true,
});

module.exports = {
  LeadFlowControllerDepsSchema,
  LeadFlowControllerSchema,
};
