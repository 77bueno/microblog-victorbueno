<?php 
namespace Microblog;
use Exception, PDO;

class Categoria {
    private int $id;
    private string $nome;
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Banco::conecta();
    }

    /* INSERT */
    public function inserir() {
        $sql = "INSERT INTO categorias(nome) VALUES (:nome)";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $this->nome, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $e) {
            die("Erro ao inserir categoria: ".$e->getMessage());
        }
    }

    /* SELECT */
    public function ler() {
        $sql = "SELECT * FROM categorias ORDER BY nome";

        try {
            
        } catch (Exception $e) {
            die("Erro ao ler categorias: ".$e->getMessage());
        }
    }


    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): self {
        $this->nome = $nome;
        return $this;
    }
}