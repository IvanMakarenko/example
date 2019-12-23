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
class BountySignUpForm extends Model
{
    const PASSWORD_LENGTH = 8;

    public $name;
    public $surname;
    public $facebook_link;
    public $twitter_link;
    public $linkedin_link;
    public $vk_link;
    public $email;
    public $eth_address;
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
            [['name', 'email', 'eth_address'], 'required'],
            [['name', 'surname', 'email'], 'filter', 'filter' => 'trim'],
            [['name', 'surname'], 'string'],

            [['email'], 'email'],
            [['email'], EmailMxRecordValidator::class],
            [['email'], 'unique', 'targetClass' => User::class],

            [['facebook_link', 'twitter_link', 'linkedin_link', 'vk_link'], 'url',
                'skipOnEmpty' => true, 'defaultScheme' => '',
            ],
            [['socialLinks'], 'required',
                'message' => Yii::t('registration', 'Indicate at least one social network'),
                'when' => function ($model) {
                    return empty($model->facebook_link)
                        && empty($model->twitter_link)
                        && empty($model->linkedin_link)
                        && empty($model->vk_link);
                },
                'whenClient' => 'function (attribute, value) {
                    if ($("#bountysignupform-facebook_link").val()
                        || $("#bountysignupform-twitter_link").val()
                        || $("#bountysignupform-linkedin_link").val()
                        || $("#bountysignupform-vk_link").val()
                    ) {
                        $("#bountysignupform-vk_link").parents("form").yiiActiveForm("validate", true);
                        return false;
                    } else {
                        return true;
                    }
                }',
            ],

            [['eth_address'], 'match', 'pattern' => '/^(0x)?[0-9a-f]{40}$/i',
                'message' => Yii::t('app', 'Enter Ethereum address in a format that starts 0x.'),
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
            'email' => $this->email,
            'role' => User::ROLE_BOUNTY_REQUEST,
            'facebook_link' => $this->facebook_link,
            'twitter_link' => $this->twitter_link,
            'linkedin_link' => $this->linkedin_link,
            'vk_link' => $this->vk_link,
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash($this->_password),
        ]);

        try {
            if ($this->_user->save()) {
                $profile = new UserProfile([
                    'user_id' => $this->_user->id,
                    'first_name' => $this->name,
                    'last_name' => $this->surname,
                    'eth_address' =>$this->eth_address,
                ]);

                if ($profile->save()) {
                    $this->mailService->sendBountyRequestMessage($this->_user, $this->_password);
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

    public function getSocialLinks()
    {
        $links = [];

        if ($this->facebook_link) {
            $links[] = $this->facebook_link;
        }
        if ($this->twitter_link) {
            $links[] = $this->twitter_link;
        }
        if ($this->linkedin_link) {
            $links[] = $this->linkedin_link;
        }
        if ($this->vk_link) {
            $links[] = $this->vk_link;
        }
        return $links;
    }
}
