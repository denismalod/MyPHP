<?php

namespace Ijdb\controllers;

use \Ninja\DatabaseTable;

class Joke
{
    public function __construct(private DatabaseTable
    $jokesTable, private DatabaseTable $authorsTable, private
    \Ninja\Authentication $authentication) {}

    public function home()
    {
        $title = 'Internet Joke Database';
        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function list()
    {
        $jokes = $this->jokesTable->findAll();
        $user = $this->authentication->getUser();
        $totalJokes = $this->jokesTable->total();
        return [
            'template' => 'jokes.html.php',
            'title' => 'Joke List',
            'variables' => [
                'jokes' => $jokes,
                'totalJokes' => $totalJokes,
                'userId' => $user->id ?? null
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
        $author->addJoke($joke);
        header('location: /joke/list');
    }

    public function edit($id = null)
    {
        if (isset($id)) {
            $joke = $this->jokesTable->find('id', $id)[0] ?? null;
        }
        $author = $this->authentication->getUser();
        $title = 'Edit joke';
        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
                'userId' => $author->id 
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
