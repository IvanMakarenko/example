import React from 'react';
import PropTypes from 'prop-types';

import { DPE, ENERGY_RATINGS } from '../../../../../../../configs/constants';

import Chip from '../../../../../ui-kit/Chip/Chip';
import Typography from '../../../../../ui-kit/Typography/Typography';

import './GradeCell.scss';

const COLOR_MAP = Object.freeze({
  [Chip.COLORS.SUCCESS]: ENERGY_RATINGS.high,
  [Chip.COLORS.WARNING]: ENERGY_RATINGS.average,
  [Chip.COLORS.ERROR]: ENERGY_RATINGS.low,
});

const colorMap = value => Object.entries(COLOR_MAP)
  .find(([, values]) => values.includes(value))?.[0] || Chip.COLORS.INFO;

function GradeCell(props) {
  const { value } = props;

  if (!value || value === DPE.UNKNOWN) {
    return <Typography size="md">—</Typography>;
  }

  return (
    <Chip className="GradeCell" size="sm" weight="semi-bold" color={colorMap(value)}>
      {value}
    </Chip>
  );
}

GradeCell.propTypes = {
  value: PropTypes.any,
};

export default GradeCell;
