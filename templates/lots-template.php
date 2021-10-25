<main>
    <?= $categoriesList ?>

    <div class="container">

      <section class="lots">
        <h2>Все лоты в категории <span>«<?= htmlspecialchars($currentCategoryData['name']) ?>»</span></h2>
        <ul class="lots__list">
          <!-- Lots list -->
          <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">

                <div class="lot__image">
                    <img
                        src="uploads/<?= htmlspecialchars($lot['imageUrl']) ?>"
                        alt="<?= htmlspecialchars($lot['name']) ?>"
                        width="350"
                        height="260">
                </div>

                <div class="lot__info">
                    <span class="lot__category">
                        <?= htmlspecialchars($lot['category']) ?>
                    </span>

                    <h3 class="lot__title">
                        <a class="text-link" href="lot.php?id=<?= htmlspecialchars($lot['id']) ?>">
                            <?= htmlspecialchars($lot['name']) ?>
                        </a>
                    </h3>

                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                <?= htmlspecialchars(formatPrice($lot['startPrice'])) ?>
                            </span>
                        </div>

                        <!-- get remaining time for the item -->
                        <?php $remainingTime = calculateRemainingTime($lot['expirationDate']) ?>

                        <!-- add class 'timer--finishing' if $remainingTime less than 1 hour -->
                        <div class="lot__timer timer <?= $remainingTime['hours'] < 1 ? 'timer--finishing' : ''?>">
                            <!-- show $remainingTime -->
                            <?= $remainingTime['hours'] . ' h : ' . $remainingTime['minutes'] . ' min' ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
      </section>


      <!-- show pagination only if we have more than 1 page -->
      <?php if ($pagesTotal > 1) : ?>
                <ul class="pagination-list">
                    <li class="pagination-item pagination-item-prev">
                        <a
                            href="<?= htmlspecialchars(updatePageNumber($currentPage > 1 ? $currentPage - 1 : 1)) ?>">Prev</a>
                    </li>

                    <?php foreach ($pages as $page) { ?>
                    <li
                        class="pagination-item <?= intval($page) === intval($currentPage) ? 'pagination-item-active' : '' ?>">
                        <a href="<?= htmlspecialchars(updatePageNumber($page)) ?>"><?= $page ?></a>
                    </li>
                    <?php } ?>

                    <li class="pagination-item pagination-item-next">
                        <a
                            href="<?= htmlspecialchars(updatePageNumber($currentPage < $pagesTotal ? $currentPage + 1 : $pagesTotal)) ?>">Next</a>
                    </li>
                </ul>

                <?php endif ?>
    </div>
  </main>
