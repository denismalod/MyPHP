<?php

namespace Ijdb\controllers;

use \Ninja\DatabaseTable;

class Author
{
    public function __construct(private DatabaseTable $authorsTable) {}

    public function home()
    {
        $title = 'Internet Joke Database';
        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function list()
    {
        $result = $this->authorsTable->findAll();
        $authors = [];
        foreach ($result as $author) {
            $authors[] = [
                'id' => $author['id'],
                'name' => $author['name'],
                'email' => $author['email']
            ];
        }
        $title = 'Author list';
        return [
            'template' => 'authors.html.php',
            'title' => $title,
            'variables' => [
                'authors' => $authors
            ]
        ];
    }
}
