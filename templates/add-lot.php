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
    <form class="form form--add-lot container <?=count($errors) > 0 ?? "form--invalid"; ?>" action="add.php" method="post" enctype="multipart/form-data">
        <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= isset($errors['lot-name']) ? "form__item--invalid" : ""; ?>">

                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"
                    value="<?= htmlspecialchars(getPostVal('lot-name')); ?>">

                <span class="form__error">
                    <?=$errors['lot-name'] ?? ""; ?>
                </span>
            </div>

            <div class="form__item <?= isset($errors['category']) ? "form__item--invalid" : ""; ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category" value="<?= htmlspecialchars(getPostVal('category')); ?>">
                    <option disable selected value>Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                    <option value=<?= htmlspecialchars($category['id']) ?>><?= htmlspecialchars($category['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <span class="form__error">
                    <?=$errors['category'] ?? ""; ?>
                </span>
            </div>
        </div>

        <div class="form__item form__item--wide <?= isset($errors['message']) ? "form__item--invalid" : ""; ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message"
                placeholder="Напишите описание лота"><?= htmlspecialchars(getPostVal('message')); ?></textarea>
            <span class="form__error">
                <?=$errors['message'] ?? ""; ?>
            </span>
        </div>

        <div class="form__item form__item--file <?= isset($errors['lot-img']) ? "form__item--invalid" : ""; ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img"
                    value="<?= htmlspecialchars(getPostVal('lot-img')); ?>" name="lot-img">
                <label for="lot-img">Добавить</label>
                <span class="form__error">
                    <?=$errors['lot-img'] ?? ""; ?>
                </span>
            </div>
        </div>

        <div class="form__container-three">
            <div class="form__item form__item--small <?= isset($errors['lot-rate']) ? "form__item--invalid" : ""; ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0"
                    value="<?= htmlspecialchars(getPostVal('lot-rate')); ?>">
                <span class="form__error">
                    <?=$errors['lot-rate'] ?? ""; ?>
                </span>
            </div>

            <div class="form__item form__item--small <?= isset($errors['lot-step']) ? "form__item--invalid" : ""; ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" placeholder="0"
                    value="<?= htmlspecialchars(getPostVal('lot-step')); ?>">
                <span class="form__error">
                    <?=$errors['lot-step'] ?? ""; ?>
                </span>
            </div>

            <div class="form__item <?= isset($errors['lot-date']) ? "form__item--invalid" : ""; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date"
                    placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                    value="<?= htmlspecialchars(getPostVal('lot-date')); ?>">
                <span class="form__error">
                    <?=$errors['lot-date'] ?? ""; ?>
                </span>
            </div>
        </div>
        <span class="form__error form__error--bottom">
            <?= count($errors) > 0 ? 'Please correct the errors in the form.' : '' ?>
        </span>
        <button type="submit" class="button" name="submit">Добавить лот</button>
    </form>
</main>
