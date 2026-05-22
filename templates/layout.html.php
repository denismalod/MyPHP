<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="jokes.css">
    <title><?= $title ?? "Internet Joke Database" ?></title>
</head>

<body>
    <header>
        <h1>Internet Joke Database</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php?action=list">Jokes List</a></li>
            <li><a href="index.php?action=edit">Add a new Joke</a></li>
        </ul>
    </nav>
    <main>
        <?= $output ?>
    </main>
    <footer>
        &copy; IJDB 2021
    </footer>
</body>

</html>