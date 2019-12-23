<?php

namespace app\core\entities;

use app\core\entities\queries\NoveltyQuery;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "novelties".
 *
 * @property int $id
 * @property string $img
 * @property string $title
 * @property string $content
 * @property int $comments_count
 * @property string $date
 * @property string $lang
 */
class Novelty extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    public $upload_image;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'novelties';
    }

    /**
     * @return NoveltyQuery
     */
    public static function find()
    {
        return new NoveltyQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            'required' => [['title', 'content', 'date', 'lang'], 'required'],
            'required_create' => [['upload_image'], 'required', 'on' => self::SCENARIO_CREATE],
            'required_default' => [['img'], 'required', 'on' => self::SCENARIO_DEFAULT],

            [['content'], 'string'],
            [['url'], 'url', 'skipOnEmpty' => true, 'defaultScheme' => ''],
            [['comments_count'], 'integer'],
            [['comments_count'], 'default', 'value' => 0],
            [['date'], 'date', 'format' => 'php:Y-m-d'],

            [['upload_image'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
            [['img', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load($data, $formName = null)
    {
        $isLoad = parent::load($data, $formName);
        $this->upload_image = UploadedFile::getInstance($this, 'upload_image');
        return $isLoad;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img' => 'Img',
            'title' => 'Title',
            'content' => 'Content',
            'comments_count' => 'Comments Count',
            'date' => 'Date',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        $this->upload();
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete()
    {
        $this->removeImage();
        return parent::beforeDelete();
    }

    protected function removeImage()
    {
        return unlink(Yii::getAlias('@webroot' . $this->img));
    }

    protected function upload()
    {
        if (!empty($this->upload_image)) {
            $filePath = 'images/news/' . $this->upload_image->baseName . '-' . time() . '.' . $this->upload_image->extension;
            $filePath = str_replace(' ', '-', $filePath);
            $this->upload_image->saveAs($filePath);
            if (!empty($this->img)) {
                $this->removeImage();
            }
            $this->img = '/' . $filePath;
            return true;
        }
        return false;
    }
}
