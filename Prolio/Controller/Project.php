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

    public function get($project_slug = null)
    {
        $project = $this->model->getBySlug($project_slug);

        $tagModel = new \Prolio\Model\Tag();
        $tags = $tagModel->getAllByProject($project->id);
        $tags = $tagModel->tagsToString($tags);

        $compModel = new \Prolio\Model\Compatibility();
        $comps = $compModel->getAllByProject($project->id);
        $comps = $compModel->compatibilitiesToString($comps);

        $buttonModel = new \Prolio\Model\Button();
        $buttons = $buttonModel->getAllByProject($project->id);

        $imageModel = new \Prolio\Model\Image();
        $images = $imageModel->getAllByProject($project->id);

        if ($project)
        {
            $this->app->render('project.twig', [
                'project' => $project,
                'tags' => $tags,
                'comps' => $comps,
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
