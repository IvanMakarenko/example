<?php

// namespace api\controllers; directory api don`t work, it fork by symlink
namespace frontend\controllers\api;

use api\components\controllers\PrivateController;
use api\models\entities\Campain;
use common\models\BalanceLog;
use common\models\User;
use Yii;
use yii\web\HttpException;

class CampainController extends PrivateController
{
    const SUPPORTED_FILES = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
    ];


    public $modelClass  = Campain::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] = [
            'actions' => ['index', 'view', 'update', 'create', 'file', 'chart'],
            'allow' => true,
            'roles' => [User::ROLE_ADMIN, User::ROLE_ADVERTISER],
        ];
        $behaviors['access']['rules'][] = [
            'actions' => ['file'],
            'allow' => true,
        ];

        return $behaviors;
    }

    public function actionFile()
    {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
            throw new HttpException(500, 'Invalid parameters.');
        }

        // Check $_FILES['file']['error'] value.
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new HttpException(500, 'No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new HttpException(500, 'Exceeded filesize limit.');
            default:
                throw new HttpException(500, 'Unknown errors.');
        }

        // You should also check filesize here.
        if ($_FILES['file']['size'] > 1000000) {
            throw new HttpException(500, 'Exceeded filesize limit.');
        }

        // DO NOT TRUST $_FILES['file']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search($finfo->file($_FILES['file']['tmp_name']), self::SUPPORTED_FILES, true)) {
            throw new HttpException(500, 'Invalid file format.');
        }

        $dir = Yii::getAlias('@frontend/web/uploads/' . Yii::$app->user->id . '/');
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $filename = 'uploads/' . Yii::$app->user->id . '/' . sha1_file($_FILES['file']['tmp_name']) . '.' . $ext;
        // You should name it uniquely.
        // DO NOT USE $_FILES['file']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        if (!move_uploaded_file($_FILES['file']['tmp_name'], Yii::getAlias('@frontend/web/' . $filename))) {
            throw new HttpException(500, 'Failed to move uploaded file.');
        }

        return $filename;
    }

    public function actionChart()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        $start = new \DateTime($requestParams['start']);

        return [
            BalanceLog::find()->andMy()->andWhere(['like', 'created_at', $start->format('Y-m-d')])->sum('amount'),
            BalanceLog::find()->andMy()->andWhere(['like', 'created_at', $start->add(new \DateInterval('P1D'))->format('Y-m-d')])->sum('amount'),
            BalanceLog::find()->andMy()->andWhere(['like', 'created_at', $start->add(new \DateInterval('P1D'))->format('Y-m-d')])->sum('amount'),
            BalanceLog::find()->andMy()->andWhere(['like', 'created_at', $start->add(new \DateInterval('P1D'))->format('Y-m-d')])->sum('amount'),
            BalanceLog::find()->andMy()->andWhere(['like', 'created_at', $start->add(new \DateInterval('P1D'))->format('Y-m-d')])->sum('amount'),
            BalanceLog::find()->andMy()->andWhere(['like', 'created_at', $start->add(new \DateInterval('P1D'))->format('Y-m-d')])->sum('amount'),
            BalanceLog::find()->andMy()->andWhere(['like', 'created_at', $start->add(new \DateInterval('P1D'))->format('Y-m-d')])->sum('amount'),
        ];
    }
}