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
            $globals = $this->app->view->getInstance()->getGlobals();
            $siteTitle = $globals['siteTitle'];
            $to        = $this->app->config('user')['email'];
            $fromEmail = $this->app->request->post('email');
            $fromName  = $this->app->request->post('name');
            $message   = htmlspecialchars($this->app->request->post('message'));

            $headers  = "To: $to\r\n";
            $headers .= "From: $fromName <$fromEmail>\r\n";
            $headers .= "Reply-To: $fromName <$fromEmail>\r\n";

            mail($to, "Contact from $siteTitle", $message, $headers);
        }

        $result = [
            'sent' => empty($error),
            'error' => $error
        ];

        if ($this->app->request->isAjax())
        {
            echo json_encode($result);
        }
        else
        {
            $this->app->render('contact.twig', $result);
        }
    }

}