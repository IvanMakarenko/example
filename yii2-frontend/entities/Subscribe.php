<?php

namespace app\core\entities;


use app\core\forms\SubscribeForm;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "subscriptions".
 *
 * @property integer $id
 * @property integer $status
 * @property string $email
 * @property string $token
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Subscribe extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_READ = 2;


    public static function tableName()
    {
        return '{{%subscriptions}}';
    }

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

    public function rules()
    {
        return [
            [['id', 'status', 'user_id'], 'integer'],
            [['email', 'token'], 'string'],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'email' => 'Email',
            'user_id' => 'User ID',
            'created_at' => 'Subscribed at',
            'updated_at' => 'Updated at',
        ];
    }

    public static function create(SubscribeForm $form)
    {
        $item = new static([
            'status'    => self::STATUS_INACTIVE,
            'email'     => $form->email,
            'token'     => Yii::$app->security->generateRandomString() . '_' . time(),
            'user_id'   => Yii::$app->user->isGuest ? null : Yii::$app->user->identity->id,
        ]);
        $item->save();

        return $item;
    }

    public static function updateByUser(User $user, $status)
    {
        if ($item = $user->subscribe) {
            if ($item->email != $user->email && !empty($user->email)) {
                $item->email = $user->email;
            }
            $item->status = $status ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
        } else {
            $item = new static([
                'status' => $status ? self::STATUS_ACTIVE : self::STATUS_INACTIVE,
                'email' => $user->email,
                'token' => Yii::$app->security->generateRandomString() . '_' . time(),
                'user_id' => $user->id,
            ]);
        }
        $item->save();

        return $item;
    }

    public function read()
    {
        $this->status = self::STATUS_READ;
        return $this->save();
    }

    public function confirm()
    {
        $this->status = self::STATUS_ACTIVE;
        return $this->save();
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function findByMail($email)
    {
        if ($model = self::findOne(['email' => $email])) {
            return $model;
        }
        if ($user = User::findOne(['email' => $email])) {
            return $user->subscribe;
        }
        return null;
    }
}
