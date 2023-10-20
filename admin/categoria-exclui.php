<?php
use Microblog\{Categoria, ControleDeAcesso};
require_once "../vendor/autoload.php";

$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();

$categoria = new Categoria;
$categoria->getId($_GET['id']);
$categoria->deletar();