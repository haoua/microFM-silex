<?php

namespace SilexApi;

use Doctrine\DBAL\Connection;

class UserDao
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

    public function findAll()
    {
        $sql = "SELECT * FROM user";
        $result = $this->getDb()->fetchAll($sql);

        $entities = array();
        foreach ( $result as $row ) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObjects($row);
        }

        return $entities;
    }

    public function find($username)
    {
        $sql = "SELECT * FROM user WHERE username=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row) {
            return $this->buildDomainObjects($row);
        } else {
//           throw new \Exception("No user matching id ".$username);
           return 0;
        }
    }

    public function save(User $user)
    {
        $userData = array(
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname()
        );

        // TODO CHECK
        if ($user->getId()) {
            $this->getDb()->update('user', $userData, array('id' => $user->getId()));
        } else {
            $this->getDb()->insert('user', $userData);
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    public function delete($id)
    {
        $this->getDb()->delete('user', array('id' => $id));
    }

    protected function buildDomainObjects($row)
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setEmail($row['email']);
        $user->setUsername($row['username']);
        $user->setPassword($row['password']);

        return $user;
    }

    public function login($id, $username, $password)
    {
        $sql = "SELECT * FROM user WHERE id=? AND username=? AND password=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id, $username,$password));
        if ($row) {
            return 1;
        }
        
    }
}
