<?php

class Guia implements JsonSerializable
{
    private $id;
    private $nome;
    private $contacto;
    private $email;
    private $idiomasFalados;

    public function __construct($id, $nome, $contacto, $email, $idiomasFalados)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->contacto = $contacto;
        $this->email = $email;
        $this->idiomasFalados = $idiomasFalados;
    }

    public function getId()
    {
        return $this->id;
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

    public function getIdiomasFalados()
    {
        return $this->idiomasFalados;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'contacto' => $this->contacto,
            'email' => $this->email,
            'idiomasFalados' => $this->idiomasFalados,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id . " Nome: " . $this->nome . " Contacto: " . $this->contacto . " Email: " . $this->email . " Idiomas Falados: " . $this->idiomasFalados;
    }
}
?>