<?php

class TipoRoteiro implements JsonSerializable
{
    private $id;
    private $nome;
    private $duracao;
    private $preco;

    public function __construct($id, $nome, $duracao, $preco)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->duracao = $duracao;
        $this->preco = $preco;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getDuracao()
    {
        return $this->duracao;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'duracao' => $this->duracao,
            'preco' => $this->preco,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id .
            " Nome: " . $this->nome .
            " Duração: " . $this->duracao .
            " Preço: " . $this->preco . "€";
    }
}
?>
