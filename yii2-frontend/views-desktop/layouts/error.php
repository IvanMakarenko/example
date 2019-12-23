<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->registerLinkTag([
    'href' => 'https://fonts.googleapis.com/css?family=Elsie|Yrsa',
    'rel' => 'stylesheet',
]);
$this->registerCss('
body {
    background: #67a389;
    font-family: "Elsie", cursive;
}
.h1, .before-h1 {
    color: #f9f0d3;
}
.before-h1 {
    font-size: 120px;
    text-align: center;
    line-height: 0;
    margin: 100px 0 0;
    position: relative;
    z-index: 2;
}
.h1 {
    position: relative;
    font-size: 600px;
    line-height: 0;
    text-align: center;
    margin: 300px 0 200px;
    font-family: "Yrsa", serif;
}
.h1:after {
    content: "";
    position: absolute;
    background: url(/images/girl-404.png);
    width: 391px;
    height: 484px;
    left: calc(100% / 2 - 190px);
    top: -300px;
}
.h1 .hidden {
    color: transparent;
    margin: 0 20px;
}
.description {
    text-align: center;
    font-size: 40px;
}
');
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
    <title>404</title>
    <meta name="description" content="Looks like the page you are looking for is missing.">
    <?= Html::csrfMetaTags(); ?>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

<div class="container">
    <div class="site-error">

        <p class="before-h1">OOPS!</p>
        <h1 class="h1">4<span class="hidden">0</span>4</h1>

        <p class="description">Looks like the page you are looking for is missing.</p>
    </div>
</div>

<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter50046784 = new Ya.Metrika2({ id:50046784, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/50046784" style="position:absolute; left:-9999px;" alt="ya" /></div></noscript> <!-- /Yandex.Metrika counter -->

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>