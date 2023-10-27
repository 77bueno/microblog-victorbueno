<?php 
namespace Microblog;

abstract class Utilitarios {
    /* Sobre o parâmetro $dados com tipo array/bool
    Quando um parâmetro pode receber tipos de dados
    diferentes de acordo com a chamada do método,
    usamos o operador | (OU) entre as opçoes de tipos. ) */
    public static function dump(array | bool | object $dados):void {
        echo "<pre>";
        var_dump($dados);
        echo "</pre>";
    }

    public static function formataData(string $data):string {
        return date("d/m/Y H:i", strtotime($data));
    }
}