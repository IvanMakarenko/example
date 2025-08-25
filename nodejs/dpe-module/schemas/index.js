const controllersSchemas = require('./controllersSchemas');
const entityDataSchemas = require('./entityDataSchemas');
const ioSchemas = require('./ioSchemas');

module.exports = {
  ...controllersSchemas,
  ...entityDataSchemas,
  ...ioSchemas,
};
