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

function total(PDO $pdo, string $table)
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM `' . $table . '`');
    $stmt->execute();
    $row = $stmt->fetch();
    return $row[0];
}

function find(PDO $pdo, string $table, string $field, mixed $value): array
{
    $query = 'SELECT * FROM `' . $table . '` WHERE `' . $field
        . '` = :value';
    $values = [
        'value' => $value
    ];
    $stmt = $pdo->prepare($query);
    $stmt->execute($values);
    return $stmt->fetchAll();
}

function processDates(array $values): array
{
    foreach ($values as $key => $value) {
        if ($value instanceof DateTime) {
            $values[$key] = $value->format('Y-m-d H:i:s');
        }
    }
    return $values;
}


function insert(PDO $pdo, string $table, array $values)
{
    $query = 'INSERT INTO `' . $table . '` (';
    foreach ($values as $key => $value) {
        $query .= '`' . $key . '`,';
    }
    $query = rtrim($query, ',');
 $query .= ') VALUES (';
 foreach ($values as $key => $value) {
 $query .= ':' . $key . ',';
 }
 $query = rtrim($query, ',');
 $query .= ')';
 $values = processDates($values);
 $stmt = $pdo->prepare($query);
 $stmt->execute($values);
}
function update(PDO $pdo, string $table, string $primaryKey, array $values) {
 $query = ' UPDATE `' . $table .'` SET ';
 foreach ($values as $key => $value) {
 $query .= '`' . $key . '` = :' . $key . ',';
 }
 $query = rtrim($query, ',');
 $query .= ' WHERE `' . $primaryKey . '` = :primaryKey';
 // Set the :primaryKey variable
 $values['primaryKey'] = $values['id'];
 $values = processDates($values);
 $stmt = $pdo->prepare($query);
 $stmt->execute($values);
}

function delete(PDO $pdo, string $table, string $field, mixed $value)
{
    $values = ['value' => $value];
    $stmt = $pdo->prepare('DELETE FROM `' . $table . '`
WHERE `' . $field . '` = :value');
    $stmt->execute($values);
}

function findAll(PDO $pdo, string $table)
{
    $stmt = $pdo->prepare('SELECT * FROM `' . $table . '`');
    $stmt->execute();
    return $stmt->fetchAll();
}
