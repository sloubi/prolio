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
        $hash = $this->app->config('user')['password'];

        // Success
        if ($this->user->check($password, $hash))
        {
            $this->user->login();
            $this->app->redirect('/admin/home', 301);
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
    }

    public function home()
    {
        $projectModel = new \Prolio\Model\Project();
        $tagModel = new \Prolio\Model\Tag();

        $projects = $projectModel->all();
        $projectModel->addTags($projects);

        $this->app->render('backend/home.twig', [
            'projects' => $projects
        ]);
    }

}