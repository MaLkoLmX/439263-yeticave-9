<nav class="nav">
  <ul class="nav__list container">
    <?php foreach ($categories as $key => $item):?>
    <li class="nav__item">
      <a href="all-lots.html"><?=$item["name"];?></a>
    </li>
    <?php endforeach;?>
  </ul>
</nav>
<div class="container">
  <section class="lots">
    <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
    <ul class="lots__list">
      <?php foreach ($lots as $key => $item):?>
      <li class="lots__item lot">
        <div class="lot__image">
          <img src="<?= $item["image"];?>" width="350" height="260" alt="<?= $item["name"];?>">
        </div>
        <div class="lot__info">
          <span class="lot__category"><?= $item["category"];?></span>
          <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$item["id_lot"];?>"><?= $item["name"];?></a></h3>
          <div class="lot__state">
            <div class="lot__rate">
              <span class="lot__amount">Стартовая цена</span>
              <span class="lot__cost"><?= $item["price"];?><b class="rub">р</b></span>
            </div>
            <div class="lot__timer timer <?=(get_time($item["date_finish"])) <= "01:00" ? "timer--finishing" : "";?>">
              <?=get_time($item["date_finish"]);?>
            </div>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php if ($pages > 1):?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href="/search.php?search=<?=$search; ?>&page=<?php if ($cur_page > 1) print($cur_page - 1);?>">Назад</a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif;?>">
                <a href="/search.php?search=<?=$search;?>&page=<?=$page;?>"><?=$page;?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a href="/search.php?search=<?=$search;?>&page=<?php if ($cur_page < $pages_count) print($cur_page + 1);?>">Вперед</a>
        </li>
    </ul>
    <?endif;?>
</div>
