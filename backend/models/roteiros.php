<?php

class Roteiro implements JsonSerializable
{
    private $id;
    private $nome;
    private $idPontos;

    
    public function __construct($id, $nome, $idPontos)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->idPontos = [];
    }

    public function addIdPonto($idPonto)
    {
        $this->idPontos[] = $idPonto;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'idPontos' => $this->idPontos
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id . " Nome: " . $this->nome . " Pontos: " . implode(", ", $this->idPontos);
    }

}
?>