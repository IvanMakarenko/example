<div class="col-xs-6 text-center">
    <div class="ratio img-responsive img-circle greyscale"
         style="background-image: url(<?= $user->photo ? $user->photo . '?date=20180917' : '/images/users/default.svg'; ?>)">
    </div>
    <?= \app\widgets\SocialLinks::widget([
        'socials' => [
            'vk' => $user->vk_link,
            'facebook' => $user->facebook_link,
            'linkedin' => $user->linkedin_link,
        ],
    ]); ?>
</div>

<p class="fio">
    <?= Yii::t('team', $user->profile->first_name); ?>
    <?= Yii::t('team', $user->profile->last_name); ?>
</p>
<?= \app\widgets\ReadMore::widget([
    'label' => Yii::t('app', 'Read more'),
    'content' => Yii::t('team', $user->about),
    'options' => [
        'tag' => 'p',
        'class' => 'about',
    ],
]); ?>