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

        $tagModel = new \Prolio\Model\Tag();
        $tags = $tagModel->getAllByProject($project_id);
        $tags = $tagModel->tagsToString($tags);

        $buttonModel = new \Prolio\Model\Button();
        $buttons = $buttonModel->getAllByProject($project_id);

        $imageModel = new \Prolio\Model\Image();
        $images = $imageModel->getAllByProject($project_id);

        if ($project)
        {
            $this->app->render('project.twig', [
                'project' => $project,
                'tags' => $tags,
                'buttons' => $buttons,
                'images' => $images
            ]);
        }
        else
        {
            $this->app->notFound();
        }
    }
}