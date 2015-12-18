<?php

namespace Prolio\Controller;

class Backend
{
    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->user = new \Prolio\Model\User();
    }

    public function loginGet()
    {
        $this->app->render('backend/login.twig');
    }

    public function loginPost()
    {
        $password = $this->app->request->post('password');

        // Success
        if ($this->user->check($password))
        {
            $this->user->login();
            $this->app->redirect($this->app->urlFor('admin'));
        }
        // Invalid credentials
        else
        {
            $this->app->render('backend/login.twig', [
                'error' => true
            ]);
        }
    }

    public function logout()
    {
        $this->user->logout();
        $this->app->redirect($this->app->urlFor('admin'));
    }

    public function index()
    {
        $this->app->redirect($this->app->urlFor('project_list'));
    }

}
