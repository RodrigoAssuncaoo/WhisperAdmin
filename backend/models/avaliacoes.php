<?php

class Avaliacao implements JsonSerializable
{
    private $id;
    private $idRoteiroCompras;
    private $idGrupoVisitas;
    private $avaliacaoGuia;
    private $avaliacaoViagem;
    private $comentario;

    public function __construct($id, $idRoteiroCompras, $idGrupoVisitas, $avaliacaoGuia, $avaliacaoViagem, $comentario)
    {
        $this->id = $id;
        $this->idRoteiroCompras = $idRoteiroCompras;
        $this->idGrupoVisitas = $idGrupoVisitas;
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
            'avaliacaoGuia' => $this->avaliacaoGuia,
            'avaliacaoViagem' => $this->avaliacaoViagem,
            'comentario' => $this->comentario,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id . " ID Roteiro Compras: " . $this->idRoteiroCompras . " ID Grupo Visitas: " . $this->idGrupoVisitas . " Avaliacao Guia: " . $this->avaliacaoGuia . " Avaliacao Viagem: " . $this->avaliacaoViagem . " Comentario: " . $this->comentario;
    }
}


?>