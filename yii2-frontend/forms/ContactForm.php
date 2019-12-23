<?php

namespace app\core\forms;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    public function init()
    {
        parent::init();

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $this->name = $user->fio;
            $this->email = $user->email;
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('contact', 'Your Name'),
            'email' => Yii::t('contact', 'Email'),
            'subject' => Yii::t('contact', 'Subject'),
            'body' => Yii::t('contact', 'Message'),
            'verifyCode' => Yii::t('contact', 'Verification Code'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @return bool whether the model passes validation
     */
    public function contact()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo(Yii::$app->params['supportEmail'])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body . '<br/><br/>'
                    . 'Email for answer: ' . $this->email . '<br/>'
                    . 'User name: ' . $this->name
                )
                ->send();

            return true;
        }
        return false;
    }
}
