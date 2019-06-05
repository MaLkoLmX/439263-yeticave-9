<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $item): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= $item["name"]; ?></a>
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
                <h3 class="rates__title"><a href="lot.php?id=<?= $item["id_lot"]; ?>"><?= $item["name"]; ?></a></h3>
            </td>
            <td class="rates__category">
                <?= $item["category"]; ?>
            </td>
            <td class="rates__timer">
                <?php if (strtotime($item["date_finish"]) > time()) : ?>
                    <div class="timer <?= (strtotime($item["date_finish"]) - strtotime("now") <= 3600 && strtotime($item["date_finish"]) - strtotime("now") > 0) ? "timer--finishing" : "" ?>">
                        <?= get_time($item["date_finish"]); ?>
                    </div>
                <?php elseif ($item["winner"] === $user_id) : ?>
                    <div class="timer timer--win">Ставка выиграла</div>
                <?php else : ?>
                    <div class="timer timer--end">Торги окончены</div>
                <?php endif; ?>
            </td>
            <td class="rates__price">
                <?= get_price($item["amount"]); ?>
            </td>
            <td class="rates__time">
                <?= date_bets($item["date_create"]); ?>
            </td>
            <?php endforeach; ?>
        </tr>
    </table>
</section>
