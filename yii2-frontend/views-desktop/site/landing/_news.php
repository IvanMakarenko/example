<?php

use app\core\entities\Novelty;
use evgeniyrru\yii2slick\Slick;
use yii\bootstrap\Modal;

$items = [];
foreach (Novelty::find()->forCurrentLang()->byDate()->all() as $novelty) {
    $items[] = $this->render('_novelty-item', compact('novelty'));
}
if ($items): ?>
    <section id="news" class="news">
        <div class="container">
            <h2><?= Yii::t('app', 'News'); ?></h2>
            <div class="row novelty-list slider-controlls-top">
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

    <?php
    Modal::begin([
        'id' => 'novelty-modal',
        'header' => '<h3 class="modal-title color-chartreuse"></h3>',
        'size' => Modal::SIZE_LARGE,
    ]);
    Modal::end(); ?>

<?php endif; ?>