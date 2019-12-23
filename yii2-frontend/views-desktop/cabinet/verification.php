<?php

use app\core\entities\Country;
use app\core\helpers\ClientHelper;
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\Html;


$this->title = Yii::t('personal', 'Verification');
$this->beginContent('@app/views/layouts/cabinet.php', compact('user'));

?>

<div class="not-verification clearfix">
    <div class="not-verification-info text-center">
        <div>
            <big><?= $profile->statusLabel; ?></big>
        </div>
        <br>
        <small class="not-verification-info-des"><?= $profile->statusDescription; ?></small>
    </div>
</div>

<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

    <h3><?= Yii::t('personal', 'Personal Information'); ?></h3>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'first_name')
                ->textInput(['placeholder' => Yii::t('personal', 'First Name')]); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'last_name')
                ->textInput(['placeholder' => Yii::t('personal', 'Last Name')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'middle_name')
                ->textInput(['placeholder' => Yii::t('personal', 'Middle Name')]); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'birthday_at')
                ->widget(DatePicker::class, [
                    'removeButton' => false,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'startView' => 'years',
                        'maxViewMode' => 'years',
                    ],
                    'options' => [
                        'class' => 'form-control right',
                        'placeholder' => 'YYYY-MM-DD',
                    ],
                ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'phone')
                ->input('tel', ['placeholder' => '(123) 1234-5678']); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'employment')
                ->textInput(['placeholder' => Yii::t('personal', 'Employment or occupation')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'income')
                ->textInput(['placeholder' => 10000]); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'contribute')
                ->textInput(['placeholder' => 500]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($profile, 'eth_address')
                ->textInput(['placeholder' => '0x0000000000000000000000000000000000000000'])
                ->hint(Yii::t('personal', '(You need to enter the ETH address you wish to receive MBC`s to. For ETH payments: MBC receiving address and payment address should be the SAME. Don`t use exchange address otherwise you will lose your found.)')); ?>
        </div>
    </div>

    <h3><?= Yii::t('personal', 'Address'); ?></h3>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'country')
                ->dropDownList(Country::find()->select('name')->indexBy('code')->column(), [
                    'prompt' => Yii::t('app', 'Select'),
                ]); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'zip_code')
                ->textInput(['placeholder' => ClientHelper::getZip()]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'city')
                ->textInput(['placeholder' => ClientHelper::getCity()]); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'state')
                ->textInput(['placeholder' => ClientHelper::getState()]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'street')
                ->textInput(['placeholder' => Yii::t('personal', 'Street')]); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'appartment')
                ->textInput(['placeholder' => Yii::t('personal', 'Apartment/Suite')]); ?>
        </div>
    </div>

    <h3><?= Yii::t('personal', 'Upload Documents'); ?></h3>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'document_type')
                ->dropDownList($profile->getDocumentTypeList(), ['prompt' => Yii::t('app', 'Select')]); ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($profile, 'issuing_country')
                ->dropDownList(Country::find()->select('name')->indexBy('code')->column(), [
                    'prompt' => Yii::t('app', 'Select'),
                ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8">
            <?= $form->field($profile, 'font_file', [
                'template' => '<label class="control-label required">{label}</label>'
                    . '<div class="input-group">'
                    . '<input type="text" class="form-control left" disabled value="' . $profile->doc_font . '">'
                    . '<div class="input-group-btn">'
                    . '<label class="btn btn-buy">'
                    . '{input}'
                    . Yii::t('personal', 'Upload File')
                    . '</label>'
                    . '</div>'
                    . '</div>',
            ])->fileInput(['class' => 'hidden']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8">
            <?= $form->field($profile, 'back_file', [
                'template' => '<label class="control-label required">{label}</label>'
                    . '<div class="input-group">'
                    . '<input type="text" class="form-control left" disabled value="' . $profile->doc_back . '">'
                    . '<div class="input-group-btn">'
                    . '<label class="btn btn-buy">'
                    . '{input}'
                    . Yii::t('personal', 'Upload File')
                    . '</label>'
                    . '</div>'
                    . '</div>',
            ])->fileInput(['class' => 'hidden']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8">
            <?= $form->field($profile, 'identity_file', [
                'template' => '<label class="control-label required">{label}</label>'
                    . '<div class="input-group">'
                    . '<input type="text" class="form-control left" disabled value="' . $profile->doc_identity . '">'
                    . '<div class="input-group-btn">'
                    . '<label class="btn btn-buy">'
                    . '{input}'
                    . Yii::t('personal', 'Upload File')
                    . '</label>'
                    . '</div>'
                    . '</div>',
            ])->fileInput(['class' => 'hidden']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8">
            <?= $form->field($profile, 'proof_file', [
                'template' => '<label class="control-label required">{label}</label>'
                    . '<div class="input-group">'
                    . '<input type="text" class="form-control left" disabled value="' . $profile->doc_proof . '">'
                    . '<div class="input-group-btn">'
                    . '<label class="btn btn-buy">'
                    . '{input}'
                    . Yii::t('personal', 'Upload File')
                    . '</label>'
                    . '</div>'
                    . '</div>',
            ])->fileInput(['class' => 'hidden']); ?>
        </div>
    </div>

    <ul>
        <li><?= Yii::t('personal', 'Acceptable file format: JPEG, JPG, PNG;'); ?></li>
        <li><?= Yii::t('personal', 'Not acceptable: photos of a screen, photocopies, etc;'); ?></li>
        <li><?= Yii::t('personal', 'All sides of the document should bt seen in the photo;'); ?></li>
        <li><?= Yii::t('personal', 'The document should take over 50% or more of the frame;'); ?></li>
        <li><?= Yii::t('personal', 'The picture should be clear, without blur and glare;'); ?></li>
    </ul>

    <div class="form-group form-group-submit">
        <?= Html::submitButton(Yii::t('app', 'Save'), [
            'class' => 'btn btn-buy',
            'name' => 'verify-button',
        ]); ?>
        <?= Html::a(Yii::t('app', 'Next'), '/cabinet/tokens', [
            'class' => 'btn btn-get' . ($profile->isVerify ? null : ' disabled'),
        ]); ?>
    </div>

<?php ActiveForm::end(); ?>

<?php $this->endContent(); ?>