<?php

namespace Prolio\Controller;

class PageBackend
{
    public function __construct()
    {
        global $app;
        $this->app = $app;

        $this->pageModel = new \Prolio\Model\Page();
    }

    public function  all()
    {
        $pages = $this->pageModel->all();

        $this->app->render('backend/page_list.twig', [
            'pages' => $pages
        ]);
    }

    public function edit($page_id)
    {
        $page = $this->pageModel->get($page_id);

        $this->app->render('backend/page_form.twig', [
            'error'           => false,
            'page'            => $page,
            'post'            => $this->app->request->post()
        ]);
    }

    public function process($page_id)
    {
        $request = $this->app->request;
        $name    = $request->post('name');
        $content = $request->post('content');

        // Required fields
        if ($name && $content)
        {
            $this->pageModel->update($this->app->request->post(), $page_id);
            $this->app->flash('success', 'Page modifié');
            $this->app->redirect($this->app->urlFor('page_list'));
        }
        // Error
        else
        {
            $this->app->flashNow('error', 'Remplissez tous les champs obligatoires');
            $this->edit($page_id);
        }
    }

}
