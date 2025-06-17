<?php

class User implements JsonSerializable
{
    private int $id;
    private int $role; // 1=Admin, 2=Guia, 3=Cliente
    private string $nome;
    private string $contacto;
    private string $email;
    private string $token;
    private string $password;
    private string $created_at;

    public function __construct($id, $role, $nome, $contacto, $email, $token, $password, $created_at) {
        $this->id = $id;
        $this->role = $role ?? 3; // se null, define como cliente
        $this->nome = $nome;
        $this->contacto = $contacto;
        $this->email = $email;
        $this->token = $token;
        $this->password = $password;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function getRoleName(): string
    {
        return match ($this->role) {
            1 => 'Admin',
            2 => 'Guia',
            default => 'Cliente', // inclui 3 e null tratados como cliente
        };
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getContacto(): string
    {
        return $this->contacto;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'roleName' => $this->getRoleName(),
            'nome' => $this->nome,
            'contacto' => $this->contacto,
            'email' => $this->email,
            'created_at' => $this->created_at
        ];
    }

    public function toString(): string
    {
        return "ID: {$this->id} | Role: {$this->getRoleName()} ({$this->role}) | Nome: {$this->nome} | Contacto: {$this->contacto} | Email: {$this->email} | Created At: {$this->created_at}";
    }
}
