<?php

namespace app\core\forms;


use app\core\services\MailService;
use app\core\entities\User;
use Yii;
use yii\base\Model;

/**
 * BountyRequestForm is the model behind the request form to take part in bounty.
 *
 * @property User|null $user This property is read-only.
 *
 */
class BountyRequestForm extends Model
{
    public $facebook_link;
    public $twitter_link;
    public $linkedin_link;
    public $vk_link;
    public $eth_address;

    private $_user = false;
    private $mailService;

    public function __construct($config = []) {
        $this->mailService = Yii::createObject(MailService::class);
        $this->_user = Yii::$app->user->identity;
        parent::__construct($config);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
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
                    if ($("#bountyrequestform-facebook_link").val()
                        || $("#bountyrequestform-twitter_link").val()
                        || $("#bountyrequestform-linkedin_link").val()
                        || $("#bountyrequestform-vk_link").val()) {
                        $("#bountyrequestform-vk_link").parents("form").yiiActiveForm("validate", true);
                        return false;
                    } else {
                        return true;
                    }
                }',
            ],
            [['eth_address'], 'required'],
            [['eth_address'], 'match', 'pattern' => '/^(0x)?[0-9a-f]{40}$/i',
                'message' => Yii::t('app', 'Enter Ethereum address in a format that starts 0x.'),
            ],
        ];
    }

    /**
     * Request to take part in bounty.
     */
    public function request()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->_user->role = User::ROLE_BOUNTY_REQUEST;
        $this->_user->facebook_link = $this->facebook_link;
        $this->_user->twitter_link = $this->twitter_link;
        $this->_user->linkedin_link = $this->linkedin_link;
        $this->_user->vk_link = $this->vk_link;

        $this->_user->profile->eth_address = $this->eth_address;

        try {
            if ($this->_user->save() && $this->_user->profile->save()) {
                $this->mailService->sendBountyRequestMessage($this->_user);
                return true;
            }
        } catch (\Exception $e) {
            $this->addError(self::formName(), $e->getMessage());
        }

        $this->addErrors($this->_user->errors);
        $this->addErrors($this->_user->profile->errors);
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
        return implode(', ', $links);
    }
}
