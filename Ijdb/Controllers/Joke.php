<?php

namespace Ijdb\controllers;

use \Ninja\DatabaseTable;
use \Ninja\Authentication;


class Joke
{
    public function __construct(private DatabaseTable
    $jokesTable, private DatabaseTable $authorsTable, private DatabaseTable
    $categoriesTable, private Authentication $authentication) {}

    public function home()
    {
        $title = 'Internet Joke Database';
        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function list($categoryId = null)
    {
        if (isset($categoryId)) {
            $category = $this->categoriesTable->find('id', $categoryId)[0];
            $jokes = $category->getJokes();
        } else {
            $jokes = $this->jokesTable->findAll();
        }
        $user = $this->authentication->getUser();
        $totalJokes = $this->jokesTable->total();
        return [
            'template' => 'jokes.html.php',
            'title' => 'Joke List',
            'variables' => [
                'jokes' => $jokes,
                'totalJokes' => $totalJokes,
                'userId' => $user->id ?? null,
                'categories' => $this->categoriesTable->findAll()
            ]
        ];
    }

    public function editSubmit()
    {
        $author = $this->authentication->getUser();
        if (!empty($id)) {
            $joke = $this->jokesTable->find('id', $id)[0];
            if ($joke->authorId != $author->id) {
                return;
            }
        }
        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();
        $jokeEntity = $author->addJoke($joke);
        foreach ($_POST['category'] as $categoryId) {
            $jokeEntity->addCategory($categoryId);
        }
        header('location: /joke/list');
    }

    public function edit($id = null)
    {
        $author = $this->authentication->getUser();
        $categories = $this->categoriesTable->findAll();
        if (!empty($id)) {
            $joke = $this->jokesTable->find('id', $id)[0];
        } else {
            $joke = null;
        }
        $title = 'Edit joke';
        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
                'userId' => $author->id ?? null,
                'categories' => $categories
            ]
        ];
    }



    public function deleteSubmit()
    {
        $author = $this->authentication->getUser();
        $joke = $this->jokesTable->find('id', $_POST['id'])[0];
        if ($joke->authorid != $author->id) {
            return;
        }
        $this->jokesTable->delete('id', $_POST['id']);
        header('location: /joke/list');
    }
}
