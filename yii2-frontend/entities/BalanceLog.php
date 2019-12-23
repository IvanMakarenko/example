<?php

namespace app\core\entities;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Html;

/**
 * This is the model class for table "balance_logs".
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property double $amount
 * @property int $bounty_quest_id
 * @property string $created_at
 * @property int $created_by
 */
class BalanceLog extends \yii\db\ActiveRecord
{
    const SCENARIO_BOUNTY = 'bounty';

    const TYPE_BUY      = 1;
    const TYPE_BOUNTY   = 2;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'balance_logs';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'amount'], 'required'],
            [['user_id', 'type', 'bounty_quest_id', 'created_by'], 'integer'],
            [['type'], 'compare', 'compareValue' => self::TYPE_BUY, 'on' => self::SCENARIO_DEFAULT],
            [['type'], 'compare', 'compareValue' => self::TYPE_BOUNTY, 'on' => self::SCENARIO_BOUNTY],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['bounty_quest_id', 'created_by'], 'required', 'on' => self::SCENARIO_BOUNTY],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'tokens' => Yii::t('personal', 'Amount of tokens'),
            'bounty_quest_id' => 'Bounty Quest ID',
            'created_at' => Yii::t('personal', 'Date'),
            'created_by' => 'Created By',
            'additional' => Yii::t('personal', 'Additional'),
        ];
    }

    public function getQuest()
    {
        return $this->hasOne(BountyQuest::class, ['id' => 'bounty_quest_id']);
    }

    public function getTokens()
    {
        return $this->amount;
    }

    public function getAdditional()
    {
        if ($this->quest) {
            return Yii::t('personal', 'By quest #{0}', Html::a($this->bounty_quest_id, [
                'cabinet/bounty',
                'BountyQuestsSearch[id]' => $this->bounty_quest_id,
            ]));
        }
        return null;
    }
}
