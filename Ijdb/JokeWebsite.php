<?php

namespace Ijdb;

class JokeWebsite implements \Ninja\Website
{
    public function getDefaultRoute(): string
    {
        return 'joke/home';
    }
    public function getController(string $controllerName): ?object
    {
        $pdo = new \PDO(
            'mysql:host=127.0.0.1:3306;dbname=ijdb;charset=utf8mb4',
            'ijdbuser',
            'mypassword'
        );
        $jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id');
        $authorsTable = new \Ninja\DatabaseTable(
            $pdo,
            'author',
            'id'
        );
        if ($controllerName === 'joke') {
            $controller = new \Ijdb\controllers\Joke(
                $jokesTable,
                $authorsTable
            );
        } else if ($controllerName === 'author') {
            $controller = new \Ijdb\controllers\Author($authorsTable);
        }
            else {
                $controller =null;
            }
        return $controller;
    }
}
