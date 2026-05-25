<?php

namespace Ijdb\controllers;

use \Ninja\DatabaseTable;

class Joke
{
    public function __construct(private DatabaseTable
    $jokesTable, private DatabaseTable $authorsTable) {}

    public function home()
    {
        $title = 'Internet Joke Database';
        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function list()
    {
        $result = $this->jokesTable->findAll();
        $jokes = [];
        foreach ($result as $joke) {
            $author = $this->authorsTable->find(
                'id',
                $joke['authorid']
            )[0];
            $jokes[] = [
                'id' => $joke['id'],
                'joketext' => $joke['joketext'],
                'jokedate' => $joke['jokedate'],
                'name' => $author['name'],
                'email' => $author['email']
            ];
        }
        $title = 'Joke list';
        $totalJokes = $this->jokesTable->total();
        return [
            'template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes
            ]
        ];
    }

    public function editSubmit()
    {
        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();
        $joke['authorId'] = 1;
        $this->jokesTable->save($joke);
        header('location: /joke/list');
    }

    public function edit($id = null)
    {
        if (isset($id)) {
            $joke = $this->jokesTable->find('id', $id)[0] ?? null;
        }
        $title = 'Edit joke';
        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null
            ]
        ];
    }

    public function deleteSubmit()
    {
        $this->jokesTable->delete('id', $_POST['id']);
        header('location: /joke/list');
    }
}
