<?php 

class Ponto implements JsonSerializable
{
    private $id;
    private $nome;
    private $longitude;
    private $latitude;
    private $pontoInicial;
    private $pontoFinal;

    public function __construct($id, $nome, $longitude, $latitude, $pontoInicial, $pontoFinal)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->pontoInicial = $pontoInicial;
        $this->pontoFinal = $pontoFinal;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getPontoInicial()
    {
        return $this->pontoInicial;
    }

    public function getPontoFinal()
    {
        return $this->pontoFinal;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'pontoInicial' => $this->pontoInicial,
            'pontoFinal' => $this->pontoFinal,
        ];
    }

    public function toString()
    {
        return "ID: " . $this->id . " Nome: " . $this->nome . " Longitude: " . $this->longitude . " Latitude: " . $this->latitude . " Ponto Inicial: " . $this->pontoInicial . " Ponto Final: " . $this->pontoFinal;
    }
}


?>