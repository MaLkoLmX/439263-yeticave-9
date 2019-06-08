<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $item): ?>
            <li class="promo__item">
                <a class="promo__link"
                   href="all-lots.php?cat=<?= $item["id"]; ?>&name=<?= $item["name"]; ?>"><?= esc($item["name"]); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <?php foreach ($lots

    as $key => $item): ?>
    <h2><?= esc($item["title"]); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $item["image"]; ?>" width="730" height="548" alt="<?= $item["categories"]; ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= esc($item["categories"]); ?></span></p>
            <p class="lot-item__description"><?= esc($item["description"]); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($_SESSION["user"])): ?>
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?= (get_time($item["date_finish"])) <= "01:00" ? "timer--finishing" : ""; ?>">
                    <?= esc(get_time($item["date_finish"])); ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= esc(get_price($item["price"])); ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= esc($item["price"] + $item["step_price"]); ?> р</span>
                    </div>
                </div>
                <form class="lot-item__form" action="" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?= isset($errors) ? "form__item--invalid" : ""; ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="rate"
                               placeholder="<?= $lots["price"] + $lots["step_price"]; ?>">
                        <span class="form__error"><?= isset($errors) ? esc($errors["rate"]) : ""; ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <div class="history">
                <h3>История ставок (<span><?= $count_rate; ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($rate as $key => $item): ?>
                        <tr class="history__item">
                            <td class="history__name"><?= esc($item["user_name"]); ?></td>
                            <td class="history__price"><?= esc(get_price($item["amount"])); ?></td>
                            <td class="history__time"><?= esc(date_bets($item["date_rate"])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
