<?php

namespace Prolio\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $columns = [
        'name',
        'image',
        'extract',
        'description',
        'version',
        'created_at',
        'updated_at',
    ];

    /**
     * Get all projects
     * @param  boolean $full Project with all data (buttons, tags...)
     * @return array
     */
    public function all($full = false)
    {
        $projects = parent::all();

        if ($full)
        {
            $this->addTags($projects, false);
            $this->addButtons($projects);
        }

        return $projects;
    }

    /**
     * Add related tags to projects
     * @param array $projects Array of project object
     * @param bool  $toString Convert to string
     */
    public function addTags(array $projects, $toString = true)
    {
        $tagModel = new \Prolio\Model\Tag();

        foreach ($projects as $project)
        {
            $tags = $tagModel->getAllByProject($project->id);
            if ($toString)
                $tags = $tagModel->tagsToString($tags);
            $project->tags = $tags;
        }
    }

    /**
     * Add related buttons to projects
     * @param array $projects Array of project object
     */
    public function addButtons(array $projects)
    {
        $buttonModel = new \Prolio\Model\Button();

        foreach ($projects as $project)
        {
            $buttons = $buttonModel->getAllByProject($project->id);
            $project->buttons = $buttons;
        }
    }

    public function get($project_id)
    {
        $project = parent::get($project_id);

        if ($project->created_at)
            $project->created_at = date_create($project->created_at)->format('d/m/Y');
        if ($project->updated_at)
            $project->updated_at = date_create($project->updated_at)->format('d/m/Y');

        return $project;
    }

    public function create(array $values)
    {
        $this->prepareColumns($values);
        return parent::create($values);
    }

    public function update(array $values, $project_id)
    {
        $this->prepareColumns($values);
        parent::update($values, $project_id);
    }

    public function prepareColumns(& $values)
    {
        // Set NULL if empty or convert french date to mysql date
        if (empty($values['created_at']))
            $values['created_at'] = null;
        else
            $values['created_at'] = \DateTime::createFromFormat('d/m/Y', $values['created_at'])->format('Y-m-d');

        if (empty($values['updated_at']))
            $values['updated_at'] = null;
        else
            $values['updated_at'] = \DateTime::createFromFormat('d/m/Y', $values['updated_at'])->format('Y-m-d');
    }

    public function delete($project_id)
    {
        parent::delete($project_id);

        // Delete relations
        $tables = ['buttons', 'projects_images', 'projects_tags'];
        foreach ($tables as $table)
        {
            $sql = "DELETE FROM $table WHERE project_id = :project_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':project_id', $project_id, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

}
