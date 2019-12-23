<?php

use yii\helpers\Html;

?>
<section id="why-blockchain" class="why-blockchain">
    <div class="container">
        <h2><?= Yii::t('app', 'Why blockchain?'); ?></h2>

        <div class="row">
            <div class="col-sm-2 text-center">
                <?= Html::img('/images/why-blockchain/graph.svg'); ?>
            </div>
            <div class="col-sm-10">
                <p class="title">
                    <?= Yii::t('app', 'Creation of a single cryptocurrency - MamboCoin'); ?>
                </p>
                <p class="description">
                    <?= Yii::t('app', 'Mambo24 project is planning to reach international markets in the next two years. Of course, the issue of payments unification arises, both for customers and for partners. MamboCoin -  a single cryptocurrency will be used in order to simplify all payment transactions. At the same time, the project does not exclude the use of fiat money. They will be converted automatically into mbc and the calculation on the platform will be carried out in the cryptocurrency. This will allow to increase the cost of mbc in a few years to 100 аnd more times.'); ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 text-center">
                <?= Html::img('/images/why-blockchain/placemark.svg'); ?>
            </div>
            <div class="col-sm-10">
                <p class="title">
                    <?= Yii::t('app', 'Decentralization of delivery service'); ?>
                </p>
                <p class="description">
                    <?= Yii::t('app', 'The use of blockchain technology will allow cargo tracking in real time. Each participant in p2p logistic system will have its own code that can track the cargo at the moment.'); ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 text-center">
                <?= Html::img('/images/why-blockchain/schema.svg'); ?>
            </div>
            <div class="col-sm-10">
                <p class="title">
                    <?= Yii::t('app', 'Creation of a single database'); ?>
                </p>
                <p class="description">
                    <?= Yii::t('app', 'Blockchain technology allows to create a single unified base of sellers and buyers in all countries of the project presence.'); ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 text-center">
                <?= Html::img('/images/why-blockchain/paper.svg'); ?>
            </div>
            <div class="col-sm-10">
                <p class="title">
                    <?= Yii::t('app', 'Global trade'); ?>
                </p>
                <p class="description">
                    <?= Yii::t('app', 'A smart contract allows you to make global trade transactions between buyers and sellers, in order that all requirements of the smart contract have been fulfilled. This will allow Mambo24 users to buy goods from other countries sellers without overpayments and commissions.Transactions are also anonymous, which is especially important for b2b sector.'); ?>
                </p>
            </div>
        </div>
    </div>
</section>