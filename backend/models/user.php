<?php

class User implements JsonSerializable
{
    private $id;
    private $isAdmin;
    private $nome;
    private $contacto;
    private $email;
    private $token;
    private $password;
    private $created_at;
    private $updated_at;

    public function __construct($id, $isAdmin, $nome, $contacto, $email, $token, $password, $created_at, $updated_at) {
        $this->id = $id;
        $this->isAdmin = $isAdmin;
        $this->nome = $nome;
        $this->contacto = $contacto;
        $this->email = $email;
        $this->token = $token;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getContacto()
    {
        return $this->contacto;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'isAdmin' => $this->isAdmin,
            'nome' => $this->nome,
            'contacto' => $this->contacto,
            'email' => $this->email,
            'token' => $this->token,
            'password' => $this->password,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function toString()
    {
        return "ID: {$this->id} | Admin: {$this->isAdmin} | Nome: {$this->nome} | Contacto: {$this->contacto} | Email: {$this->email} | Token: {$this->token} | Password: {$this->password} | Created At: {$this->created_at} | Updated At: {$this->updated_at}";
    }
}
