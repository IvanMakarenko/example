<?php


namespace backend\components\columns;


use common\models\City;
use yii\bootstrap\Html;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class CityStatusColumn extends DataColumn
{
    public $withLabel = false;
    public $attribute = 'status';
    public $format = 'raw';
    public static $statusList = [
        City::STATUS_INACTIVE       => 'Inactive',
        City::STATUS_ACTIVE         => 'Active',
        City::STATUS_WAIT_CONFIRM   => 'Wait confirm',
    ];
    public $icon = [
        City::STATUS_INACTIVE       => 'remove',
        City::STATUS_ACTIVE         => 'ok',
        City::STATUS_WAIT_CONFIRM   => 'time',
    ];

    public function init()
    {
        parent::init();
        if (empty($this->filter)) {
            $this->filter = self::$statusList;
        }
    }

    public function getDataCellValue($model, $key, $index)
    {
        $status = parent::getDataCellValue($model, $key, $index);
        $label = ArrayHelper::getValue($this->filter, $status, 'Unknown');
        $icon = Html::icon(ArrayHelper::getValue($this->icon, $status, 'question-sign'), [
            'title' => $label,
        ]);

        if ($this->withLabel) {
            return $label . ' ' . $icon;
        }
        return $icon;
    }
}