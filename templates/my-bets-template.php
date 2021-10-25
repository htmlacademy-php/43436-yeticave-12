<main>
    <?= $categoriesList ?>

    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">

            <!-- Bets list -->
            <?php foreach ($bets as $bet): ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="uploads/<?= htmlspecialchars($bet['imageUrl']) ?>" width="54" height="40"
                            alt="<?= htmlspecialchars($bet['name']) ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a
                                href="lot.php?id=<?= htmlspecialchars($bet['lotId']) ?>"><?= htmlspecialchars($bet['name']) ?></a>
                        </h3>
                        <!-- <p>Телефон +7 900 667-84-48, Скайп: Vlas92. Звонить с 14 до 20</p> -->
                    </div>
                </td>
                <td class="rates__category">
                    <?= htmlspecialchars($bet['category']) ?>
                </td>
                <td class="rates__timer">
                    <!-- get remaining time for the item -->
                    <?php $remainingTime = calculateRemainingTime($bet['expirationDate']) ?>
                    <!-- timer--win -->
                    <div class="timer <?= $remainingTime['hours'] < 1 ? 'timer--finishing' : ''?>">
                        <!-- show $remainingTime -->
                        <?= $remainingTime['hours'] . ' h : ' . $remainingTime['minutes'] . ' min' ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= htmlspecialchars(formatPrice($bet['betValue'])) ?>
                </td>
                <td class="rates__time">
                    <?= htmlspecialchars($bet['betCreated']) ?>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </section>
</main>
