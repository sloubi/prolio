<?php

namespace Prolio\Controller;

class SettingsBackend
{
    public function __construct()
    {
        global $app;
        $this->app = $app;
    }

    public function index()
    {
        require APP_DIR . '/config/config.php';
        $post = $this->app->request->post();

        if ($this->app->request->isPost())
        {
            $this->writeConfig($post, $config);
            $this->app->flash('success', 'Paramètres modifiés');
            $this->app->redirect($this->app->urlFor('settings'));
        }

        $this->app->render('backend/settings_form.twig', [
            'config' => $config,
            'post'   => $post
        ]);
    }

    public function writeConfig($post, $config)
    {
        $config['user']['email'] = $post['email'];
        $config['user']['adminLink'] = $post['adminlink'];
        if (!empty($post['password']))
            $config['user']['password'] = password_hash($this->app->request->post('password'), PASSWORD_DEFAULT);

        $configString = '<?php' . "\n" . '$config = ' . var_export($config, true) . ';';

        file_put_contents(APP_DIR . '/config/config.php', $configString);
    }
}
