<?php
use Microblog\Usuario;
require_once "../vendor/autoload.php";

$usuario = new Usuario;
$usuario->setId($_GET['id']);
$usuario->deletar();
header("location:usuarios.php");