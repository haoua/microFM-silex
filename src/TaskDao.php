<?php

namespace SilexApi;

use Doctrine\DBAL\Connection;

class TaskDao
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    protected function getDb()
    {
        return $this->db;
    }

    public function findAll($id)
    {
        $sql = "SELECT * FROM tasks WHERE user_id=?";
        $result = $this->getDb()->fetchAll($sql, array($id));

        $entities = array();
        foreach ( $result as $row ) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObjects($row);
        }

        return $entities;
    }

    protected function buildDomainObjects($row)
    {
        $task = new Task();
        $task->setId($row['id']);
        $task->setName($row['name']);
        $task->setCreated($row['created_at']);

        return $task;
    }

    public function delete($id)
    {
        $this->getDb()->delete('tasks', array('id' => $id));
    }

    public function save(Task $task){
        $taskData = array(
            'user_id' => $task->getUserId(),
            'name' => $task->getName(),
            'created_at' => date('Y-m-d')
        );

        $this->getDb()->insert('tasks', $taskData);
    }
}
