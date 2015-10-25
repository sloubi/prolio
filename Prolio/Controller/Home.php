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

        $projects = false;
        $configSite = $this->app->config('site');
        if (!empty($configSite['fullhome']))
        {
            $projectModel = new \Prolio\Model\Project();
            $projects = $projectModel->all(true);
        }
        
        $this->app->render('index.twig', [
            'page' => $page,
            'projects' => $projects
        ]);
    }

}