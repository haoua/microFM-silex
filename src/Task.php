<?php

namespace SilexApi;

class Task
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $user_id;

    /**
    * @var date
    */
    public $created_at;

    /**
     * @param string $username
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }

    /**
     * @param string $created_at
     */
    public function setCreated( $created_at ) {
        $this->created_at = $created_at;
    }

    /**
     * @param string $user_id
     */
    public function setUserId( $user_id ) {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUserId() {
        return $this->user_id;
    }
}