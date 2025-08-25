import React from 'react';
import i18next from 'i18next';
import { useDispatch, useSelector } from 'react-redux';

import * as ACTIONS from '../../../../../../actions/leadFlow/diagnosisPerformanceEnergy';
import * as SELECTORS from '../../../../../../selectors/leadFlow/diagnosisPerformanceEnergy';
import useDayRange from '../../../../../ui-kit/DayRange/useDayRange';
import useMultipleFilter from '../../../../../hooks/useMultipleFilter';
import { DPE } from '../../../../../../../configs/constants';
import { RangeShortcut } from '../../../../../../utils/datetime';
import { capitalize } from '../../../../../../utils/string';

import Button from '../../../../../ui-kit/Button/Button';
import Checkbox from '../../../../../ui-kit/Checkbox/Checkbox';
import SearchFilterDrop from '../../../../../commons/SearchFilterDrop';
import { AutocompleteLocation, useAutocompleteLocation } from '../../../AutocompleteLocation';
import { DayRange } from '../../../../../ui-kit/DayRange/DayRange';
import { TypesButton } from '../../../../../ui-kit/DayRange/components/buttons';

import './Filters.scss';

function FiltersDesktop() {
  const dispatch = useDispatch();
  const filters = useSelector(SELECTORS.getFilters);
  const autocompleteLocationProps = useAutocompleteLocation(SELECTORS.getFilters, ACTIONS.setFilters);
  const dayRangeProps = useDayRange(SELECTORS.getFilters, ACTIONS.setFilters, {
    default: RangeShortcut.options.last30Days,
  });
  const typeGroupProps = useMultipleFilter(SELECTORS.getFilters, ACTIONS.setFilters, 'typeGroups');
  const energyRatingProps = useMultipleFilter(SELECTORS.getFilters, ACTIONS.setFilters, 'energyRatings');

  const preConditions = RangeShortcut.getOptions([
    RangeShortcut.options.last3Days,
    RangeShortcut.options.last7Days,
    RangeShortcut.options.last14Days,
    RangeShortcut.options.last30Days,
    RangeShortcut.options.last90Days,
    RangeShortcut.options.thisYear,
  ]);

  const handleReset = () => {
    dispatch(ACTIONS.resetFilters());
    autocompleteLocationProps.handleReset();
    dayRangeProps.handleReset();
    typeGroupProps.handleClear();
    energyRatingProps.handleClear();
  };

  return (
    <div className="DPEFilters DPEFilters-desktop">
      <AutocompleteLocation
        className="DPEFilters__filter"
        section="lead-flow-diagnosis-performance-energy"
        value={autocompleteLocationProps.value}
        onChange={autocompleteLocationProps.handleChange}
        selected={autocompleteLocationProps.value.length}
      />
      <DayRange
        typeButton={TypesButton.Time}
        isMobile={false}
        updateFilter
        withTime={false}
        defaultStartDate={dayRangeProps.defaultStartDate}
        defaultEndDate={dayRangeProps.defaultEndDate}
        preConditions={preConditions}
        onChange={dayRangeProps.handleChange}
        onSearch={dayRangeProps.handleApply}
      />
      <div className="DPEFilters__filter DPEFilters__filter-typeGroups">
        <SearchFilterDrop
          dataCy="filter-leads-by-type-groups"
          name={i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.typeGroup')}
          updateFilter
          isActive={Boolean(typeGroupProps.initValue?.length)}
          counter={typeGroupProps.initValue?.length}
          handleSearch={typeGroupProps.handleApply}
          handleReset={() => typeGroupProps.handleClear(true)}
        >
          {Object.values(DPE.TYPE_GROUPS).map(typeGroup => (
            <div className="sort-row" key={typeGroup}>
              <Checkbox
                label={i18next.t(`pages.leadFlow.diagnosisPerformanceEnergy.typeGroups.${typeGroup}`)}
                onChange={() => typeGroupProps.handleChange(typeGroup)}
                checked={typeGroupProps.value.includes(typeGroup)}
              />
            </div>
          ))}
        </SearchFilterDrop>
      </div>
      <div className="DPEFilters__filter DPEFilters__filter-energyRatings">
        <SearchFilterDrop
          dataCy="filter-leads-by-energy-ratings"
          name={i18next.t('pages.leadFlow.diagnosisPerformanceEnergy.energyRating')}
          updateFilter
          isActive={Boolean(energyRatingProps.initValue?.length)}
          counter={energyRatingProps.initValue?.length}
          handleSearch={energyRatingProps.handleApply}
          handleReset={() => energyRatingProps.handleClear(true)}
        >
          {Object.values(DPE.ENERGY_RATINGS).map(energyRating => (
            <div className="sort-row" key={energyRating}>
              <Checkbox
                label={capitalize(energyRating.toLowerCase())}
                onChange={() => energyRatingProps.handleChange(energyRating)}
                checked={energyRatingProps.value.includes(energyRating)}
              />
            </div>
          ))}
        </SearchFilterDrop>
      </div>
      <Button
        className="DPEFilters__reset"
        appearance="transparent"
        icon="reset"
        onClick={handleReset}
        dataCy="filters-reset"
      />
    </div>
  );
}

export default FiltersDesktop;
