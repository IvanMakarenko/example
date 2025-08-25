const crypto = require('crypto');

const { ERRORS } = require('../constants');

class PaginationService {
  #cache;
  TTL = 1 * 60 * 60; // 1 hour

  constructor(cache) {
    this.#cache = cache;
  }

  static #getKey(user, filter, page) {
    const related = { ...filter.toApiQuery(), ...filter.toApiBody() };
    const sorted = JSON.stringify(related, Object.keys(related).sort());
    const filterHash = crypto.createHash('sha1').update(sorted).digest('hex');

    return `DPE:user:${user?._id}:filter:${filterHash}:page:${page}`;
  }

  async handleError(user) {
    const KEY = `DPE:user:${user?._id}:*`;
    await this.#cache.removeBySub(KEY);
    throw new ERRORS.PaginationCacheExpiredError();
  }

  async saveNextPageUrl(user, filter, nextPageUrl) {
    const KEY = PaginationService.#getKey(user, filter, filter.page + 1);
    return this.#cache.set(KEY, nextPageUrl, this.TTL);
  }

  async loadPaginationFromCache(user, filter) {
    if (filter.page < 2) return;

    const KEY = PaginationService.#getKey(user, filter, filter.page);
    const pageUrl = await this.#cache.get(KEY, false);

    if (!pageUrl) {
      await this.handleError(user);
    } else {
      const pageObj = new URL(pageUrl);
      filter.searchParams = pageObj.searchParams;
    }
  }
}

module.exports = PaginationService;
