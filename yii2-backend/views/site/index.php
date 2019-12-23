<?php

/* @var $this yii\web\View */
/* @var $map \dosamigos\google\maps\Map */

$this->title = 'DAV - Digital Ads on Vehicles';

$this->registerJs(' 
setInterval(function(){  
     $.pjax.reload({container: "#p0"});
}, 60000);', \yii\web\VIEW::POS_HEAD);
?>
<div class="site-index">
    <div class="body-content">
        <?php \yii\widgets\Pjax::begin(['timeout' => 650]); ?>
        <?= $map->display(); ?>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
