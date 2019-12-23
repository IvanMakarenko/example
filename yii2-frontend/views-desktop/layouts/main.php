<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);

$this->registerJs('var course = ' . \app\core\helpers\EthHelper::MBC_COURSE_ETH . ';',  View::POS_HEAD);
$this->registerJs('var startPrivateSale = new Date("' . Yii::$app->params['PrivateSale']['start'] . '");',  View::POS_HEAD);
$this->registerJs('var endPrivateSale = new Date("' . Yii::$app->params['PrivateSale']['end'] . '");',  View::POS_HEAD);
$this->registerJs('var startPreICO = new Date("' . Yii::$app->params['PreICO']['start'] . '");',  View::POS_HEAD);
$this->registerJs('var endPreICO = new Date("' . Yii::$app->params['PreICO']['end'] . '");',  View::POS_HEAD);
$this->registerJs('var startICO = new Date("' . Yii::$app->params['ICO']['start'] . '");',  View::POS_HEAD);
$this->registerJs('var endICO = new Date("' . Yii::$app->params['ICO']['end'] . '");',  View::POS_HEAD);
$this->registerJs('var showModal = "#' . Yii::$app->request->get('modal') . '";',  View::POS_HEAD);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head>
    <meta charset="<?= Yii::$app->charset; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="1c8c1c0f4c00207f" />
    <?php $this->registerCsrfMetaTags(); ?>
    <title><?= Html::encode($this->title); ?></title>
    <meta name="description" content="<?=
        Yii::t('app', 'Buy and pay for purchases with {0} MamboCoin all over the world.', '')
        . '. '
        . Yii::t('app', 'Mambo24 - is one of top ten largest marketplaces in Russia by quantity of goods and services.'); ?>">
    <?= Html::csrfMetaTags(); ?>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

<div class="wrap">
    <?= $this->render('_header'); ?>

    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]); ?>

    <div class="alert-fixed">
        <div class="container">
            <?= Alert::widget(); ?>
        </div>
    </div>

    <?= $content; ?>
</div>

<?= $this->render('_footer'); ?>
<?= $this->render('_fixed-telegram'); ?>
<?= $this->render('_buy'); ?>
<?= $this->render('_get'); ?>

<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter50046784 = new Ya.Metrika2({ id:50046784, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/50046784" style="position:absolute; left:-9999px;" alt="ya" /></div></noscript> <!-- /Yandex.Metrika counter -->

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
