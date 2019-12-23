<?php

namespace app\core\entities;

use app\core\filters\ReferralFilter;
use app\core\helpers\EthHelper;
use app\core\services\ReferralService;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property int $role
 * @property string $login
 * @property string $password_hash
 * @property string $access_token
 * @property string $auth_key
 * @property string $facebook_id
 * @property string $facebook_link
 * @property string $twitter_id
 * @property string $twitter_link
 * @property string $linkedin_link
 * @property string $vk_id
 * @property string $vk_link
 * @property string $photo
 * @property int $is_worker
 * @property string $about
 * @property string $last_ip
 * @property string $created_at
 * @property string $updated_at
 * @property int $referral_id
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN            = 999;
    const ROLE_INVESTOR         = 1;
    const ROLE_BOUNTY_REQUEST   = 2;
    const ROLE_BOUNTY_AGENT     = 3;

    const SCENARIO_UPDATE       = 'update';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
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
            'required' => [['role', 'password_hash'], 'required'],
            [['role', 'referral_id'], 'integer'],
            'default-role' => [['role'], 'default', 'value' => self::ROLE_INVESTOR],
            [['role'], 'in', 'range' => [
                self::ROLE_ADMIN,
                self::ROLE_INVESTOR,
                self::ROLE_BOUNTY_AGENT,
                self::ROLE_BOUNTY_REQUEST,
            ]],
            [['email'], 'filter', 'filter' => 'trim'],
            [['email'], 'email'],
            [['email'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['facebook_link', 'twitter_link', 'linkedin_link', 'vk_link'], 'url',
                'skipOnEmpty' => true, 'defaultScheme' => '',
            ],
            [['login', 'password_hash', 'access_token', 'auth_key',
                'facebook_id', 'twitter_id', 'vk_id',
                'photo', 'is_worker', 'is_adviser', 'about', 'last_ip'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'role' => 'Role',
            'roleLabel' => 'Role',
            'login' => 'Login',
            'password_hash' => 'Password Hash',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'facebook_id' => 'FaceBook ID',
            'facebook_link' => 'FaceBook link',
            'twitter_id' => 'Twitter ID',
            'twitter_link' => 'Twitter link',
            'linkedin_link' => 'LinkedIn link',
            'vk_id' => 'VK ID',
            'vk_link' => 'VK link',
            'photo' => 'Photo',
            'is_worker' => 'Is worker',
            'about' => 'About',
            'last_ip' => 'Last Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'referral_id' => 'Referral ID',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()
            ->orWhere(['email' => $username])
            ->orWhere(['login' => $username])
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getRoleLabel()
    {
        if ($this->role == self::ROLE_ADMIN) {
            return Yii::t('admin', 'Admin');
        } elseif ($this->role == self::ROLE_INVESTOR) {
            return Yii::t('admin', 'Investor');
        } elseif ($this->role == self::ROLE_BOUNTY_REQUEST) {
            return Yii::t('admin', 'Bounty request');
        } elseif ($this->role == self::ROLE_BOUNTY_AGENT) {
            return Yii::t('admin', 'Bounty hunter');
        }
        return Yii::t('admin', 'Guest');
    }

    public function getBountyDeclineReason()
    {
        if ($this->role == self::ROLE_BOUNTY_REQUEST) {
            return $this->about;
        }
        return null;
    }

    public function getReferralLink()
    {
        return ['/', 'referral' => $this->id];
    }

    public function getTokens()
    {
        $real = $this->hasMany(BalanceLog::class, ['user_id' => 'id'])->sum('amount');
        return $real ? $real : EthHelper::getBalanceOf($this->profile->eth_address);
    }

    public function getSubscribe()
    {
        return $this->hasOne(Subscribe::class, ['user_id' => 'id']);
    }

    public function getFio()
    {
        return $this->profile->first_name . ' ' . $this->profile->last_name;
    }

    public function beforeSave($insert)
    {
        if ($insert && is_a(Yii::$app, 'yii\web\Application')) {
            $this->referral_id = ReferralFilter::getReferralID();
            $this->last_ip = Yii::$app->request->userIP;
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            Yii::createObject(ReferralService::class)->referralRegistration($this);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function getReferral()
    {
        return $this->hasOne(User::class, ['id' => 'referral_id']);
    }

    public function getProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    public function getIsVerification()
    {
        $this->profile->setScenario(UserProfile::SCENARIO_VALIDATE);
        return $this->profile->validate();
    }
}
