<?php

namespace SilexApi;

class User
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername( $username ) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail( $email ) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPasswor() {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword( $password ) {
        $this->password = $password;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname( $firstname ) {
        $this->firstname = $firstname;
    }
}