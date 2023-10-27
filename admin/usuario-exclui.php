<?php
use Microblog\{Usuario, ControleDeAcesso};
require_once "../vendor/autoload.php";

$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();
$sessao->verificaAcessoAdmin();


$usuario = new Usuario;
$usuario->setId($_GET['id']);
$usuario->deletar();
header("location:usuarios.php");