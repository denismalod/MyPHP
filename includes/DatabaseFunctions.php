<?php
function totalJokes(PDO $database)
{
    $stmt = $database->prepare('SELECT COUNT(*) FROM `joke`');
    $stmt->execute();
    $row = $stmt->fetch();
    return $row[0];
}

function getJoke(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare('SELECT * FROM `joke` WHERE `id` = :id');
    $values = [
        'id' => $id
    ];
    $stmt->execute($values);
    return $stmt->fetch();
}

function insertJoke(PDO $pdo, string $joketext, int $authorId)
{
    $stmt = $pdo->prepare('INSERT INTO `joke` (`joketext`,
`jokedate`, `authorId`)
 VALUES (:joketext, :jokedate, :authorId)');
    $values = [
        ':joketext' => $joketext,
        ':authorId' => $authorId,
        ':jokedate' => date('Y-m-d')
    ];
    $stmt->execute($values);
}

function updateJoke(PDO $pdo, int $jokeId, string $joketext, int $authorId)
{
    $stmt = $pdo->prepare('UPDATE `joke` SET
 `authorId` = :authorId,
 `joketext` = :joketext
 WHERE `id` = :id');
    $values = [
        ':joketext' => $joketext,
        ':authorId' => $authorId,
        ':id' => $jokeId
    ];
    $stmt->execute($values);
}

function deleteJoke(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare('DELETE FROM `joke` WHERE `id` = :id');
    $values = [
        ':id' => $id
    ];
    $stmt->execute($values);
}

function allJokes(PDO $pdo)
{
    $stmt = $pdo->prepare('SELECT `joke`.`id`, `joketext`, `name`,`email`
                        FROM `joke` INNER JOIN `author`
                        ON `authorid` = `author`.`id`');
    $stmt->execute();
    return $stmt->fetchAll();
}
