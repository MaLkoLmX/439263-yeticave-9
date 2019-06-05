<?php foreach ($rate as $key => $item):?>
<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?=$item["name"];?></p>
<p>Ваша ставка для лота <a href="http://439263-yeticave-9:8080/lot.php?id=<?=$lots_id?>"><?=$lots_name;?></a> победила.</p>
<p>Перейдите по ссылке <a href="http://439263-yeticave-9:8080/my-bets.php">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
<?php endforeach;?>
