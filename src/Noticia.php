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
    public Categoria $categoria;
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
            $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            $consulta->bindValue(":categoria_id", $this->categoria->getId(), PDO::PARAM_INT);

            $consulta->execute();
        } catch (Exception $e) {
            die("Erro ao inserir notícia: ".$e->getMessage());
        }
    }

    public function listar():array {
        /* Se o tipo de usuário logado for admin  */
        if ( $this->usuario->getTipo() === "admin" ) {
            /* Considere o SQL abaixo (pega tudo de todos) */
             $sql = "SELECT 
                    noticias.id,
                    noticias.titulo,
                    noticias.data,
                    usuarios.nome AS Autor,
                    noticias.destaque
                FROM noticias INNER JOIN usuarios
                ON noticias.usuario_id = usuarios.id
                ORDER BY data DESC"; 
        } else {
            /* Senão, considere o SQL abaixo (pega somente referente ao editar) */
            $sql = "SELECT 
                    id,
                    titulo,
                    data,
                    destaque
                    FROM noticias 
                WHERE usuario_id = :usuario_id
                ORDER BY data DESC";
        } // fim condicional

        try {
            $consulta = $this->conexao->prepare($sql);
            // Somente se NÃO for um admin, trate o parâmetro abaixo
            if ( $this->usuario->getTipo() !== "admin" ) {
                $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao listar notícia: ".$e->getMessage());
        }
        return $resultado;
    } // fim listar()

    public function listarUm():array {
        if ( $this->usuario->getTipo() === "admin" ) {
            // Carrega dados de qualquer noticia de qualquer pessoa
            $sql = "SELECT * FROM noticias WHERE id = :id";
        } else {
            // Carrega dados de qualquer noticia DELE/DELA
            $sql = "SELECT * FROM noticias WHERE id = :id AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            if ( $this->usuario->getTipo() !== "admin" ) {
                $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);                
            }
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao listar um usuário: ".$e->getMessage());
        }
        return $resultado;
    }


    public function atualizar():void {
        if ( $this->usuario->getTipo() === "admin" ) {
            // Carrega dados de qualquer noticia de qualquer pessoa
            $sql = "UPDATE noticias SET titulo = :titulo, texto = :texto, resumo = :resumo,
                    imagem = :imagem, categoria_id = :categoria_id,
                    destaque = :destaque WHERE id = :id";
        } else {
            // Carrega dados de qualquer noticia DELE/DELA
            $sql = "UPDATE noticias SET titulo = :titulo, texto = :texto, resumo = :resumo,
                    imagem = :imagem, categoria_id = :categoria_id,
                    destaque = :destaque WHERE id = :id
                    AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->bindValue(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindValue(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindValue(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindValue(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindValue(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->bindValue(":categoria_id", $this->categoria->getId(), PDO::PARAM_INT);
            
            if ($this->usuario->getTipo() !== "admin") {
                $consulta->bindValue("usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }

            $consulta->execute();
        } catch (Exception $e) {
            die("Erro ao atualizar notícia: ".$e->getMessage());
        }
    }

    public function excluir():void {
        if ( $this->usuario->getTipo() === "admin" ) {
            $sql = "DELETE FROM noticias WHERE id = :id";
        } else { 
            $sql = "DELETE FROM noticias WHERE id = :id AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            if ($this->usuario->getTipo() !== "admin") {
                $consulta->bindValue("usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }

            $consulta->execute();
        } catch (Exception $e) {
            die("Erro ao excluir notícia: ".$e->getMessage());
        }
    }


    public function upload(array $arquivo):void {
        // Definindo os tipos válidos 
        $tiposValidos = [
            "image/png",
            "image/jpeg", 
            "image/gif", 
            "image/svg+xml"
        ];

        // Verificando se o arquivo NÃO É um dos tipos válidos
        if ( !in_array($arquivo['type'], $tiposValidos) ) {
            // Alertamos o usuário e o fazemos voltar para o form.
            die("<script>
                    alert('Formato inválido!');
                    history.back();
                </script>"
            );
        }

        // Acessando APENAS o nome/extensão do arquivo
        $nome = $arquivo['name'];

        // Acessando os dados de acesso/armazenamento temporários
        $temporario = $arquivo['tmp_name'];

        // Definindo a pasta de destino das imagens no site.
        $pastaFinal = "../imagens/".$nome;

        // Movemos/enviamos da área temporária para a final/destino
        move_uploaded_file($temporario, $pastaFinal);
    }









    /* MÉTODOS DA ÁREA PÚBLICA */

    // Index.php
    public function listarDestaques():array {
        $sql = "SELECT id, titulo, resumo, imagem FROM noticias
                WHERE destaque = :destaque
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao carregar destaques: ".$e->getMessage());
        }
        return $resultado;
    }

    public function listarTodas():array {
        $sql = "SELECT id, data, titulo, resumo FROM noticias
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao carregar notícias: ".$e->getMessage());
        }
        return $resultado;
    }

    public function listarDetalhes():array {
        $sql = "SELECT 
                    noticias.id, 
                    noticias.titulo, 
                    noticias.data, 
                    noticias.texto, 
                    noticias.imagem, 
                    usuarios.nome as autor
                FROM noticias INNER JOIN usuarios
                ON noticias.usuario_id = usuarios.id
                WHERE noticias.id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao abrir a notícias: ".$e->getMessage());
        }
        return $resultado;
    }

    public function listarPorCategoria():array {
        $sql = "SELECT 
                    noticias.id, 
                    noticias.titulo, 
                    noticias.data, 
                    noticias.resumo,  
                    usuarios.nome as autor,
                    categorias.nome as categoria
                FROM noticias 
                INNER JOIN usuarios ON noticias.usuario_id = usuarios.id
                INNER JOIN categorias ON noticias.categoria_id = categorias.id
                WHERE noticias.categoria_id = :categoria_id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":categoria_id", $this->categoria->getId(), PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao carregar notícias da categoria: ".$e->getMessage());
        }
        return $resultado;
    }





    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = filter_var($titulo, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): void
    {
        $this->texto = filter_var($texto, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getResumo(): string
    {
        return $this->resumo;
    }

    public function setResumo(string $resumo): void
    {
        $this->resumo = filter_var($resumo, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    public function getImagem(): string
    {
        return $this->imagem;
    }

    public function setImagem(string $imagem): void
    {
        $this->imagem = filter_var($imagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getDestaque(): string
    {
        return $this->destaque;
    }

    public function setDestaque(string $destaque): void
    {
        $this->destaque = filter_var($destaque, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = filter_var($categoria, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = filter_var($usuario, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getTermo(): string
    {
        return $this->termo;
    }

    public function setTermo(string $termo): void
    {
        $this->termo = filter_var($termo, FILTER_SANITIZE_SPECIAL_CHARS);
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