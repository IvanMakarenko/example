<?php

use app\core\entities\User;
use evgeniyrru\yii2slick\Slick;

$items = [];
foreach (User::find()->where(['>', 'is_adviser', 0])->orderBy('is_adviser')->all() as $user) {
    $items[] = $this->render('_adviser-item', compact('user'));
}

if ($items): ?>
<section id="advisers" class="advisers">
    <div class="container">
        <h2><?= Yii::t('app', 'Advisers'); ?></h2>

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
<?php endif; ?>