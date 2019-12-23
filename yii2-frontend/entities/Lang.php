<?php

namespace app\core\entities;

use app\core\helpers\LangHelper;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "langs".
 *
 * @property int $id
 * @property string $local
 * @property string $name
 * @property int $default
 */
class Lang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'langs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['local', 'name'], 'required'],
            [['default'], 'integer'],
            [['local', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'local' => 'Local',
            'name' => 'Name',
            'default' => 'Default',
        ];
    }

    public function getLabel()
    {
        list($lang, $country) = explode('-', $this->local);
        return $lang;
    }

    public function getUrl()
    {
        return $this->isCurrent ? null : Url::to(['/', 'lang' => $this->local]);
    }

    public function getIconPath()
    {
        return '/images/lang/' . $this->label . '.svg';
    }

    public function getIsCurrent()
    {
        return LangHelper::getCurrent() == $this;
    }
}
