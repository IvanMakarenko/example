<?php

namespace app\core\entities;


use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bounty_quests".
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property int $status
 * @property string $link
 * @property string $comment
 * @property string $response
 * @property datetime $created_at
 * @property datetime $updated_at
 */
class BountyQuest extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE       = 'create';

    const TYPE_SOC_POST         = 0;
    const TYPE_SOC_RE_POST      = 1;
    const TYPE_SOC_COMMENT      = 2;
    const TYPE_SOC_LIKE         = 3;
    const TYPE_REFERRAL         = 4;
    const TYPE_ARTICLE          = 5;
    const TYPE_RE_POST_VIDEO    = 6;
    const TYPE_TRANSLATION      = 7;

    const STATUS_CHECK          = 0;
    const STATUS_DONE           = 1;
    const STATUS_FAIL           = -1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bounty_quests';
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
                'updatedAtAttribute' => 'updated_at',
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
            'required' => [['user_id', 'type', 'status'], 'required'],
            [['user_id', 'type', 'status'], 'integer'],
            [['type'], 'in', 'range' => [
                self::TYPE_SOC_POST,
                self::TYPE_SOC_RE_POST,
                self::TYPE_SOC_COMMENT,
                self::TYPE_SOC_LIKE,
                self::TYPE_REFERRAL,
                self::TYPE_ARTICLE,
                self::TYPE_RE_POST_VIDEO,
                self::TYPE_TRANSLATION,
            ]],
            [['status'], 'compare', 'compareValue' => self::STATUS_CHECK, 'on' => self::SCENARIO_CREATE],
            [['status'], 'in', 'range' => [
                self::STATUS_CHECK,
                self::STATUS_DONE,
                self::STATUS_FAIL,
            ]],
            [['link'], 'required', 'on' => self::SCENARIO_CREATE],
            [['link'], 'url', 'skipOnEmpty' => true, 'defaultScheme' => ''],
            [['comment', 'response'], 'string'],
            'timestamp' => [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('bounty', 'ID'),
            'user_id' => Yii::t('bounty', 'User ID'),
            'type' => Yii::t('bounty', 'Type'),
            'status' => Yii::t('bounty', 'Status'),
            'link' => Yii::t('bounty', 'Link'),
            'comment' => Yii::t('bounty', 'Comment'),
            'response' => Yii::t('bounty', 'Reason'),
            'created_at' => Yii::t('bounty', 'Date'),
            'updated_at' => Yii::t('bounty', 'Date of check'),
        ];
    }

    public function getTypeList()
    {
        $forCreate = [
            self::TYPE_SOC_POST => Yii::t('bounty', 'Post in soc. net.'),
            self::TYPE_SOC_RE_POST => Yii::t('bounty', 'Repost in soc. net.'),
            self::TYPE_SOC_COMMENT => Yii::t('bounty', 'Comment in soc. net.'),
            self::TYPE_SOC_LIKE => Yii::t('bounty', 'Like in soc. net.'),
            self::TYPE_ARTICLE => Yii::t('bounty', 'Article / Author\'s post'),
            self::TYPE_RE_POST_VIDEO => Yii::t('bounty', 'Repost video'),
            self::TYPE_TRANSLATION => Yii::t('bounty', 'Translation')
        ];
        if ($this->scenario != self::SCENARIO_CREATE) {
            $forCreate[self::TYPE_REFERRAL] = Yii::t('bounty', 'Referral');
        }
        return $forCreate;
    }

    public function getTypeLabel()
    {
        return ArrayHelper::getValue($this->getTypeList(), $this->type);
    }

    public function getStatusList()
    {
        return [
            self::STATUS_CHECK => Yii::t('bounty', 'Check'),
            self::STATUS_DONE => Yii::t('bounty', 'Done'),
            self::STATUS_FAIL => Yii::t('bounty', 'Fail'),
        ];
    }

    public function getStatusLabel()
    {
        return ArrayHelper::getValue($this->getStatusList(), $this->status);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getBalanceLog()
    {
        return $this->hasOne(BalanceLog::class, ['bounty_quest_id' => 'id']);
    }
}
