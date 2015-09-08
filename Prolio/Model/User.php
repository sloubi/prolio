<?php

namespace Prolio\Model;

class User
{
    public function __construct()
    {
        global $app;
        $this->app = $app;

        $this->email = $this->app->config('user')['email'];
        $this->password = $this->app->config('user')['password'];
    }

    public function check($password)
    {
        return password_verify($password, $this->password);
    }

    public function login()
    {
        $_SESSION['is_logged'] = true;
    }

    public function logout()
    {
        session_destroy();
    }

    public function isLogged()
    {
        return !empty($_SESSION['is_logged']);
    }
}