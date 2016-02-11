<?php

namespace Prolio\Model;

class Compatibility extends Model
{
    protected $table = 'compatibilities';
    protected $columns = [
        'name',
        'category'
    ];

    /**
     * Get all compatibilities from database
     * @return  array
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY category";
        $query = $this->db->prepare($sql);
        $query->execute();
        $results = $query->fetchAll();

        $compatibilities = array();
        foreach ($results as $compat)
        {
            $compatibilities[$compat->category][] = $compat;
        }
        return $compatibilities;
    }

    /**
     * Get compatibilities related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $prefix = $this->db->getPrefix();
        $sql = "SELECT c.*
                FROM {$this->table} c
                INNER JOIN {$prefix}projects_compatibilities pc ON pc.compatibility_id = c.id
                WHERE pc.project_id = :project_id";

        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetchAll();
    }

    /**
     * Get compatibility ID related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getIdByProject($project_id)
    {
        $compatibilities = $this->getAllByProject($project_id);
        return array_map(function($e) {
            return $e->id;
        }, $compatibilities);
    }

    /**
     * Convert an array of compatibilities to a pretty string
     * @param  array $compatibilities
     * @return string
     */
    public function compatibilitiesToString($compatibilities)
    {
        $result = array_map(function($compatibility) {
            return $compatibility->name;
        }, $compatibilities);

        return implode(', ', $result);
    }

    /**
     * Attach some compatibilities to a project
     * @param  int $project_id
     * @param  array  $compatibilities  compatibilities ID
     */
    public function attachProject($project_id, array $compatibilities)
    {
        $prefix = $this->db->getPrefix();

        // Remove previous compatibilities
        $sql = "DELETE FROM {$prefix}projects_compatibilities WHERE project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
        $stmt->execute();

        // Attach some compatibilities
        $sql = "INSERT INTO {$prefix}projects_compatibilities (project_id, compatibility_id)
                VALUES (:project_id, :compatibility_id)";
        $stmt = $this->db->prepare($sql);

        foreach ($compatibilities as $compatibility_id)
        {
            $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
            $stmt->bindValue(':compatibility_id', $compatibility_id, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

}
