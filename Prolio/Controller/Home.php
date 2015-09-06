<?php

namespace Prolio\Controller;

class Home
{

    public function __construct()
    {
        global $app;
        $this->app = $app;
    }

    public function index()
    {
        $pageModel = new \Prolio\Model\Page();
        $page = $pageModel->getBySlug('home');

        $this->app->render('index.twig', [
            'page' => $page
        ]);
    }

}