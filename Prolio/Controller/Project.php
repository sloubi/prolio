<?php

namespace Prolio\Controller;

class Project
{

    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->model = new \Prolio\Model\Project();
    }

    public function all()
    {
        $projects = $this->model->all();

        $this->app->render('projects.twig', [
            'projects' => $projects
        ]);
    }

    public function get($project_id = null)
    {
        $project = $this->model->get($project_id);

        if ($project)
        {
            $this->app->render('project.twig', [
                'project' => $project
            ]);
        }
        else
        {
            $this->app->notFound();
        }
    }
}