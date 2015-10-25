<?php

namespace Prolio\Controller;

class Install
{

    public function __construct()
    {
        global $app;
        $this->app = $app;
    }

    public function index()
    {
        $error = false;
        if ($this->app->request->isPost())
        {
            if (!filter_var($this->app->request->post('email'), FILTER_VALIDATE_EMAIL))
            {
                $error = 'Email invalide.';
            }
            else if (!$this->isDBConnexionOK())
            {
                $error = 'Connexion à la base de données impossible.';
            }
            else
            {
                $this->writeConfig();
                $this->createTables();
                $this->app->flash('success', 'Installation réussie !');
                $this->app->redirect('/');
            }
        }

        $this->app->render('install.twig', [
            'error' => $error,
            'post'  => $this->app->request->post(),
            'host'  => $this->app->request->getUrl()
        ]);
    }

    public function writeConfig()
    {
        $config = array(
            'debug' => false,
            'database' => array(
                'host' => $this->app->request->post('db_host'),
                'port' => '',
                'name' => $this->app->request->post('db_name'),
                'user' => $this->app->request->post('db_user'),
                'pass' => $this->app->request->post('db_pass')
            ),
            'user' => array(
                'email' => $this->app->request->post('email'),
                'password' => password_hash($this->app->request->post('password'), PASSWORD_DEFAULT),
                'adminLink' => $this->app->request->post('admin_link')
            )
        );
        $configString = '<?php' . "\n" . '$config = ' . var_export($config, true) . ';';

        file_put_contents(PUBLIC_DIR . '/../config/config.php', $configString);
    }

    public function isDBConnexionOK()
    {
        $isConnexionOK = true;
        try
        {
            $this->pdo = new \PDO('mysql:host='.$this->app->request->post('db_host').';dbname='.$this->app->request->post('db_name'),
                $this->app->request->post('db_user'),
                $this->app->request->post('db_passw'),
                array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )
            );
        }
        catch (\Exception $e)
        {
            $isConnexionOK = false;
        }
        return $isConnexionOK;
    }

    public function createTables()
    {
        $sql = 
            "CREATE TABLE IF NOT EXISTS `buttons` (
              `id` int(11) NOT NULL,
              `name` varchar(32) NOT NULL,
              `icon` varchar(32) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            INSERT INTO `buttons` (`id`, `name`, `icon`) VALUES
            (1, 'Jouer', 'glyphicon-play'),
            (2, 'Télécharger', 'glyphicon-download'),
            (3, 'Télécharger les sources', 'glyphicon-eye-open'),
            (4, 'Lancer l''application', 'glyphicon-flash');

            CREATE TABLE IF NOT EXISTS `tags` (
              `id` int(11) NOT NULL,
              `name` varchar(32) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            INSERT INTO `tags` (`id`, `name`) VALUES
            (1, 'php'),
            (2, 'html'),
            (3, 'jeu'),
            (4, 'svg');

            CREATE TABLE IF NOT EXISTS `pages` (
              `id` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `slug` varchar(255) NOT NULL,
              `content` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            INSERT INTO `pages` (`id`, `name`, `slug`, `content`) VALUES
            (1, 'Sloubi', 'home', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut sapiente dolor porro maiores recusandae excepturi possimus, distinctio, explicabo dolore! Vitae eum animi consectetur aliquam reprehenderit eos voluptatem, nobis optio, velit reiciendis praesentium placeat rerum fugiat autem unde, dolorem ratione natus eligendi a quam qui. Distinctio provident, esse eos ex nulla?');

            CREATE TABLE IF NOT EXISTS `projects` (
              `id` int(11) NOT NULL,
              `name` varchar(100) NOT NULL,
              `image` varchar(32) NOT NULL,
              `version` varchar(16) DEFAULT NULL,
              `description` text NOT NULL,
              `extract` varchar(255) NOT NULL,
              `created_at` date DEFAULT NULL,
              `updated_at` date DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE IF NOT EXISTS `projects_buttons` (
              `project_id` int(11) NOT NULL,
              `button_id` int(11) NOT NULL,
              `url` varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE IF NOT EXISTS `projects_images` (
              `id` int(11) NOT NULL,
              `project_id` int(11) NOT NULL,
              `name` varchar(100) NOT NULL,
              `filename` varchar(32) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            CREATE TABLE IF NOT EXISTS `projects_tags` (
              `project_id` int(11) NOT NULL,
              `tag_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            ALTER TABLE `buttons`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `pages`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `slug` (`slug`);

            ALTER TABLE `projects`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `projects_buttons`
              ADD PRIMARY KEY (`project_id`,`button_id`);

            ALTER TABLE `projects_images`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `projects_tags`
              ADD PRIMARY KEY (`project_id`,`tag_id`);

            ALTER TABLE `tags`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `buttons`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
            ALTER TABLE `pages`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
            ALTER TABLE `projects`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
            ALTER TABLE `projects_images`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
            ALTER TABLE `tags`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;";

        $this->pdo->exec($sql);
    }

}