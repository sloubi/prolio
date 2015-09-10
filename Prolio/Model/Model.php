<?php

namespace Prolio\Model;

abstract class Model
{
    protected $table;
    protected $columns = array();

    public function __construct()
    {
        global $app;
        $this->app = $app;
        $this->db = \Prolio\Model\Spdo::get();
    }

    /**
     * Get all entities from database
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
     * Get an entity by ID
     * @param int $id
     * @return object
     */
    public function get($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Create a record in table
     * @param  array  $values
     * @return  int   ID of the created entity
     */
    public function create(array $values)
    {
        // Add ":" before all column names
        foreach ($this->columns as $col)
        {
            // We take only columns with values
            if (array_key_exists($col, $values))
            {
                $paramNames[] = ':' . $col;
                $columns[] = $col;
            }

        }
        $columnNames = implode(',', $columns);
        $paramNames = implode(',', $paramNames);

        $sql = "INSERT INTO {$this->table} ($columnNames) VALUES ($paramNames)";
        $stmt = $this->db->prepare($sql);

        // Assign values
        foreach ($columns as $col)
        {
            $stmt->bindValue(':' . $col, $values[$col]);
        }

        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Edit a record in table
     * @param  array  $values
     * @param  int    $id
     */
    public function update(array $values, $id)
    {
        // Add ":" before all column names
        foreach ($this->columns as $col)
        {
            // We take only columns with values
            if (array_key_exists($col, $values))
            {
                $paramNames[] = "$col = :$col";
                $columns[] = $col;
            }

        }
        $paramNames = implode(',', $paramNames);

        $sql = "UPDATE {$this->table} SET $paramNames WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        // Assign values
        foreach ($columns as $col)
        {
            $stmt->bindValue(':' . $col, $values[$col]);
        }
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Delete an entity by ID
     * @param int $id
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}