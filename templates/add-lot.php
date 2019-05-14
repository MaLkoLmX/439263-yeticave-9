<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $key => $item):?>
        <li class="nav__item">
          <a href="all-lots.html"><?=$item["name"];?></a>
        </li>
        <?php endforeach;?>
      </ul>
    </nav>
    <form class="form form--add-lot container <?=isset($errors) ? "form--invalid" : "";?>" action="add.php" method="post"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?=isset($errors["lot-name"]) ? "form__item--invalid" : "";?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <?php $value = isset($lot["lot-name"]) ? $lot["lot-name"] : ""?>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= $value;?>">
          <span class="form__error"><?=isset($errors["lot-name"]) ? $errors["lot-name"] : "";?></span>
        </div>
        <div class="form__item <?=isset($errors["lot-cat"]) ? "form__item--invalid" : "";?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
            <option>Выберите категорию</option>
            <?php foreach ($categories as $key => $item): ?>
            <option value="<?=$item["id"];?>" <?=$lot["lot-cat"] == $item["id"] ? "selected" : "";?>><?=$item["name"];?></option>
            <?php endforeach;?>
          </select>
          <span class="form__error"><?=isset($errors["lot-cat"]) ? $errors["lot-cat"] : "";?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?=isset($errors["message"]) ? "form__item--invalid" : "";?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"></textarea>
        <span class="form__error">Напишите описание лота</span>
      </div>
      <div class="form__item form__item--file <?=isset($errors["lot_image"]) ? "form__item--invalid" : "";?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" value="">
          <label for="lot-img">
            Добавить
          </label>
        </div>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small <?=isset($errors["lot-rate"]) ? "form__item--invalid" : "";?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <?php $value = isset($lot["lot-rate"]) ? $lot["lot-rate"] : ""?>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= $value;?>">
          <span class="form__error"><?= isset($errors["lot-rate"]) ? $errors["lot-rate"] : "";?></span>
        </div>
        <div class="form__item form__item--small <?=isset($errors["lot-step"]) ? "form__item--invalid" : "";?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <?php $value = isset($lot["lot-step"]) ? $lot["lot-step"] : ""?>
          <input id="lot-step" type="text" name="lot-step" placeholder="0">
          <span class="form__error"><?= isset($errors["lot-step"]) ? $errors["lot-step"] : "";?></span>
        </div>
        <div class="form__item <?=isset($errors["lot-date"]) ? "form__item--invalid" : "";?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <?php $value = isset($lot["lot-date"]) ? $lot["lot-date"] : ""?>
          <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
          <span class="form__error"><?= isset($errors["lot-date"]) ? $errors["lot-date"] : "";?></span>
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
</main>
