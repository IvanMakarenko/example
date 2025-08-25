import React from 'react';
import i18next from 'i18next';
import { useDispatch, useSelector } from 'react-redux';

import * as ACTIONS from '../../../../../actions/leadFlow/diagnosisPerformanceEnergy';
import * as SELECTORS from '../../../../../selectors/leadFlow/diagnosisPerformanceEnergy';
import capitalize from '../../../../../utils/capitalize';
import { DPE, LEAD_FLOW } from '../../../../../../configs/constants';
import { useView } from './Filters/hooks';

import DownloadButton from '../../DownloadButton/DownloadButton';
import Filters from './Filters';
import Typography from '../../../../ui-kit/Typography/Typography';
import Views from './Views';
import { SegmentedRadio, Radio } from '../../../../ui-kit/SegmentedRadio/SegmentedRadio';

import './LeadsFeed.scss';

function LeadsFeed() {
  const dispatch = useDispatch();
  const count = useSelector(SELECTORS.getTotalLeadsFeed);
  const isLoading = useSelector(SELECTORS.getLeadsFeedIsLoading);
  const isDownloading = useSelector(SELECTORS.getLeadsFeedIsDownloading);
  const leads = useSelector(SELECTORS.getLeadsFeed);
  const viewProps = useView();
  const isShowLimitTooltip = count > LEAD_FLOW.LIMIT_FOR_DOWNLOAD;
  const SelectedView = Views[`${capitalize(viewProps.value)}View`];

  const handleDownload = (event, format) => dispatch(ACTIONS.downloadLeadsFeed(format));

  return (
    <div className={LeadsFeed.displayName} data-cy="diagnosis-performance-energy-feed">
      <div className={`${LeadsFeed.displayName}__header`}>
        <div className={`${LeadsFeed.displayName}__title`}>
          <Typography variant="h3">{i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.feed')}</Typography>
          <Typography size="md" className={`${LeadsFeed.displayName}__total`}>
            {count !== undefined && `(${count})`}
          </Typography>
        </div>
        {leads?.length ? (
          <DownloadButton
            supportFormats={LEAD_FLOW.DOWNLOAD_FORMATS}
            tooltipDescription={
              isShowLimitTooltip ? i18next.t('pages.leadFlow.downloadLimit', {
                count: LEAD_FLOW.LIMIT_FOR_DOWNLOAD,
                items: i18next.t('pages.leadFlow.records'),
              }) : ''
            }
            onClick={handleDownload}
            dataCy="download-leads"
            isDisabled={isLoading}
            isLoading={isDownloading}
          />
        ) : null}
      </div>
      <div className={`${LeadsFeed.displayName}__controls`}>
        <Filters />
        <SegmentedRadio className={`${LeadsFeed.displayName}__view`} {...viewProps}>
          {Object.entries(DPE.VIEWS).map(([, value]) => (
            <Radio key={value} value={value} data-cy={`DPE-view-${value}`}>
              {i18next.t(`pages.leadFlow.diagnosisPerformanceEnergy.view.${value}`)}
            </Radio>
          ))}
        </SegmentedRadio>
      </div>
      <SelectedView />
    </div>
  );
}

LeadsFeed.displayName = 'EPDLeadsFeed';

export default LeadsFeed;
