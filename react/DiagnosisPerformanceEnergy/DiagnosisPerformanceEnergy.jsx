import React from 'react';
import i18next from 'i18next';

import Layout from '../Layout/Layout';
import LeadsFeed from './LeadsFeed/LeadsFeed';
import Typography from '../../../ui-kit/Typography/Typography';

import './DiagnosisPerformanceEnergy.scss';

function DiagnosisPerformanceEnergy(props) {
  return (
    <Layout {...props} className={DiagnosisPerformanceEnergy.displayName}>
      <Typography variant="h1">
        {i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.title')}
      </Typography>

      <LeadsFeed />
    </Layout>
  );
}

DiagnosisPerformanceEnergy.displayName = 'DiagnosisPerformanceEnergy';

DiagnosisPerformanceEnergy.propTypes = {};

export default DiagnosisPerformanceEnergy;
