<?php

use app\widgets\TimeLine;

?>
<section id="roadmap" class="roadmap">
    <div class="container">
        <h2><?= Yii::t('app', 'Roadmap'); ?></h2>

        <?= TimeLine::widget([
            'options' => [
                'id' => 'timeline',
                'class' => 'roadmap-bg',
            ],
            'items' => [
                '2016-11' => Yii::t('app', 'Mambo24 project launch in Azerbaijan'),
                '2017-05' => Yii::t('app', 'beta version launch in the Russian Federation'),
                '2017-08' => Yii::t('app', '500th Integrated Partner'),
                '2018-06' => Yii::t('app', '10 000th order'),
                '2018-08' => Yii::t('app', 'preparation for Pre-ICO'),
                '2018-10' => Yii::t('app', 'start of Pre-ICO'),
                '2019-03' => Yii::t('app', 'ICO launch'),
                '2019-10' => Yii::t('app', 'Mambo24 project launch in Turkey'),
                '2019-12' => Yii::t('app', 'Mambo24 project launch in India'),
                '2020-04' => Yii::t('app', 'Mambo24 project launch in Indonesia'),
                '2020-12' => Yii::t('app', 'sales growth in all countries from $ 20 million'),
                '2025-12' => Yii::t('app', 'annual turnover is over $ 100 million'),
            ],
        ]); ?>
    </div>
</section>