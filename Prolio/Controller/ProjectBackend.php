<?php

namespace Prolio\Controller;

class ProjectBackend
{
    public function __construct()
    {
        global $app;
        $this->app = $app;

        $this->projectModel = new \Prolio\Model\Project();
        $this->tagModel = new \Prolio\Model\Tag();
        $this->buttonModel = new \Prolio\Model\Button();
    }

    public function  all()
    {
        $projects = $this->projectModel->all();
        $this->projectModel->addTags($projects);

        $this->app->render('backend/project_list.twig', [
            'projects' => $projects
        ]);
    }

    public function add()
    {
        $tags = $this->tagModel->all();
        $buttons = $this->buttonModel->all();

        $this->app->render('backend/project_form.twig', [
            'error'   => false,
            'tags'    => $tags,
            'buttons' => $buttons
        ]);
    }

    public function edit($project_id)
    {
        $tags = $this->tagModel->all();
        $buttons = $this->buttonModel->all();

        $project = $this->projectModel->get($project_id);
        $project->tags = $this->tagModel->getIdByProject($project_id);
        $project->buttons = $this->buttonModel->getIdByProject($project_id);

        $this->app->render('backend/project_form.twig', [
            'error'   => false,
            'tags'    => $tags,
            'buttons' => $buttons,
            'project' => $project,
            'edit'    => true,
            'post'    => $this->app->request->post()
        ]);
    }

    public function process($project_id = null)
    {
        $request = $this->app->request;

        $name         = $request->post('name');
        $extract      = $request->post('extract');
        $description  = $request->post('description');

        // Required fields
        if ($name && $extract && $description)
        {
            // All post values
            $values = $request->post();

            if ($image = $this->upload())
                $values['image'] = $image;

            // Create project
            if (is_null($project_id))
            {
                $project_id = $this->projectModel->create($values);
                $this->app->flash('success', 'Projet créé');
            }
            // Update project
            else
            {
                $this->projectModel->update($values, $project_id);
                $this->app->flash('success', 'Projet modifié');
            }

            $this->tagModel->attachProject($project_id, $request->post('tags', []));
            $this->buttonModel->attachProject($project_id, $request->post('buttons', []));

            $this->app->redirect('/admin/project/list');
        }
        // Error
        else
        {
            $this->app->flashNow('error', 'Remplissez tous les champs obligatoires');
            is_null($project_id) ? $this->add() : $this->edit($project_id);
        }
    }

    protected function upload()
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] > 0)
            return false;

        $name = uniqid('img-'.date('Ymd').'-') . '.jpg';
        move_uploaded_file($_FILES['image']['tmp_name'], PUBLIC_DIR . '/images/projects/'.$name);

        return $name;
    }

    public function delete($project_id)
    {
        $this->projectModel->delete($project_id);

        $this->app->flash('success', 'Projet supprimé');
        $this->app->redirect('/admin/project/list');
    }
}