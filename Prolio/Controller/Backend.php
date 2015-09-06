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
            $this->app->redirect('/admin');
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
        $this->app->redirect('/admin');
    }

    public function index()
    {
        $pageModel = new \Prolio\Model\Page();
        $page = $pageModel->getBySlug('home');

        if ($this->app->request->isPost())
        {
            $values['content'] = $this->app->request->post('content');
            $values['slug'] = $page->slug;
            $values['name'] = $page->name;
            $pageModel->update($values, $page->id);

            $this->app->flash('success', 'Accueil modifié');
            $this->app->redirect('/admin');
        }

        $this->app->render('backend/index.twig', [
            'page' => $page
        ]);
    }

}