<div class="col-xs-12">
    <div class="block-text rel zmin">
        <?php /*<a title="" href="#">Hercules</a>
        <div class="mark">
            My rating:
            <span class="rating-input">
                <span data-value="0" class="glyphicon glyphicon-star"></span>
                <span data-value="1" class="glyphicon glyphicon-star"></span>
                <span data-value="2" class="glyphicon glyphicon-star"></span>
                <span data-value="3" class="glyphicon glyphicon-star"></span>
                <span data-value="4" class="glyphicon glyphicon-star-empty"></span>
                <span data-value="5" class="glyphicon glyphicon-star-empty"></span>
            </span>
        </div>*/ ?>
        <p><?= $feedback['comment']; ?></p>
        <ins class="zmin"></ins>
    </div>
    <div class="person-text rel">
        <?php /*<img src=""/>*/ ?>
        <span class="fio"><?= $feedback['fio']; ?></span>
        <?php /*= empty($feedback['date']) ? null : Html::tag('i', $feedback['date']); */ ?>
    </div>
</div>