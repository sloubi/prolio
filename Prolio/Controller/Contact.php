<?php

namespace Prolio\Controller;

class Contact
{

    public function __construct()
    {
        global $app;
        $this->app = $app;
    }

    public function get()
    {
        $this->app->render('contact.twig');
    }

    public function post()
    {
        $error = null;

        // Missing input
        if (empty($this->app->request->post('name')) || 
            empty($this->app->request->post('email')) || 
            empty($this->app->request->post('message')))
        {
            $error = 'Veuillez remplir tous les champs.';
        }
        // Email not valid
        else if (!filter_var($this->app->request->post('email'), FILTER_VALIDATE_EMAIL))
        {
            $error = 'Votre adresse email est invalide.';
        }
        // Send email
        else
        {
            $to = $this->app->config('user')['email'];
            $message = htmlspecialchars($this->app->request->post('message'));
            mail($to, 'Contact from Prolio', $message);
        }

        $this->app->render('contact.twig', [
            'sent' => empty($error),
            'error' => $error
        ]);
    }

}