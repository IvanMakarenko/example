<?php

use yii\helpers\Html;
use app\core\entities\User;

?>
<section id="team" class="team">
    <div class="container">
        <h2><?= Yii::t('app', 'Team'); ?></h2>
        <div class="row">
            <?php foreach (User::find()->where(['is_worker' => 1])->limit(4)->all() as $user): ?>
                <?= $this->render('_team-worker', compact('user')); ?>
            <?php endforeach; ?>
        </div>
        <div class="text-center">
            <?= Html::a(Yii::t('app', 'More'), null, [
                'class' => 'btn btn-chartreus',
                'data-toggle' => 'collapse',
                'data-target' => '#full-team',
                'onClick' => '$(this).addClass("hidden");',
            ]); ?>
        </div>
        <div id="full-team" class="collapse">
            <div class="row">
                <?php foreach (User::find()->where(['is_worker' => 1])->offset(4)->all() as $index => $user): ?>
                    <?= ($index != 0 && $index % 4 == 0)
                        ? '</div><div class="row">'
                        : null; ?>

                    <?= $this->render('_team-worker', compact('user')); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>