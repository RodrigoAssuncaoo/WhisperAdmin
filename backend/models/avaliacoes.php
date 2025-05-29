<?php

class Avaliacao implements JsonSerializable
{
    private $id;
    private $idRoteiroCompras;
    private $idGrupoVisitas;
    private $user_id;
    private $avaliacaoGuia;
    private $avaliacaoViagem;
    private $comentario;

    public function __construct($id, $idRoteiroCompras, $idGrupoVisitas, $user_id, $avaliacaoGuia, $avaliacaoViagem, $comentario)
    {
        $this->id = $id;
        $this->idRoteiroCompras = $idRoteiroCompras;
        $this->idGrupoVisitas = $idGrupoVisitas;
        $this->user_id = $user_id;
        $this->avaliacaoGuia = $avaliacaoGuia;
        $this->avaliacaoViagem = $avaliacaoViagem;
        $this->comentario = $comentario;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdRoteiroCompras()
    {
        return $this->idRoteiroCompras;
    }

    public function getIdGrupoVisitas()
    {
        return $this->idGrupoVisitas;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getAvaliacaoGuia()
    {
        return $this->avaliacaoGuia;
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
            'idRoteiroCompras' => $this->idRoteiroCompras,
            'idGrupoVisitas' => $this->idGrupoVisitas,
            'user_id' => $this->user_id,
            'avaliacaoGuia' => $this->avaliacaoGuia,
            'avaliacaoViagem' => $this->avaliacaoViagem,
            'comentario' => $this->comentario,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id . 
            " | ID Roteiro Compras: " . $this->idRoteiroCompras . 
            " | ID Grupo Visitas: " . $this->idGrupoVisitas . 
            " | User ID: " . $this->user_id .
            " | Avaliação Guia: " . $this->avaliacaoGuia . 
            " | Avaliação Viagem: " . $this->avaliacaoViagem . 
            " | Comentário: " . $this->comentario;
    }
}
?>
