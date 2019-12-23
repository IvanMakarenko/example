<?php

namespace app\core\entities;

use app\core\helpers\ClientHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_profiles".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $birthday_at
 * @property string $phone
 * @property string $employment
 * @property integer $income
 * @property integer $contribute
 * @property string $eth_address
 * @property string $country
 * @property string $zip_code
 * @property string $city
 * @property string $state
 * @property string $street
 * @property string $appartment
 * @property string $document_type
 * @property string $issuing_country
 * @property string $doc_font
 * @property string $doc_back
 * @property string $doc_identity
 * @property string $doc_proof
 * @property integer $status
 */
class UserProfile extends \yii\db\ActiveRecord
{
    const SCENARIO_VALIDATE = 'validate';

    const STATUS_NOT_VERIFY = 0;
    const STATUS_VERIFY     = 1;
    const STATUS_IN_PROCESS = 2;
    const STATUS_ERROR      = 3;

    public $font_file;
    public $back_file;
    public $identity_file;
    public $proof_file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            'required-relation' => [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['user_id'], 'unique'],

            [['birthday_at'], 'date', 'format' => 'php:Y-m-d'],

            [['first_name', 'last_name', 'middle_name', 'employment', 'country',
                    'zip_code', 'city', 'state', 'street', 'appartment', 'document_type', 'issuing_country',
                    'doc_font', 'doc_back', 'doc_identity', 'doc_proof'
                ], 'string', 'max' => 255,
            ],

            [['income', 'contribute'], 'number', 'min' => 1],

            [['eth_address'], 'match', 'pattern' => '/^(0x)?[0-9a-f]{40}$/i',
                'message' => Yii::t('app', 'Enter Ethereum address in a format that starts 0x.'),
            ],

            [['phone'], 'string', 'min' => 7, 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^\+?[\d\-\(\)]{0,20}$/i'],

            'required' => [['first_name', 'last_name', 'birthday_at', 'employment', 'income',
                    'eth_address', 'issuing_country', 'country', 'city', 'street', 'document_type',
                    'issuing_country', 'doc_font', 'doc_back',
                ], 'required', 'on' => self::SCENARIO_VALIDATE,
            ],

            [['font_file', 'back_file', 'identity_file', 'proof_file'], 'file', 'skipOnEmpty' => true,
                'extensions' => 'jpg, jpeg, png',
                'maxFiles' => 1,
            ],

            [['country', 'issuing_country'], 'default', 'value' => ClientHelper::getCountryCode()],

            [['status'], 'integer'],
            'status-default' => [['status'], 'default', 'value' => self::STATUS_NOT_VERIFY],
            [['status'], 'in', 'range' => [
                self::STATUS_NOT_VERIFY,
                self::STATUS_VERIFY,
                self::STATUS_IN_PROCESS,
                self::STATUS_ERROR,
            ]],
        ];
    }

    /**
     * Add auto upload files after posts.
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        unset($data[$scope]['font_file']);
        unset($data[$scope]['back_file']);
        unset($data[$scope]['identity_file']);
        unset($data[$scope]['proof_file']);
        $result = parent::load($data, $formName);

        $this->upload('font');
        $this->upload('back');
        $this->upload('identity');
        $this->upload('proof');

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('personal', 'ID'),
            'user_id' => Yii::t('personal', 'User ID'),
            'first_name' => Yii::t('personal', 'First Name'),
            'last_name' => Yii::t('personal', 'Last Name'),
            'middle_name' => Yii::t('personal', 'Middle Name'),
            'birthday_at' => Yii::t('personal', 'Date of Birth'),
            'phone' => Yii::t('personal', 'Phone Number'),
            'employment' => Yii::t('personal', 'Employment or occupation'),
            'income' => Yii::t('personal', 'Approx. Annual Gross Income'),
            'contribute' => Yii::t('personal', 'How much ETH do you want to contribute?'),
            'eth_address' => Yii::t('app', 'Ethereum address'),
            'country' => Yii::t('personal', 'Country'),
            'zip_code' => Yii::t('personal', 'Zip Code'),
            'city' => Yii::t('personal', 'City'),
            'state' => Yii::t('personal', 'State/Province'),
            'street' => Yii::t('personal', 'Street'),
            'appartment' => Yii::t('personal', 'Apartment/Suite'),
            'document_type' => Yii::t('personal', 'Document Type'),
            'issuing_country' => Yii::t('personal', 'Issuing County'),
            'doc_font' => Yii::t('personal', 'Document(Front)'),
            'font_file' => Yii::t('personal', 'Document(Front)'),
            'doc_back' => Yii::t('personal', 'Document(Back)'),
            'back_file' => Yii::t('personal', 'Document(Back)'),
            'doc_identity' => Yii::t('personal', 'Selfie with Passport / Identity Document'),
            'identity_file' => Yii::t('personal', 'Selfie with Passport / Identity Document'),
            'doc_proof' => Yii::t('personal', 'Proof of current address (e.g. utility bill, driving licence)'),
            'proof_file' => Yii::t('personal', 'Proof of current address (e.g. utility bill, driving licence)'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getStatusList()
    {
        return [
            self::STATUS_NOT_VERIFY => Yii::t('personal', 'Verify your account to access all financial transactions'),
            self::STATUS_VERIFY => Yii::t('personal', 'Your account has been successfully verified'),
            self::STATUS_IN_PROCESS => Yii::t('personal', 'Verification in the process'),
            self::STATUS_ERROR => Yii::t('personal', 'In the verification process, errors were found. <br> Please correct them and retry the verification request'),
        ];
    }

    public function getStatusLabel()
    {
        return ArrayHelper::getValue($this->getStatusList(), $this->status);
    }

    public function getStatusDescriptionList()
    {
        return [
            self::STATUS_NOT_VERIFY => Yii::t('personal', 'Filling out the application will take several minutes'),
            self::STATUS_VERIFY => Yii::t('personal', 'Now you can make any purchases'),
            self::STATUS_IN_PROCESS => Yii::t('personal', 'The verification process can take up to several days'),
            self::STATUS_ERROR => Yii::t('personal', 'Filling out the application will take several minutes'),
        ];
    }

    public function getStatusDescription()
    {
        return ArrayHelper::getValue($this->getStatusDescriptionList(), $this->status);
    }

    public function getIsVerify()
    {
        return $this->status == self::STATUS_VERIFY;
    }

    public function getDocumentTypeList()
    {
        return [
            Yii::t('personal', 'Passport'),
            Yii::t('personal', 'Driver`s license'),
            Yii::t('personal', 'International passport'),
            Yii::t('personal', 'ID card'),
        ];
    }

    public function getCountryFullName()
    {
        $country = $this->hasOne(Country::class, ['code' => 'country'])->one();
        return empty($country) ? '' : $country->name . ' (' . $country->code . ')';
    }

    protected function upload($attr)
    {
        if ($file = UploadedFile::getInstance($this, $attr . '_file')) {
            $path = 'images/users/' . $this->user_id . '-' . $attr . '.' . $file->extension;
            $path = str_replace(' ', '-', $path);
            $file->saveAs($path);
            $this->{'doc_' . $attr} = '/' . $path;
        }
    }
}
