<p><?= isset($totalJokes) ? $totalJokes : 0 ?> jokes have been submitted to the Internet
    Joke Database.</p>

<?php if (isset($jokes)): ?>
    <?php foreach ($jokes as $joke): ?>
        <blockquote>
            <p>
                <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>
                (by <a href="mailto:<?php
                                    echo htmlspecialchars(
                                        $joke['email'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"><?php
                                            echo htmlspecialchars(
                                                $joke['name'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?></a>)
                <a href="index.php?action=edit&id=<?= $joke['id'] ?>">Edit</a>
            <form action="index.php?action=delete" method="post">
                <input type="hidden" name="id" value="<?= $joke['id'] ?>">
                <input type="submit" value="Delete">
            </form>
            </p>
        </blockquote>
    <?php endforeach; ?>
<?php endif; ?>