<?php

namespace Prolio\Model;

class Project
{
    protected $table = 'projects';

    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->db = \Prolio\Model\Spdo::get();
    }

    /**
     * Get all projects from database
     * @return  array
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * Get a project from database
     * @param int $project_id Id of project
     * @return object
     */
    public function get($project_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :project_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetch();
    }

}
