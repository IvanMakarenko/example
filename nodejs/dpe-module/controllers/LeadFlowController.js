const { StatusCodes } = require('http-status-codes');
const { logger } = require('@app/logger');

const {
  CONTENT_TYPES, LEAD_FLOW, PAGE_VIEW,
} = require('../constants');
const { DownloadService, PaginationService } = require('../services');
const { DpeApiClient, SearchHistoryService, tableGenerator } = require('../imports');
const { LeadFlowControllerDepsSchema, LeadFlowControllerSchema } = require('../schemas');
const { LeadFlowQuery } = require('../dto');

class LeadFlowController {
  #dpeApiClient = new DpeApiClient();
  #downloadService = new DownloadService();
  #paginationService;

  constructor(deps) {
    const depsValidation = LeadFlowControllerDepsSchema.validate(deps);
    if (depsValidation.error) throw new Error(`LeadFlowController not initialized: ${depsValidation.error.message}`);

    this.#paginationService = new PaginationService(deps.cache);

    const validation = LeadFlowControllerSchema.validate(this);
    if (validation.error) throw new Error(`LeadFlowController not initialized: ${validation.error.message}`);
  }

  async actionSearch(user, query) {
    const filter = new LeadFlowQuery(query);

    await this.#paginationService.loadPaginationFromCache(user, filter);
    const response = await this.#dpeApiClient.search(filter, user).catch(async (error) => {
      if (error.statusCode === StatusCodes.GONE) await this.#paginationService.handleError(user);
      throw error;
    });
    await this.#paginationService.saveNextPageUrl(user, filter, response.next_page_url);

    this.#saveHistory(user, query, response.total_count);
    return Object.freeze({
      count: response.total_count,
      results: response.results,
    });
  }

  async actionDownload(user, query, i18next) {
    const pageLimit = LEAD_FLOW.LIMIT_FOR_DOWNLOAD / LEAD_FLOW.API_LIMIT;
    const results = [];

    for (let page = 1; page <= pageLimit; page += 1) {
      const filter = new LeadFlowQuery({
        ...query,
        page,
        limit: LEAD_FLOW.API_LIMIT,
      });
      await this.#paginationService.loadPaginationFromCache(user, filter);

      const response = await this.#dpeApiClient.search(filter, user);
      results.push(...(response.results || []));

      if (!response.next_page_url) break;
      await this.#paginationService.saveNextPageUrl(user, filter, response.next_page_url);
    }

    const filename = this.#downloadService.getFileName(query, i18next);
    const table = this.#downloadService.mapRecordsToRows(results, i18next);

    const buffer = await tableGenerator.generate({
      ...table,
      sheetName: filename,
      format: query.format,
    });

    return {
      buffer,
      contentType: CONTENT_TYPES[query.format],
      filename,
    };
  }

  #saveHistory(user, query, count) {
    const searchHistoryService = new SearchHistoryService({ log: logger, user });
    searchHistoryService.createLeadFlow(query, count, PAGE_VIEW);
  }
}

module.exports = LeadFlowController;
