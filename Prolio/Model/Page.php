<?php

namespace Prolio\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $columns = [
        'name',
        'slug',
        'content'
    ];

    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':slug', $slug, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
    }
}
