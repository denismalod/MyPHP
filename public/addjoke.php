<?php
if (isset($_POST['joketext'])) {
    try {
        $pdo = new
            PDO(
                'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
                'ijdbuser',
                'mypassword'
            );
        $sql = 'INSERT INTO `joke` SET
            `joketext` = "' . $_POST['joketext'] . '", `jokedate` = "2021-02-04"';
        $pdo->exec($sql);
        $output = 'Joke added';
        $title = 'Joke added';
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in '
            .
            $e->getFile() . ':' . $e->getLine();
    }
} else {
    $title = 'Add a new joke';
    ob_start();
    include __DIR__ . '/../templates/addjoke.html.php';
    $output = ob_get_clean();
}
include __DIR__ . '/../templates/layout.html.php';
