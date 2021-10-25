<nav class="nav">
    <ul class="nav__list container">
        <!-- Show all categories -->
        <?php foreach ($categories as $category): ?>
        <li class="nav__item">
            <a href="lots.php?category=<?= htmlspecialchars($category['techName']) ?>">
                <?= htmlspecialchars($category['name']) ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

