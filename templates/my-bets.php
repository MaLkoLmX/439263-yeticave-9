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
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($rate

        as $key => $item): ?>
        <tr class="rates__item <?= (strtotime($item["date_finish"]) < strtotime(date("Y-m-d H:i:s"))) ? "rates__item--end" : "" ?>">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?= $item["image"]; ?>" width="54" height="40" alt="<?= $item["name"]; ?>">
                </div>
                <h3 class="rates__title"><a href="lot.php?id=<?= $item["id_lot"]; ?>"><?= esc($item["name"]); ?></a>
                </h3>
            </td>
            <td class="rates__category">
                <?= esc($item["category"]); ?>
            </td>
            <td class="rates__timer">
                <?php if (strtotime($item["date_finish"]) > time()) : ?>
                    <div class="timer <?= (strtotime($item["date_finish"]) - strtotime("now") <= 3600 && strtotime($item["date_finish"]) - strtotime("now") > 0) ? "timer--finishing" : "" ?>">
                        <?= esc(get_time($item["date_finish"])); ?>
                    </div>
                <?php elseif ($item["winner"] === $user_id) : ?>
                    <div class="timer timer--win">Ставка выиграла</div>
                <?php else : ?>
                    <div class="timer timer--end">Торги окончены</div>
                <?php endif; ?>
            </td>
            <td class="rates__price">
                <?= esc(get_price($item["amount"])); ?>
            </td>
            <td class="rates__time">
                <?= esc(date_bets($item["date_create"])); ?>
            </td>
            <?php endforeach; ?>
        </tr>
    </table>
</section>
