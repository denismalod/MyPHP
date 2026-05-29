<?php

namespace Ijdb\controllers;

use \Ninja\DatabaseTable;

class Author
{
    public function __construct(private DatabaseTable $authorsTable) {}

    public function registrationForm()
    {
        return [
            'template' => 'register.html.php',
            'title' => 'Register an account'
        ];
    }

    public function registrationFormSubmit()
    {
        $author = $_POST['author'];
        // Start with an empty array
        $errors = [];
        // But if any of the fields have been left blank, set $valid to false
        if (empty($author['name'])) {
            $errors[] = 'Name cannot be blank';
        }
        if (empty($author['email'])) {
            $errors[] = 'Email cannot be blank';
        } else if (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false) {
            $errors[] = 'Invalid email format';
        } else {
            $author['email'] = strtolower($author['email']);
            if (count($this->authorsTable->find('email', $author['email'])) > 0) {
                $errors[] = 'Email address is already registered';
            }
        }
        if (empty($author['password'])) {
            $errors[] = 'Password cannot be blank';
        }
        // If the $errors array is still empty, no fields were blank and the data can be added
        if (empty($errors)) {
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);
            $this->authorsTable->save($author);
            $author['permissions'] = 0;
            header('Location: /author/success');
        } else {
            // If the data is not valid, show the form again
            return [
                'template' => 'register.html.php',
                'title' => 'Register an account',
                'variables' => [
                    'errors' => $errors,
                    'author' => $author
                ]
            ];
        }
    }


    public function success()
    {
        return [
            'template' => 'registersuccess.html.php',
            'title' => 'Registration Successful'
        ];
    }

    public function list()
    {
        $authors = $this->authorsTable->findAll();
        return [
            'template' => 'authorlist.html.php',
            'title' => 'Author List',
            'variables' => [
                'authors' => $authors
            ]
        ];
    }

    public function permissions($id = null)
    {
        $author = $this->authorsTable->find('id', $id)[0];
        $reflected = new \ReflectionClass('\Ijdb\Entity\Author');
        $constants = $reflected->getConstants();
        return [
            'template' => 'permissions.html.php',
            'title' => 'Edit Permissions',
            'variables' => [
                'author' => $author,
                'permissions' => $constants
            ]
        ];
    }

    public function permissionsSubmit($id = null)
    {
        $author = [
            'id' => $id,
            'permissions' => array_sum($_POST['permissions'] ?? [])
        ];
        $this->authorsTable->save($author);
        header('location: /author/list');
    }
}
