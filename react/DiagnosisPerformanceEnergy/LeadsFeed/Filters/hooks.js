import { useDispatch, useSelector } from 'react-redux';

import * as SELECTORS from '../../../../../../selectors/leadFlow/diagnosisPerformanceEnergy';
import * as ACTIONS from '../../../../../../actions/leadFlow/diagnosisPerformanceEnergy';

export const useView = () => {
  const dispatch = useDispatch();
  const filters = useSelector(SELECTORS.getFilters);
  const value = filters.view;

  const onChange = (e, newValue) => dispatch(ACTIONS.setFilters({ ...filters, view: newValue }));

  return {
    value,
    onChange,
  };
};
