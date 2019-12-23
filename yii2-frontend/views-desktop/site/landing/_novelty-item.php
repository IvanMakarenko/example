<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;

?>
<div class="col-xs-12" data-toggle="modal" data-target="#novelty-modal" data-id="<?= $novelty->id; ?>">
    <div class="novelty-img" style="background: url(<?= $novelty->img; ?>) no-repeat center center;"></div>
    <?= Html::tag('p', $novelty->title, ['class' => 'title']); ?>
    <?= Html::tag('p', StringHelper::truncate(strip_tags($novelty->content), 110), ['class' => 'content']); ?>

    <div class="comments-count pull-left">
        <?= Html::img('/images/news/comment.svg'); ?>
        <?= Yii::t('app', '{0, plural, =0{no comments} =1{# comment} other{# comments}}', $novelty->comments_count); ?>
    </div>
    <div class="date pull-right">
        <?= Yii::$app->formatter->asDate($novelty->date, 'long'); ?>
    </div>
</div>