<?php

namespace Prolio\Model;

class Button
{
    protected $table = 'buttons';

    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->db = \Prolio\Model\Spdo::get();
    }

    /**
     * Get buttons related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $sql = "SELECT b.name, b.icon, pb.url
                FROM {$this->table} b
                INNER JOIN projects_buttons pb ON pb.button_id = b.id
                WHERE pb.project_id = :project_id";

        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetchAll();
    }

}
