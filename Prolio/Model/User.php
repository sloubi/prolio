<?php

namespace Prolio\Model;

class User
{
    public function check($password, $hash)
    {
        return password_verify($password, $hash);
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