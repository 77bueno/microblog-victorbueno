<?php 
namespace Microblog;
use Exception, PDO;

class Usuario { 
    private int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $tipo;
    private PDO $conexao;


    public function __construct() {
        $this->conexao = Banco::conecta();
    }

    /* Métodos para codificação e comparação de Senha */
    public function codificaSenha(string $senha):string {
        return password_hash($senha, PASSWORD_DEFAULT);
    }

    public function getNome(): string {
        return $this->nome;
    }
    
    public function setNome(string $nome): self {
        $this->nome = filter_var($nome, FILTER_SANITIZE_SPECIAL_CHARS);
        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }
    
    public function setEmail(string $email): self {
        $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $this;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self {
        $this->tipo = filter_var($tipo, FILTER_SANITIZE_SPECIAL_CHARS); 
        return $this;
    }
    
    public function getSenha(): string {
        return $this->senha;
    }
    
    public function setSenha(string $senha): self {
        $this->senha = filter_var($senha, FILTER_SANITIZE_SPECIAL_CHARS);
        return $this;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        return $this;
    }

    /* Métodos para rotinas de CRUD no Banco */

    // INSERT
    public function inserir():void {
        $sql = "INSERT INTO usuarios(nome, email, senha, tipo)
                VALUES(:nome, :email, :senha, :tipo)";
        
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $this->nome, PDO::PARAM_STR);
            $consulta->bindValue(":email", $this->email, PDO::PARAM_STR);
            $consulta->bindValue(":senha", $this->senha, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $this->tipo, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro ao inserir usuário: ".$erro->getMessage());
        }
    }


    // Método LER
    public function listar():array {
        $sql = "SELECT * FROM usuarios ORDER BY nome";
    
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao listar usuários: ".$erro->getMessage());
        } 
        return $resultado;
    }

    // SELECT De Usuario
    public function listarUm() : array {
        $sql = "SELECT * FROM usuarios WHERE id = :id";

        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erro ao carregar dados: " . $e->getMessage());
        }
        return $result;
    }


    // UPDATE De Usuario 
    public function atualizar():void {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, tipo = :tipo
        WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->bindValue(":nome", $this->nome, PDO::PARAM_STR);
            $consulta->bindValue(":email", $this->email, PDO::PARAM_STR);
            $consulta->bindValue(":senha", $this->senha, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $this->tipo, PDO::PARAM_STR);
            $consulta->execute();
        } catch (\Exception $e) {
            die("Erro ao atualizar usuário: ".$e->getMessage());
        }
    }
}