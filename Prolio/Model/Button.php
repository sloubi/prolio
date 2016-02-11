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
        $sql = "SELECT b.id, b.name, b.icon, b.url, b.blank
                FROM {$this->table} b
                WHERE b.project_id = :project_id";

        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetchAll();
    }

    /**
     * Attach some buttons to a project
     * @param  int $project_id
     * @param  array  $buttons
     */
    public function attachProject($project_id, array $buttons)
    {
        // Remove previous buttons
        $sql = "DELETE FROM {$this->table} WHERE project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
        $stmt->execute();

        // Attach some buttons
        $sql = "INSERT INTO {$this->table} (project_id, name, icon, url, blank) VALUES (:project_id, :name, :icon, :url, :blank)";
        $stmt = $this->db->prepare($sql);

        foreach ($buttons as $button)
        {
            $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
            $stmt->bindValue(':name', $button['name'], \PDO::PARAM_STR);
            $stmt->bindValue(':icon', $button['icon'], \PDO::PARAM_STR);
            $stmt->bindValue(':url', $button['url'], \PDO::PARAM_STR);
            $stmt->bindValue(':blank', $button['blank'], \PDO::PARAM_BOOL);
            $stmt->execute();
        }
    }

}
