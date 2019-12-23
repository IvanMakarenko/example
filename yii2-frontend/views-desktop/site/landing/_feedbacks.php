<?php

use evgeniyrru\yii2slick\Slick;

$items = [];
foreach (Yii::$app->params['feedbacks'] as $feedback) {
    $items[] .= $this->render('_feedback-item', compact('feedback'));
} ?>
<section id="feedbacks" class="feedbacks">
    <div class="container">
        <h2><?= Yii::t('app', 'Reviews'); ?></h2>
        <div class="row slider-controlls-top">
            <?= Slick::widget([
                'items' => $items,
                // @see http://kenwheeler.github.io/slick/#settings
                'clientOptions' => [
                    'autoplay' => false,
                    'dots'     => false,
                    'slidesToShow' => 3,
                    'slidesToScroll' => 1,
                    'arrows' => true,
                    'draggable' => true,
                    'prevArrow' => '<a class="left carousel-control" href="#w4" data-slide="prev">‹</a>',
                    'nextArrow' => '<a class="right carousel-control" href="#w4" data-slide="next">›</a>',
                    'responsive' => [
                        [
                            'breakpoint' => 768,
                            'settings' => [
                                'slidesToShow' => 2,
                                'slidesToScroll' => 1,
                            ],
                        ],
                        [
                            'breakpoint' => 480,
                            'settings' => [
                                'slidesToShow' => 1,
                                'slidesToScroll' => 1,
                            ],
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</section>