<?php

class Avaliacao implements JsonSerializable
{
    private $id;
    private $user_id;
    private $avaliacaoViagem;
    private $comentario;

    public function __construct($id, $user_id, $avaliacaoViagem, $comentario)
    {
        $this->id = $id;

        $this->user_id = $user_id;
        $this->avaliacaoViagem = $avaliacaoViagem;
        $this->comentario = $comentario;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }


    public function getAvaliacaoViagem()
    {
        return $this->avaliacaoViagem;
    }

    public function getComentario()
    {
        return $this->comentario;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'avaliacaoViagem' => $this->avaliacaoViagem,
            'comentario' => $this->comentario,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id .
            " | User ID: " . $this->user_id .
            " | Avaliação Viagem: " . $this->avaliacaoViagem .
            " | Comentário: " . $this->comentario;
    }
}
?>
