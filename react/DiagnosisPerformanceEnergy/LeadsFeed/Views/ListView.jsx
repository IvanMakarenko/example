import React from 'react';
import i18next from 'i18next';
import moment from 'moment';
import { useSelector } from 'react-redux';

import * as ACTIONS from '../../../../../../actions/leadFlow/diagnosisPerformanceEnergy';
import * as SELECTORS from '../../../../../../selectors/leadFlow/diagnosisPerformanceEnergy';
import usePagination from '../../../../../ui-kit/Pagination/usePagination';
import { DATE_OUTPUTS, PAGINATION } from '../../../../../../../configs/constants';
import { getMobileModeState } from '../../../../../../selectors';

import DataGrid from '../../../../../ui-kit/DataGrid/DataGrid';
import MirrorScrollbar from '../../../../../ui-kit/MirrorScrollbar/MirrorScrollbar';
import PaginationSequential from '../../../../../ui-kit/Pagination/PaginationSequential';
import { GradeCell, TotalAreaCell } from '../cells';

function ListView() {
  const isMobile = useSelector(getMobileModeState);
  const count = useSelector(SELECTORS.getTotalLeadsFeed);
  const isLoading = useSelector(SELECTORS.getLeadsFeedIsLoading);
  const leads = useSelector(SELECTORS.getLeadsFeed);
  const paginationProps = usePagination(SELECTORS.getFilters, ACTIONS.setFilters, count);

  return (
    <>
      <MirrorScrollbar minHeightToShowScrollbar={500} isActive={!isMobile}>
        <DataGrid
          stripped
          edgeShadows
          className="EPDLeadsFeed__grid"
          getRowId={row => row?.certificate_id}
          columns={[
            {
              field: 'certificate_id',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.certificateId'),
            },
            {
              field: 'address',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.address'),
            },
            {
              field: 'certificate_issue_date',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.certificateIssueDate'),
              valueFormatter: ({ value }) => moment(value).format(DATE_OUTPUTS.day),
            },
            {
              field: 'inspection_date',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.inspectionDate'),
              valueFormatter: ({ value }) => moment(value).format(DATE_OUTPUTS.day),
            },
            {
              field: 'type_group',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.typeGroup'),
              valueFormatter: ({ value }) => i18next.t(`pages.leadFlow.diagnosisPerformanceEnergy.typeGroups.${value}`),
            },
            {
              field: 'living_area',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.livingArea'),
              renderCell: TotalAreaCell,
            },
            {
              field: 'energy_rating',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.energyRating'),
              renderCell: GradeCell,
            },
            {
              field: 'parcel_id',
              headerName: i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.parcelId'),
            },
          ]}
          rows={leads}
          loading={isLoading}
          emptyTitle={i18next.t('pages.leadFlow.anyLeads')}
          emptyDescription={i18next.t('pages.leadFlow.trySelectOtherDateRange')}
        />
      </MirrorScrollbar>
      <PaginationSequential
        className="EPDLeadsFeed__pagination"
        rowsPerPageOptions={PAGINATION.availablePageSizes}
        disabled={isLoading}
        {...paginationProps}
      />
    </>
  );
}

export default ListView;
