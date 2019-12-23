<?php

use yii\bootstrap\Modal;


Modal::begin([
    'id' => 'modal-buy',
    'header' => null,
    'closeButton' => false,
    'options' => [
        'class' => 'double-login',
        'aria-labelledby' => 'gridModalLabel',
    ],
]); ?>

<div class="row auto-height">
    <div class="col-md-6 buy">
        <?= $this->render('_signup'); ?>
    </div>

    <div class="col-md-6 login">
        <?= $this->render('_login'); ?>
    </div>
</div>

<?php
Modal::end();
