<?php

namespace Prolio\Model;

class Button extends Model
{
    protected $table = 'buttons';

    /**
     * Get buttons related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $sql = "SELECT b.id, b.name, b.icon, pb.url
                FROM {$this->table} b
                INNER JOIN projects_buttons pb ON pb.button_id = b.id
                WHERE pb.project_id = :project_id";

        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetchAll();
    }

    /**
     * Get button ID/url related to a project in a nice array
     * @param int $project_id Id of project
     * @return array
     */
    public function getIdByProject($project_id)
    {
        $buttons = $this->getAllByProject($project_id);
        $pretty = array();
        foreach ($buttons as $button)
        {
            $pretty[$button->id] = $button->url;
        }
        return $pretty;
    }

    /**
     * Attach some buttons to a project
     * @param  int $project_id 
     * @param  array  $buttons  [button_id => url]
     */
    public function attachProject($project_id, array $buttons)
    {
        // Remove previous buttons
        $sql = "DELETE FROM projects_buttons WHERE project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
        $stmt->execute();

        // Attach some buttons
        $sql = "INSERT INTO projects_buttons (project_id, button_id, url) VALUES (:project_id, :button_id, :url)";
        $stmt = $this->db->prepare($sql);

        foreach ($buttons as $button_id => $url)
        {
            if (!empty($url))
            {
                $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
                $stmt->bindValue(':button_id', $button_id, \PDO::PARAM_INT);
                $stmt->bindValue(':url', $url, \PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }

}
