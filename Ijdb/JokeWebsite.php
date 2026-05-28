<?php

namespace Ijdb;

class JokeWebsite implements \Ninja\Website
{
    private ?\Ninja\DatabaseTable $jokesTable;
    private ?\Ninja\DatabaseTable $authorsTable;
    private ?\Ninja\DatabaseTable $categoriesTable;
    private ?\Ninja\DatabaseTable $jokeCategoriesTable;
    private \Ninja\Authentication $authentication;
    public function __construct()
    {
        $pdo = new \PDO('mysql:host=127.0.0.1:3306;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'mypassword');
        $this->jokesTable = new \Ninja\DatabaseTable(
            $pdo,
            'joke',
            'id',
            '\Ijdb\Entity\Joke',
            [&$this->authorsTable, &$this->jokeCategoriesTable]
        );
        $this->authorsTable = new \Ninja\DatabaseTable(
            $pdo,
            'author',
            'id',
            '\Ijdb\Entity\Author',
            [&$this->jokesTable]
        );
        $this->categoriesTable = new \Ninja\DatabaseTable(
            $pdo,
            'category',
            'id'
        );

        $this->authentication = new \Ninja\Authentication($this->authorsTable, 'email', 'password');
        $this->jokeCategoriesTable = new
            \Ninja\DatabaseTable($pdo, 'joke_category', 'categoryId');
    }

    public function getDefaultRoute(): string
    {
        return 'joke/home';
    }
    public function getController(string $controllerName): ?object
    {
        $controllers = [
            'joke' => new \Ijdb\Controllers\Joke(
                $this->jokesTable,
                $this->authorsTable,
                $this->categoriesTable,
                $this->authentication
            ),
            'author' => new \Ijdb\Controllers\Author($this->authorsTable),
            'login' => new \Ijdb\Controllers\login($this->authentication),
            'category' => new \Ijdb\Controllers\Category($this->categoriesTable)
        ];
        return $controllers[$controllerName] ?? null;
    }
    public function checkLogin(string $uri): ?string
    {
        $restrictedPages = ['joke/edit', 'joke/delete'];
        if (in_array($uri, $restrictedPages) && !$this->authentication->isLoggedIn()) {
            header('location: /login/login');
            exit();
        }
        return $uri;
    }

    public function getLayoutVariables(): array
    {
        return [
            'loggedIn' => $this->authentication->isLoggedIn()
        ];
    }
}
