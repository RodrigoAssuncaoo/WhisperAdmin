<?php

class Roteiro implements JsonSerializable
{
    private $id;
    private $nome;
    private $tipoRoteiro;

    
    public function __construct($id, $nome, $tipoRoteiro)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->tipoRoteiro = $tipoRoteiro;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getTipoRoteiro()
    {
        return $this->tipoRoteiro;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'tipoRoteiro' => $this->tipoRoteiro,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id . " Nome: " . $this->nome . " Tipo Roteiro: " . $this->tipoRoteiro;
    }

}
?>