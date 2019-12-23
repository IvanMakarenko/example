<?php

namespace api\models\forms;

use common\models\Campain;
use common\models\DriverLog;
use common\models\Quest;
use \DateTime;
use dosamigos\google\maps\LatLng;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Statistic form
 */
class StatisticForm extends Model
{
    public $latLng;
    public $is_working;
    public $quest_id;
    public $settings = [
        'category_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    ];
    public $hardware_status;
    public $image_hash;

    public function fields()
    {
        return [
            'balance' => function ($model) {
                return Yii::$app->user->identity->balance;
            },
            'time' => function ($model) {
                if (Yii::$app->user->can('driver')) {
                    $timestamp = DriverLog::find()
                        ->select('total_time')
                        ->andMy()
                        ->andCreatedLast24H()
                        ->orderBy(['created_at' => SORT_DESC])
                        ->scalar();
                    return gmdate('H:i:s', $timestamp);
                } elseif (Yii::$app->user->can('advertiser')) {
                    $time = new \DateTime('00:00');
                    $quests = Quest::find()
                        ->select(['created_at', 'finished_at'])
                        ->where(['campain_id' => Campain::find()->andMy()->column()])
                        ->andCreatedLast24H()
                        ->asArray()
                        ->all();
                    foreach ($quests as $quest) {
                        $max = new DateTime($quest['finished_at']);
                        $min = new DateTime($quest['created_at']);
                        $time->add($min->diff($max));
                        $result[] = $time->format('H:i:s');
                    }
                    return $time->format('H:i:s');
                }
                return 0;
            },
            'money' => function ($model) {
                $money = 0;
                $questsQuery = Quest::find()
                    ->andCreatedLast24H();

                if (Yii::$app->user->can('driver')) {
                    $questsQuery->andMy();
                } elseif (Yii::$app->user->can('advertiser')) {
                    $questsQuery->andWhere(['campain_id' => Campain::find()->andMy()->column()]);
                }

                foreach ($questsQuery->all() as $quest) {
                    if (Yii::$app->user->can('driver')) {
                        $money += $quest->getDriverIncome();
                    } elseif (Yii::$app->user->can('advertiser')) {
                        $money += $quest->getPrice();
                    }
                }
                return $money;
            },
            // driver fields
            'distance' => function ($model) {
                if (Yii::$app->user->can('driver')) {
                    $distance = DriverLog::find()
                        ->andMy()
                        ->andCreatedLast24H()
                        ->max('total_distance');
                    return round($distance, 2);
                }
                return 0;
            },
            'quest_id',
            // advertiser fields
            'views' => function ($model) {
                if (Yii::$app->user->can('advertiser')) {
                    return Quest::find()
                        ->andCreatedLast24H()
                        ->andWhere(['campain_id' => Campain::find()->andMy()->column()])
                        ->count();
                }
                return 0;
            },
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_working', 'latLng'], 'required', 'when' => function ($model) {
                return Yii::$app->user->can('driver');
            }],
            [['is_working', 'quest_id', 'hardware_status'], 'integer'],
            [['latLng', 'image_hash', 'settings'], 'safe'],
        ];
    }

    public function setSettings($data)
    {
        // Supporting of old data
        $data = JSON::decode($data);
        if (empty($data['category_ids'])) {
            $data['category_ids'] = $this->settings['category_ids'];
        }
        return $this->settings = $data;
    }

    public function update()
    {
        if (!$this->validate()) {
            throw new HttpException(422, $this->errors);
        }

        if (Yii::$app->user->can('driver')) {
            $this->saveDriverLog();
        }
    }

    private function saveDriverLog()
    {
        $this->latLng = new LatLng($this->latLng);
        $log = new DriverLog([
            'user_id'           => Yii::$app->user->id,
            'is_working'        => $this->is_working,
            'location'          => $this->latLng->__toString(),
            'quest_id'          => $this->quest_id,
            'hardware_status'   => $this->hardware_status,
            'image_hash'        => $this->image_hash,
            'category_ids'      => ArrayHelper::merge([11], $this->settings['category_ids']),
        ]);

        if (!$log->save()) {
            throw new HttpException(422, $log->errors);
        }
        $this->quest_id = $log->quest_id;
    }
}
