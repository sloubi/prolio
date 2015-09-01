<?php

namespace Prolio\Model;

class Image
{
    protected $table = 'projects_images';

    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->db = \Prolio\Model\Spdo::get();
    }

    /**
     * Get images related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $sql = "SELECT pi.filename, pi.name
                FROM {$this->table} pi
                WHERE pi.project_id = :project_id";

        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetchAll();
    }

}
