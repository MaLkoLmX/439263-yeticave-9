<nav class="nav">
  <ul class="nav__list container">
    <?php foreach ($categories as $key => $item):?>
  <li class="nav__item">
    <a href="all-lots.html"><?=$item["name"];?></a>
  </li>
  <?php endforeach;?>
  </ul>
</nav>
<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php foreach ($rate as $key => $item):?>
    <tr class="rates__item">
      <td class="rates__info">
        <div class="rates__img">
          <img src="<?=$item["image"];?>" width="54" height="40" alt="<?=$item["name"];?>">
        </div>
        <h3 class="rates__title"><a href="lot.html"><?=$item["name"];?></a></h3>
      </td>
      <td class="rates__category">
        <?=$item["category"];?>
      </td>
      <td class="rates__timer">
        <div class="timer timer--finishing"><?=$item["date_finish"];?></div>
      </td>
      <td class="rates__price">
        <?=get_price($item["amount"]);?>
      </td>
      <td class="rates__time">
        <?=$item["date_create"];?>
      </td>
      <?php endforeach; ?>
    </tr>
  </table>
</section>
