<?php

class User implements JsonSerializable
{

    private $id;
    private $email;
    private $password;
    private $name;

    public function __construct($id, $email, $password)
    {
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
    
    public function getName()
    {
        return $this->name;
    }
    

    public function toString()
    {
        return "Email: " . $this->email . " Password: " . $this->password . " ID: " . $this->id;
    }
}
