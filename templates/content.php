<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $key => $item):?>
        <li class="promo__item promo__item--<?=$item["code"];?>">
            <a class="promo__link" href="pages/all-lots.html"><?=$item["name"]; ?></a>
        </li>
        <?php endforeach;?>
    </ul>
</section>

<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($products as $key => $item):?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=$item["image"];?>" width="350" height="260" alt="<?=$item["name"];?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=$item["categories"];?></span>
                <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$item["id_lot"];?>"><?=$item["name"];?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?= get_price($item["price"]);?></span>
                    </div>
                    <div class="lot-item__timer timer <?=(get_time($item["date_finish"])) <= "01:00" ? "timer--finishing" : "";?>">
                      <?=get_time($item["date_finish"]);?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
</section>
