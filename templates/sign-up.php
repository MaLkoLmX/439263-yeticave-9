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
<form class="form container <?= isset($errors) ? "form--invalid" : ""; ?>" action="sign-up.php" method="post"
      autocomplete="off"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($errors["email"]) ? "form__item--invalid" : ""; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?php if (isset($form["email"])) print $form["email"] ?>">
        <span class="form__error"><?= isset($errors["email"]) ? $errors["email"] : ""; ?></span>
    </div>
    <div class="form__item <?= isset($errors["password"]) ? "form__item--invalid" : ""; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= isset($errors["password"]) ? $errors["password"] : ""; ?></span>
    </div>
    <div class="form__item <?= isset($errors["name"]) ? "form__item--invalid" : ""; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя"
               value="<?php if (isset($form["name"])) print $form["name"] ?>">
        <span class="form__error"><?= isset($errors["name"]) ? $errors["name"] : ""; ?></span>
    </div>
    <div class="form__item <?= isset($errors["message"]) ? "form__item--invalid" : ""; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"></textarea>
        <span class="form__error"><?= isset($errors["message"]) ? $errors["message"] : ""; ?></span>
    </div>
    <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php foreach ($errors as $err => $val): ?>
                <li><strong><?= $dict[$err]; ?>:</strong> <?= $val; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
