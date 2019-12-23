<?php

namespace app\core\forms;


use app\core\entities\Subscribe;
use app\core\services\MailService;
use app\core\validators\EmailMxRecordValidator;
use Yii;
use yii\base\Model;

/**
 * Class SubscribeForm
 * @package core\forms
 * @author Ivan Makarenko <shketiam@gmail.com>
 */
class SubscribeForm extends Model
{
    public $email;

    /**
     * Initializes email by the current user if it is not a guest.
     */
    public function init()
    {
        if (!Yii::$app->user->isGuest) {
            $this->email = Yii::$app->user->identity->email;
        }
        parent::init();
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', EmailMxRecordValidator::class],
            ['email', 'unique', 'targetClass' => Subscribe::class,
                'message' => Yii::t('app', 'A user with this email address is already subscribed'),
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'email',
        ];
    }

    public function subscribe()
    {
        if ($this->validate()) {
            $model = Subscribe::create($this);
            $mailService = Yii::createObject(MailService::class);
            $mailService->sendSubscribeRequest($model);
            return true;
        }

        return false;
    }
}