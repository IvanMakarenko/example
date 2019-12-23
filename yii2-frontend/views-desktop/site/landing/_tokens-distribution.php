<?php

use dosamigos\chartjs\ChartJs;

?>
<section id="tokens-distribution" class="tokens-distribution">
    <div class="container">
        <h2><?= Yii::t('app', 'Tokens distribution'); ?></h2>
        <div class="row">
            <div class="col-xs-12 why-we-answer">
                <div class="chart-wrapper">
                    <?= ChartJs::widget([
                        'type' => 'doughnut',
                        'data' => [
                            'labels' => [
                                Yii::t('app', 'Sale - 75%'),
                                Yii::t('app', 'Reserve capital - 10%'),
                                Yii::t('app', 'Team - 7%'),
                                Yii::t('app', 'Consulting services - 5%'),
                                Yii::t('app', 'Bounty - 3%'),
                            ],
                            'datasets' => [
                                [
                                    'data' => [75, 10, 7, 5, 3],
                                    'backgroundColor' => [
                                        '#66AA00',
                                        '#3366CC',
                                        '#990099',
                                        '#FF9900',
                                        '#1ED4BD',
                                    ],
                                    'borderColor' => [
                                        '#F7F7F7',
                                        '#F7F7F7',
                                        '#F7F7F7',
                                        '#F7F7F7',
                                        '#F7F7F7',
                                    ],
                                    'borderWidth' => '15',
                                    'hoverBorderColor' => 'transparent'
                                ]
                            ]
                        ],
                        'clientOptions' => [
                            'rotation' => M_PI,
                            'cutoutPercentage' => 75,
                            'responsive' => true,
                            'maintainAspectRatio' => false,
                            'legend' => [
                                'display' => true,
                                'position' => 'left',
                                'fullWidth' => false,
                                'labels' => [
                                    'padding' => 40,
                                    'fontSize' => 18,
                                ],
                            ],
                            'tooltips' => [
                                'callbacks' => [
                                    'label' => new \yii\web\JsExpression('function(tooltipItem, data) { return data.labels[tooltipItem.index]; }'),
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>