
  <main>
    <nav class="nav">
      <ul class="nav__list container">
        <!-- Show lots categories -->
        <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <section class="lot-item container">
        <?php foreach ($lots as $lot): ?>
            <h2>
                <?= htmlspecialchars($lot['name']) ?>
            </h2>

            <div class="lot-item__content">
                <div class="lot-item__left">

                <div class="lot-item__image">
                    <img
                    src="img/<?= htmlspecialchars($lot['imageUrl']) ?>"
                    alt="<?= htmlspecialchars($lot['name']) ?>"
                    width="730"
                    height="548" >
                </div>

                <p class="lot-item__category">Категория:
                    <span>
                        <?= htmlspecialchars($lot['category']) ?>
                    </span>
                </p>

                <p class="lot-item__description">
                    <?= htmlspecialchars($lot['description']) ?>
                </p>

                </div>
                <div class="lot-item__right">
                <div class="lot-item__state">

                    <!-- get remaining time for the item -->
                    <?php $remainingTime = calculateRemainingTime($lot['expirationDate']) ?>

                    <!-- add class 'timer--finishing' if $remainingTime less than 1 hour -->
                    <div class="lot-item__timer timer <?= $remainingTime['hours'] < 1 ? 'timer--finishing' : ''?>">
                        <!-- show $remainingTime -->
                        <?= $remainingTime['hours'] . ' h : ' . $remainingTime['minutes'] . ' min' ?>
                    </div>

                    <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>

                        <span class="lot-item__cost">
                            <?= htmlspecialchars(formatPrice($lot['startPrice'])) ?>
                        </span>

                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка
                        <span>
                            <?= htmlspecialchars(formatPrice($lot['rateStep'])) ?>
                        </span>
                    </div>
                    </div>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item form__item--invalid">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="12 000">
                        <span class="form__error">Введите наименование лота</span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3>
                    <table class="history__list">
                    <tr class="history__item">
                        <td class="history__name">Иван</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">5 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Константин</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">20 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Евгений</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">Час назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Игорь</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 08:21</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                    </table>
                </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
  </main>
