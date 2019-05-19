<nav class="nav">
  <ul class="nav__list container">
    <?php foreach ($categories as $key => $item):?>
    <li class="nav__item">
      <a href="all-lots.html"><?=$item["name"];?></a>
    </li>
    <?php endforeach;?>
  </ul>
</nav>
<form class="form form--add-lot container <?=isset($errors) ? "form--invalid" : "";?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <div class="form__item <?=isset($errors["name"]) ? "form__item--invalid" : "";?>"> <!-- form__item--invalid -->
      <label for="name">Наименование <sup>*</sup></label>
      <input id="name" type="text" name="name" placeholder="Введите наименование лота" value="<?php if (isset($lots["name"])) echo $lots["name"]?>">
      <span class="form__error"><?=isset($errors["name"]) ? $errors["name"] : "";?></span>
    </div>
    <div class="form__item <?=isset($errors["category"]) ? "form__item--invalid" : "";?>">
      <label for="category">Категория <sup>*</sup></label>
      <select id="category" name="category">
        <option>Выберите категорию</option>
        <?php foreach ($categories as $key => $item): ?>
        <option value="<?=$item["id"];?>" <?php if ($lots["category"] == $item["id"]) print "selected"?>><?=$item["name"];?></option>
        <?php endforeach;?>
      </select>
      <span class="form__error"><?=isset($errors["category"]) ? $errors["category"] : "";?></span>
    </div>
  </div>
  <div class="form__item form__item--wide <?=isset($errors["description"]) ? "form__item--invalid" : "";?>">
    <label for="description">Описание <sup>*</sup></label>
    <textarea id="description" name="description" placeholder="Напишите описание лота"></textarea>
    <span class="form__error">Напишите описание лота</span>
  </div>
  <div class="form__item form__item--file <?=isset($errors["image"]) ? "form__item--invalid" : "";?>">
    <label>Изображение <sup>*</sup></label>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="lot-img" name="image" value="">
      <label for="lot-img">
        Добавить
      </label>
    </div>
  </div>
  <div class="form__container-three">
    <div class="form__item form__item--small <?=isset($errors["price"]) ? "form__item--invalid" : "";?>">
      <label for="price">Начальная цена <sup>*</sup></label>
      <?php $value = isset($lots["price"]) ? $lots["price"] : ""?>
      <input id="price" type="text" name="price" placeholder="0" value="<?= $value;?>">
      <span class="form__error"><?= isset($errors["price"]) ? $errors["price"] : "";?></span>
    </div>
    <div class="form__item form__item--small <?=isset($errors["step_price"]) ? "form__item--invalid" : "";?>">
      <label for="step_price">Шаг ставки <sup>*</sup></label>
      <?php $value = isset($lots["step_price"]) ? $lots["step_price"] : ""?>
      <input id="step_price" type="text" name="step_price" placeholder="0">
      <span class="form__error"><?= isset($errors["step_price"]) ? $errors["step_price"] : "";?></span>
    </div>
    <div class="form__item <?=isset($errors["date_finish"]) ? "form__item--invalid" : "";?>">
      <label for="date_finish">Дата окончания торгов <sup>*</sup></label>
      <?php $value = isset($lots["date_finish"]) ? $lots["date_finish"] : ""?>
      <input class="form__input-date" id="date_finish" type="text" name="date_finish" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?php if (isset($lots['date_finish'])) print $lots['date_finish']?>">
      <span class="form__error"><?= isset($errors["date_finish"]) ? $errors["date_finish"] : "";?></span>
    </div>
  </div>
  <?php if (isset($errors)): ?>
  <div class="form__errors">
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <ul>
      <?php foreach($errors as $err => $val): ?>
      <li><strong><?=$dict[$err];?>:</strong> <?=$val;?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>
  <button type="submit" class="button">Добавить лот</button>
</form>
