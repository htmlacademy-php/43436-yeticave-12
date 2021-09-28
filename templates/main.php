<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!-- Show lots categories -->
            <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--boards">
                    <a class="promo__link" href="pages/all-lots.html">
                        <?= htmlspecialchars($category) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!-- Lots list -->
            <?php foreach ($lots as $lotKey => $lotValue): ?>
            <li class="lots__item lot">

                <div class="lot__image">
                    <img
                        src="img/<?= htmlspecialchars($lotValue['imgUrl']) ?>"
                        alt="<?= htmlspecialchars($lotValue['name']) ?>"
                        width="350"
                        height="260">
                </div>

                <div class="lot__info">
                    <span class="lot__category">
                        <?= htmlspecialchars($lotValue['category']) ?>
                    </span>

                    <h3 class="lot__title">
                        <a class="text-link" href="pages/lot.html">
                            <?= htmlspecialchars($lotValue['name']) ?>
                        </a>
                    </h3>

                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                <?= htmlspecialchars(formatPrice($lotValue['price'])) ?>
                            </span>
                        </div>

                        <!-- get remaining time for the item -->
                        <?php $remainingTime = getDateRange($lotValue['expiryDate']) ?>

                        <!-- add class 'timer--finishing' if $remainingTime less than 1 hour -->
                        <div class="lot__timer timer <?= $remainingTime[0] < 1 ? 'timer--finishing' : ''?>">
                            <!-- show $remainingTime -->
                            <?= $remainingTime[0] . ' h : ' . $remainingTime[1] . ' min' ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
