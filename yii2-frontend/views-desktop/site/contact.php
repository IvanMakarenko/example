<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\core\forms\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Contact us');
?>
<section id="contact" class="contact">
    <div class="container">
        <h1><?= Html::encode($this->title); ?></h1>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
            <div class="alert alert-success">
                <?= Yii::t('contact', 'Thank you for contacting us. We will respond to you as soon as possible.'); ?>
            </div>
        <?php endif; ?>

        <div class="contact-content clearfix">
            <div class="col-xs-12 col-sm-5 contact-content-info">
                <h2><?= Yii::t('contact', 'Contact Information'); ?></h2>
                <p>
                    <?= Yii::t('contact', 'Marketing contacts:'); ?>
                    <ul>
                        <li>
                            <?= Html::mailto(Yii::$app->params['marketingEmail'], Yii::$app->params['marketingEmail'], [
                                'class' => 'color-chartreuse',
                                'target' => '_blank',
                            ]); ?>
                        </li>
                        <li>
                            <a class="color-chartreuse" href="https://t.me/Karina_Uysal" target="_blank">
                                https://t.me/Karina_Uysal
                            </a>
                        </li>
                    </ul>
                    <br>
                    <?= Yii::t('contact', 'Contacts for business inquiries:'); ?>
                    <ul>
                        <li>
                            <?= Html::mailto(Yii::$app->params['ownerEmail'], Yii::$app->params['ownerEmail'], [
                                'class' => 'color-chartreuse',
                                'target' => '_blank',
                            ]); ?>
                        </li>
                        <li>
                            <a class="color-chartreuse" href="https://t.me/eaxale" target="_blank">
                                https://t.me/eaxale
                            </a>
                        </li>
                    </ul>
                    <br>
                    <?= Yii::t('contact', 'For other questions:'); ?>
                    <ul>
                        <li>
                            <?= Html::mailto(Yii::$app->params['supportEmail'], Yii::$app->params['supportEmail'], [
                                'class' => 'color-chartreuse',
                                'target' => '_blank',
                            ]); ?>
                        </li>
                    </ul>
                    <br>
                    <?= Yii::t('contact', 'Or just fill out the following form to contact us.'); ?>
                    <?= Yii::t('contact', 'Thank you.'); ?>
                </p>
            </div>
            <div class="col-xs-12 col-sm-7 contact-content-form">
                <h2><?= Yii::t('contact', 'Send Us A Message'); ?></h2>
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'name'); ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'email'); ?>
                    </div>
                    <div class="col-xs-12 col-sm-10">
                        <?= $form->field($model, 'subject'); ?>
                    </div>
                </div>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]); ?>


                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-5">{image}</div><div class="col-lg-7">{input}</div></div>',
                ])->label(false); ?>

                <div class="form-group text-center">
                    <?= Html::submitButton(Yii::t('contact', 'Submit'), ['class' => 'btn btn-buy', 'name' => 'contact-button']); ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>
