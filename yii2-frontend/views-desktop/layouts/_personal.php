<div class="personal-photo">
    <div class="ratio img-responsive img-circle greyscale"
         style="background-image: url(<?= $user->photo ? $user->photo : '/images/users/default.svg'; ?>)">
    </div>
</div>

<div class="personal-registered">
    <label><?= Yii::t('personal', 'Date of registration'); ?></label>
    <div class="value"><?= Yii::$app->formatter->asDate($user->created_at, 'dd.MM.yyyy'); ?></div>
</div>

<div class="personal-balance">
    <label><?= Yii::t('personal', 'Balance'); ?></label>
    <div class="value color-chartreuse"><?= Yii::$app->formatter->asDecimal($user->tokens); ?> mbc</div>
</div>