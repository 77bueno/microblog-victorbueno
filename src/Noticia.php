<?php

namespace Microblog;

use Exception;
use PDO;

class Noticia
{
    private int $id;
    private string $data;
    private string $titulo;
    private string $texto;
    private string $resumo;
    private string $imagem;
    private string $destaque;
    private string $termo;
    private PDO $conexao;

    /* Propriedades cujos tipos são ASSOCIADOS
    às classes já existentes. Isso permitirá
    usar recursos destas classes à partir de Noticia. */
    private Categoria $categoria;
    public Usuario $usuario;
    
    public function __construct() {
        /* Ao criar um objeto Notícia, aproveitamos
        para instanciar objetos de Usuario e Categoria */
        $this->usuario = new Usuario;
        $this->categoria = new Categoria;

        $this->conexao = Banco::conecta();
    }


    /* Métodos CRUD */
    public function inserir():void {
        $sql = "INSERT INTO noticias(
            titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id
        ) VALUES (
            :titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id
        )";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindValue(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindValue(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindValue(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindValue(":destaque", $this->destaque, PDO::PARAM_STR);

            /* Aqui, primeiro chamamos os getters de ID o Usuario e de Categoria,
            para só depois associar os valores aos parâmetros da consulta SQL.
            Isso é possível devido à associação entre as Classes. */
            $consulta->bindValue(":usario_id", $this->usuario->getId(), PDO::PARAM_INT);
            $consulta->bindValue(":categoria_id", $this->categoria->getId(), PDO::PARAM_INT);
        } catch (Exception $e) {
            die("Erro ao inserir notícia: ".$e->getMessage());
        }
    }

    public function ler():array {
        $sql = "SELECT * FROM noticias ORDER BY nome";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao ler categorias: ".$e->getMessage());
        }
        return $resultado;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): void
    {
        $this->texto = $texto;
    }

    public function getResumo(): string
    {
        return $this->resumo;
    }

    public function setResumo(string $resumo): void
    {
        $this->resumo = $resumo;
    }

    public function getImagem(): string
    {
        return $this->imagem;
    }

    public function setImagem(string $imagem): void
    {
        $this->imagem = $imagem;
    }

    public function getDestaque(): string
    {
        return $this->destaque;
    }

    public function setDestaque(string $destaque): void
    {
        $this->destaque = $destaque;
    }

    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getTermo(): string
    {
        return $this->termo;
    }

    public function setTermo(string $termo): void
    {
        $this->termo = $termo;
    }

    public function getConexao(): PDO
    {
        return $this->conexao;
    }

    public function setConexao(PDO $conexao): void
    {
        $this->conexao = $conexao;
    }
}