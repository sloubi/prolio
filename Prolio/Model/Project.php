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


    public function getAmountOfSongs()
    {
        $sql = "SELECT COUNT(id) AS amount_of_songs FROM song";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetch()->amount_of_songs;
    }

    /**
     * Get all songs from database
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * Get a song from database
     * @param int $song_id Id of song
     * @return mixed
     */
    public function get($project_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :project_id LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute([':project_id' => $project_id]);

        return $query->fetch();
    }
}
