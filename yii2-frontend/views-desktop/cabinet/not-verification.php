<?php

$this->title = Yii::t('personal', 'My tokens');
$this->beginContent('@app/views/layouts/cabinet.php', compact('user'));

?>

<div class="not-verification clearfix">
    <a href="/cabinet/verification">
        <div class="col-xs-6 col-sm-3 not-verification-icon">
            <img src="/images/resume.svg" alt="resume"/>
        </div>
    </a>
    <div class="col-xs-6 col-sm-9 not-verification-info">
        <div>
            <big><?= $user->profile->statusLabel; ?></big>
        </div>
        <br>
        <div>
            <a href="/cabinet/verification" class="btn btn-chartreus"><?= Yii::t('personal', 'Verify account'); ?></a>
            <small class="not-verification-info-des"><?= $user->profile->statusDescription; ?></small>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>