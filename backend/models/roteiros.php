<?php

class Roteiro implements JsonSerializable
{
    private $id;
    private $id_tipo_roteiro;
    private $nome;

    public function __construct($id, $id_tipo_roteiro, $nome)
    {
        $this->id = $id;
        $this->id_tipo_roteiro = $id_tipo_roteiro;
        $this->nome = $nome;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdTipoRoteiro()
    {
        return $this->id_tipo_roteiro;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_tipo_roteiro' => $this->id_tipo_roteiro,
            'nome' => $this->nome,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id .
            " Nome: " . $this->nome .
            " Tipo de Roteiro: " . $this->id_tipo_roteiro;
    }
}
?>
