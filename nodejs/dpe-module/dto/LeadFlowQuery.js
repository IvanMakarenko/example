const moment = require('moment/moment');
const { decode } = require('latlon-geohash');

const { ALLOWED_IN_COUNTRY_IDS, DATE_OUTPUTS, DPE } = require('../constants');

class LeadFlowQuery {
  #VALID_TYPE_GROUPS = Object.values(DPE.TYPE_GROUPS);
  #VALID_ENERGY_RATINGS = Object.values(DPE.ENERGY_RATINGS);

  #DEFAULT_ORDER = Object.freeze({
    order: 'desc',
    order_by: 'certificate_issue_date',
  });

  constructor(query) {
    this.page = parseInt(query.page);
    this.limit = parseInt(query?.limit || query.pageSize);
    this.locationIds = query?.locations?.split(',')?.map(id => parseInt(id)) || ALLOWED_IN_COUNTRY_IDS;
    this.customLoc = query?.customLoc?.split(',');
    this.typeGroups = query?.typeGroups?.split(',').filter(item => this.#VALID_TYPE_GROUPS.includes(item));
    this.energyRatings = query?.energyRatings?.split(',').filter(item => this.#VALID_ENERGY_RATINGS.includes(item));
    this.livingArea__gte = parseInt(query?.livingArea__gte);
    this.livingArea__lte = parseInt(query?.livingArea__lte);
    this.createdAt__gte = query?.createdAt__gte && moment(query.createdAt__gte);
    this.createdAt__lte = query?.createdAt__lte && moment(query.createdAt__lte);
    this.searchParams = new URLSearchParams(this.toApiQuery());
  }

  #location_boundary() {
    const location_boundary = {};
    if (this.customLoc?.length) {
      location_boundary.polygon = this.customLoc.map((point) => {
        const { lat, lon } = decode(point);
        return {
          lat: parseFloat(lat),
          lon: parseFloat(lon),
        };
      });
    } else {
      location_boundary.location_ids = this.locationIds;
    }

    return { location_boundary };
  }

  #certificate_issue_date() {
    const certificate_issue_date = {};
    if (this.createdAt__lte) certificate_issue_date.lte = this.createdAt__lte.format(DATE_OUTPUTS.urlDay);
    if (this.createdAt__gte) certificate_issue_date.gte = this.createdAt__gte.format(DATE_OUTPUTS.urlDay);

    if (Object.values(certificate_issue_date).length) return { certificate_issue_date };
    return {};
  }

  #type_groups() {
    return this.typeGroups?.length && { type_groups: this.typeGroups };
  }

  #energy_ratings() {
    return this.energyRatings?.length && { energy_ratings: this.energyRatings };
  }

  #living_area() {
    const living_area = {};
    if (this.livingArea__lte) living_area.lte = this.livingArea__lte;
    if (this.livingArea__gte) living_area.gte = this.livingArea__gte;

    if (Object.values(living_area).length) return { living_area };
    return {};
  }

  toApiQuery() {
    return {
      limit: this.limit.toString(),
      ...this.#DEFAULT_ORDER,
    };
  }

  toApiBody() {
    return {
      ...this.#location_boundary(),
      ...this.#certificate_issue_date(),
      ...this.#type_groups(),
      ...this.#energy_ratings(),
      ...this.#living_area(),
    };
  }
}

module.exports = LeadFlowQuery;
