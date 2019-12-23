<?php

use app\widgets\Accordion;
use yii\helpers\Html;

?>
<section class="site-index">
    <div class="container">
        <div class="row cabinet">
            <div class="col-sm-3 personal">
                <?= $this->render('_personal', compact('user')); ?>
                <?= Html::beginForm(['/logout'], 'post'); ?>
                <?= Html::submitButton('<i class="glyphicon glyphicon-log-out"></i> ' . Yii::t('app', 'Log out'), [
                    'class' => 'btn btn-link logout color-chartreuse',
                ]); ?>
                <?= Html::endForm(); ?>
            </div>
            <div class="col-sm-9">
                <?= Accordion::widget([
                    'options' => [
                        'class' => 'cabinet-accordion',
                    ],
                    'items' => [
                        [
                            'title' => Html::a(
                                Yii::t('personal', 'Personal information')
                                    . Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus pull-right'])
                            , '/cabinet'),
                            'content' => ($this->context->id == 'cabinet' && $this->context->action->id == 'index') ? $content : null,
                            'active' => ($this->context->id == 'cabinet' && $this->context->action->id == 'index') ? true : false,
                        ],
                        [
                            'title' => Html::a(
                                Yii::t('personal', 'Verification')
                                    . Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus pull-right'])
                            , '/cabinet/verification'),
                            'content' => $this->context->action->id == 'verification' ? $content : null,
                            'active' => $this->context->action->id == 'verification' ? true : false,
                        ],
                        [
                            'title' => Html::a(
                                Yii::t('personal', 'My tokens')
                                    . Html::tag('span', Yii::t('app', 'Buy tokens'), ['class' => 'btn btn-buy btn-xs pull-right'])
                            , '/cabinet/tokens'),
                            'content' => $this->context->action->id == 'tokens' ? $content : null,
                            'active' => $this->context->action->id == 'tokens' ? true : false,
                        ],
                        [
                            'title' => Html::a(
                                Yii::t('personal', 'My operations')
                                    . Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus pull-right'])
                            , '/cabinet/operations'),
                            'content' => $this->context->action->id == 'operations' ? $content : null,
                            'active' => $this->context->action->id == 'operations' ? true : false,
                        ],
                        [
                            'title' => Html::a(
                                Yii::t('personal', 'Bounty')
                                    . Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus pull-right'])
                            , '/cabinet/bounty'),
                            'content' => $this->context->id == 'bounty' ? $content : null,
                            'active' => $this->context->id == 'bounty' ? true : false,
                            'hidden' => Yii::$app->user->can('bountyRequest') && $this->context->id != 'bounty',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</section>
