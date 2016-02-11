<?php

namespace Prolio\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $columns = [
        'name'
    ];

    /**
     * Get all tags from database
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
     * Get tags related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $prefix = $this->db->getPrefix();
        $sql = "SELECT t.*
                FROM {$this->table} t
                INNER JOIN {$prefix}projects_tags pt ON pt.tag_id = t.id
                WHERE pt.project_id = :project_id";

        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetchAll();
    }

    /**
     * Get tags ID related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getIdByProject($project_id)
    {
        $tags = $this->getAllByProject($project_id);
        return array_map(function($e) {
            return $e->id;
        }, $tags);
    }

    /**
     * Convert an array of tags to a pretty string
     * @param  array $tags
     * @return string
     */
    public function tagsToString($tags)
    {
        $result = array_map(function($tag) {
            return $tag->name;
        }, $tags);

        return implode(', ', $result);
    }

    /**
     * Attach some tags to a project
     * @param  int $project_id
     * @param  array  $tags  Tags ID
     */
    public function attachProject($project_id, array $tags)
    {
        $prefix = $this->db->getPrefix();

        // Remove previous tags
        $sql = "DELETE FROM {$prefix}projects_tags WHERE project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
        $stmt->execute();

        // Attach some tags
        $sql = "INSERT INTO {$prefix}projects_tags (project_id, tag_id) VALUES (:project_id, :tag_id)";
        $stmt = $this->db->prepare($sql);

        foreach ($tags as $tag_id)
        {
            // Create new tag
            if (!is_numeric($tag_id))
            {
                $tag_id = $this->create(['name' => $tag_id]);
            }

            $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
            $stmt->bindValue(':tag_id', $tag_id, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

}
