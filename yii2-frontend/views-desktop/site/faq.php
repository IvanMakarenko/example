<?php

use app\widgets\Accordion;
use yii\helpers\Html;

$this->title = Yii::t('app', '{0} | MamboCoin', Yii::t('app', 'FAQ'));

?>
<section id="faq" class="faq">
    <div class="container">
        <h1 class="text-uppercase"><?= Yii::t('app', 'FAQ'); ?></h1>
        <?= Accordion::widget([
            'index' => 1,
            'options' => [
                'class' => 'faq-accordion',
            ],
            'titleTemplate' => '{index}{title}' . Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus pull-right']),
            'items' => [
                [
                    'title' => Yii::t('faq', 'How can I register on the website?'),
                    'content' => Yii::t('faq', 'Fill out the registration form with your full name, nickname and email address. Verify your email address so we can inform you about the most important news of the project'),
                ],
                [
                    'title' => Yii::t('faq', 'How can I buy tokens?'),
                    'content' => Yii::t('faq', 'You can buy tokens using the website or your profile by choosing the cryptocurrency to purchase. Tokens will be immediately credited to your personal account. You can see the amount of tokens on your profile in "My tokens" or "My transactions" sections.'),
                ],
                [
                    'title' => Yii::t('faq', 'Can I buy tokens for fiat money?'),
                    'content' => Yii::t('faq', 'No, you can buy tokens is only with cryptocurrency'),
                ],
                [
                    'title' => Yii::t('faq', 'Where can I keep tokens I have bought?'),
                    'content' => Yii::t('faq', 'All purchased tokens are kept on the decentralized Ethereum blockchain platform. You can check the amount of tokens purchased within Pre-ICO or ICO on your profile in "My tokens" or "My transactions" sections. Also, they can be displayed on your Ethereum wallet, depending what kind of wallet you have, in some of them they can be added automatically, in other ones you must add them manually.'),
                ],
                [
                    'title' => Yii::t('faq', 'Can I sell tokens in Pre-ICO or ICO stages?'),
                    'content' => Yii::t('faq', 'No, it’s impossible. You can take any actions with tokens only after ICO.'),
                ],
                [
                    'title' => Yii::t('faq', 'When can I exchange my tokens to cryptocurrency?'),
                    'content' => Yii::t('faq', 'If the goal of ICO is achieved at the time of completion, all tokens owners will automatically receive the corresponding amount of cryptocurrency, this logic has been implemented into a smart contract and it guarantees its execution.'),
                ],
                [
                    'title' => Yii::t('faq', 'One token = one MamboCoin?'),
                    'content' => Yii::t('faq', 'Yes, one token is equal to one MamboCoin'),
                ],
                [
                    'title' => Yii::t('faq', 'What are my guarantees, if the project do not collect the required amount on ICO?'),
                    'content' => Yii::t('faq', 'You are insured by a smart contract that will be executed on dd-mm-yyy and if the amount X is not collected, then all tokens holders will be able to exchange it to Ethereum cryptocurrency, but if the goal is achieved they will be automatically converted into coins.'),
                ],
                [
                    'title' => Yii::t('faq', 'How fast my tokens can be transferred into coins?'),
                    'content' => Yii::t('faq', 'Usually tokens to coins exchange is carried out within 3-4 months. Our technical team of specialists will try to do everything to speed up and spiff up the process'),
                ],
                [
                    'title' => Yii::t('faq', 'How can I get tokens for free?'),
                    'content' => Yii::t('faq', 'You can take part in Bounty and get tokens in exchange for certain actions (posts and reposts about project activities, write own copyright materials or create a useful content for the project. You can learn more about terms and conditions of Bounty by filling out the {form}', [
                        'form' => Html::a(Yii::t('faq', 'form'), ['/cabinet/bounty'], [
                            'class' => 'color-chartreuse',
                            'data-toggle' => Yii::$app->user->isGuest ? 'modal' : null,
                            'data-target' => Yii::$app->user->isGuest ? '#modal-get' : null,
                        ]),
                    ]),
                ],
                [
                    'title' => Yii::t('faq', 'When can I get the first profit from investment?'),
                    'content' => Yii::t('faq', 'Monthly profit from tokens purchase will be increased. This is guaranteed by several conditions:')
                        . Html::ul([
                            Yii::t('faq', 'increase in sales of online megamall Mambo24 due to intensive advertising campaign, expansion of the range of goods, business scaling. Users of megamall have already used mbc for goods and services payments, which means that the cost of tokens will be increased in proportion to the cost of coins and their demand in the market.'),
                            Yii::t('faq', 'the cost of tokens will be increased constantly due to the rules of exchanges. The cost of tokens on Pre-ICO will be significantly lower than on ICO stage. Thus, you can purchase more tokens and as a result get more coins.'),
                            Yii::t('faq', 'there is also an opportunity to buy out mbc cryptocurrency from holders. That will increase the profit from cryptocurrency sale'),
                        ]),
                ],
            ],
        ]); ?>
    </div>
</section>