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
                <?php if (empty($joke) || $userId == $joke['authorId']): ?>
                    <a href="/joke/edit/<?= $joke['id'] ?>">Edit</a>
            <form action="/joke/delete" method="post">
                <input type="hidden" name="id" value="<?= $joke['id'] ?>">
                <input type="submit" value="Delete">
            </form>
        <?php endif; ?>
        </p>
        </blockquote>
    <?php endforeach; ?>
<?php endif; ?>