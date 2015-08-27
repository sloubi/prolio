<?php

namespace Prolio\Model;

class Tag
{
    protected $table = 'tags';

    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->db = \Prolio\Model\Spdo::get();
    }

    /**
     * Get tags related to a project
     * @param int $project_id Id of project
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $sql = "SELECT t.*
                FROM {$this->table} t
                INNER JOIN projects_tags pt ON pt.tag_id = t.id
                WHERE pt.project_id = :project_id";

        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetchAll();
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

}
