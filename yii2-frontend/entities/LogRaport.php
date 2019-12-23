<?php

namespace app\core\entities;

use app\core\helpers\EthHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class LogRaport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_raports';
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
            [['id', 'users', 'bounty_queries', 'verified_investors', 'quests', 'quests_need_checks'], 'integer'],
            [['supply', 'mbc_in_bounty'], 'number'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public static function create()
    {
        return new self([
            'supply' => round(EthHelper::getTotalSupply(), 2),
            'users' => User::find()->where(['<>', 'role', User::ROLE_ADMIN])->count(),
            'bounty_queries' => User::find()->where(['role' => User::ROLE_BOUNTY_REQUEST])->count(),
            'verified_investors' => UserProfile::find()->where(['status' => UserProfile::STATUS_VERIFY])->count(),
            'quests' => BountyQuest::find()->count(),
            'quests_need_checks' => BountyQuest::find()->where(['status' => BountyQuest::STATUS_CHECK])->count(),
            'mbc_in_bounty' => BalanceLog::find()->where(['type' => BalanceLog::TYPE_BOUNTY])->sum('amount'),
        ]);
    }
}