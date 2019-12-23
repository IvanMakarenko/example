<?php

namespace api\models\entities;

use common\models\Campain as BaseCampain;
use common\models\CampainArea;
use common\models\CampainCategory;
use common\models\CampainHistory;
use common\models\Quest;
use common\models\User;
use Yii;

class Campain extends BaseCampain
{
    public $area_ids;
    public $category_ids;

    public function init()
    {
        parent::init();
        if (!Yii::$app->user->can(User::ROLE_ADMIN)) {
            $this->user_id = Yii::$app->user->id;
        }
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['category_ids'], 'required'];
        $rules[] = [['area_ids'], 'required', 'when' => function($model) {
            return $model->type === self::TYPE_CUSTOM;
        }];
        $rules[] = [['area_ids', 'category_ids'], 'each', 'rule' => ['integer']];
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['area_ids'] = 'City`s Areas';
        $labels['category_ids'] = 'Ads Categories';
        return $labels;
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'color',
            'status',
            'type',
            'source',
            'budget',
            'spentToday',
            'balance',
            'min_price',
            'max_price',
            'count',
            'time_from',
            'time_to',
            'datetime_from',
            'datetime_to',
            'created_at',
            'finished_at',
        ];
    }

    public function extraFields()
    {
        return [
            'drivers' => function($model) {
                $questsIds = Quest::find()
                    ->select('id')
                    ->where(['campain_id' => $this->id])
                    ->andWhere(['finished_at' => null])
                    ->column();
                $driversLogsIds = Driver::find()
                    ->select('max(id)')
                    ->andWhere(['is_working' => 1])
                    ->andWhere(['quest_id' => $questsIds])
                    ->andCreatedLast24H()
                    ->groupBy('user_id')
                    ->column();
                return Driver::findAll($driversLogsIds);
            },
            'area_ids' => $this->getAreaIds(),
            'category_ids' => $this->getCategoryIds(),
        ];
    }

    public function getAreaIds()
    {
        return CampainArea::find()
            ->select('area_id')
            ->andWhere(['campain_id' => $this->id])
            ->column();
    }

    public function getCategoryIds()
    {
        return CampainCategory::find()
            ->select('category_id')
            ->andWhere(['campain_id' => $this->id])
            ->column();
    }

    public function beforeSave($insert)
    {
        // Save copy into the history table before update
        if (!$insert) {
            CampainHistory::copy($this->id);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        // Remove old relations, before add new
        if (!$insert) {
            CampainArea::deleteAll(['campain_id' => $this->id]);
            CampainCategory::deleteAll(['campain_id' => $this->id]);
        }
        if (!empty($this->area_ids)) {
            foreach ($this->area_ids as $area_id) {
                $model = new CampainArea([
                    'campain_id' => $this->id,
                    'area_id' => $area_id,
                ]);
                $model->save();
            }
        }
        if (!empty($this->category_ids)) {
            foreach ($this->category_ids as $category_id) {
                $model = new CampainCategory([
                    'campain_id' => $this->id,
                    'category_id' => $category_id,
                ]);
                $model->save();
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function getDrivers()
    {
        $logIds = Driver::find()
            ->select('max(id)')
            ->andWhere(['is_working' => 1])
            ->andCreatedLast24H()
            ->groupBy('user_id')
            ->column();

        return $this->hasMany(Driver::class, ['quest_id' => 'id'])
            ->viaTable(Quest::tableName(), ['campain_id' => 'id'])
            ->andWhere([Driver::tableName() . '.id' => $logIds]);
    }
}
