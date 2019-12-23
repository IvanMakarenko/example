<?php

namespace api\models\forms;

use common\models\User;
use Symfony\Component\Debug\ExceptionHandler;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Sign Up form
 */
class SignupForm extends Model
{
    const PASSWORD_LENGTH = 8;


    public $username;
    public $phone;
    public $email;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class,
                'message' => 'This username has already been taken.',
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'unique', 'targetClass' => User::class,
                'message' => 'This phone number has already been taken.',
            ],
            ['phone', 'match', 'pattern' => '/[0-9]+$/i',
                'message' => 'Phone must contain only numbers, from 0 to 9.',
            ],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class,
                'message' => 'This email address has already been taken.',
            ],

            ['role', 'required'],
            ['role', 'in', 'range' => ['driver', 'advertiser']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $password = Yii::$app->security->generateRandomString(self::PASSWORD_LENGTH);
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if ($user->save()) {
            try {
                $userRole = Yii::$app->authManager->getRole($this->role);
                Yii::$app->authManager->assign($userRole, $user->getId());
            } catch (\Exception $e) {
                throw new \yii\web\HttpException(500, 'Role dos not exists.');
            }

            if ($this->sendEmail($user, $password)) {
                return $user->auth_key;
            }
            throw new \yii\web\HttpException(500, 'Problem with sending confirm mail.');
        }
        throw new \yii\web\HttpException(500, 'Problem with saving data.');
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @param String $password just generated user`s password
     * @return bool whether the email was sent
     */
    protected function sendEmail($user, $password)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user, 'password' => $password]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration | ' . Yii::$app->name)
            ->send();
    }
}
