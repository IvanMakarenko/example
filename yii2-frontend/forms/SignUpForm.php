<?php

namespace app\core\forms;


use app\core\entities\Subscribe;
use app\core\entities\UserProfile;
use app\core\services\MailService;
use app\core\validators\EmailMxRecordValidator;
use app\core\entities\User;
use Yii;
use yii\base\Model;

/**
 * SignUpForm is the model behind the registration form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignUpForm extends Model
{
    const PASSWORD_LENGTH = 8;

    public $name;
    public $surname;
    public $login;
    public $email;
    public $agree = false;
    public $isSubscribe = true;
    public $rememberMe = true;

    private $_user = false;
    private $_password = false;
    private $mailService;

    public function __construct($config = []) {
        $this->mailService = Yii::createObject(MailService::class);
        $this->_password = trim(Yii::$app->getSecurity()->generateRandomString(self::PASSWORD_LENGTH));
        parent::__construct($config);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['name', 'surname', 'login', 'email'], 'filter', 'filter' => 'trim'],
            [['name', 'surname', 'login'], 'string'],

            [['email'], 'email'],
            [['email'], EmailMxRecordValidator::class],
            [['email'], 'unique', 'targetClass' => User::class,
                'message' => Yii::t('registration', 'This e-mail is being used'),
            ],

            [['login'], 'unique', 'targetClass' => User::class,
                'message' => Yii::t('registration', 'This login is already being used'),
            ],

            [['agree'], 'required', 'requiredValue' => 1,
                'message' => Yii::t ('registration', 'It is necessary to accept an agreement'),
            ],

            [['isSubscribe', 'rememberMe'], 'boolean'],
        ];
    }

    /**
     * Registration new user.
     */
    public function registration()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->_user = new User([
            'login' => $this->login,
            'email' => $this->email,
            'role' => User::ROLE_INVESTOR,
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash($this->_password),
        ]);

        try {
            if ($this->_user->save()) {
                $profile = new UserProfile([
                    'user_id' => $this->_user->id,
                    'first_name' => $this->name,
                    'last_name' => $this->surname,
                ]);

                if ($profile->save()) {
                    $this->mailService->sendRegistrationMessage($this->_user, $this->_password);
                    Subscribe::updateByUser($this->_user, $this->isSubscribe);
                    return true;
                }
                $this->addErrors($profile->errors);
            }
        } catch (\Exception $e) {
            if ($this->_user->id) {
                $this->_user->delete();
            }
            $this->addError(self::formName(), $e->getMessage());
        }
        $this->addErrors($this->_user->errors);
        return false;
    }
}
