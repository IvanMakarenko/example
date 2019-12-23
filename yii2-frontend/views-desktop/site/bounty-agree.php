<?php

use app\core\helpers\FileHelper;

$this->title = Yii::t('app', '{0} | MamboCoin', Yii::t('app', 'Bounty agree'));
?>
<div class="embed-responsive embed-responsive-16by9">
    <iframe id="user-agreement-frame"
            src="https://docs.google.com/gview?embedded=true&amp;url=<?= FileHelper::getBountyAgree(); ?>"
            width="100%"
            height="100%"
            frameborder="0"
    ></iframe>
</div>