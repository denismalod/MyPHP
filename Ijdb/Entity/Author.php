<?php

namespace Ijdb\Entity;

class Author
{
    public int $id;
    public string $name;
    public string $email;
    public ?string $password;

    public function __construct(private \Ninja\DatabaseTable $jokesTable) {}
    public function getJokes()
    {
        return $this->jokesTable->find('authorId', $this->id);
    }

    public function addJoke(array $joke)
    {
        $joke['authorId'] = $this->id;
        $this->jokesTable->save($joke);
    }
}
