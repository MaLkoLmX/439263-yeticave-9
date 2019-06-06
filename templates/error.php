<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $key => $item): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= esc($item["name"]); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?= $error_title; ?></h2>
    <p><?= $error; ?></p>
</section>
