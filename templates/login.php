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
<form class="form container <?= !empty($errors) ? "form--invalid" : "" ?>" action="login.php" method="post">
    <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?= isset($errors["email"]) ? "form__item--invalid" : "" ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?php if (isset($form["email"])) print $form["email"] ?>">
        <span class="form__error"><?= isset($errors["email"]) ? esc($errors["email"]) : ""; ?></span>
    </div>
    <div class="form__item form__item--last <?= isset($errors["password"]) ? "form__item--invalid" : "" ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= isset($errors["password"]) ? esc($errors["password"]) : ""; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
