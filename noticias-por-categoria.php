<?php
use Microblog\Utilitarios;

require_once "inc/cabecalho.php";
$noticia->categoria->setId($_GET['id']);
$dados = $noticia->listarPorCategoria();

?>


<div class="row my-1 mx-md-n1">

    <article class="col-12">
        <?php if( count($dados) > 0 ) { ?>
            <h2>Notícias sobre <span class="badge bg-primary"><?=$dados[0]['categoria']?></span> </h2>
        <?php } else { ?>
            <h2 class="alert alert-warning text-center">
                Não há notícias desta categoria
            </h2>
        <?php } ?>
        <div class="row my-1">
            <div class="col-12 px-md-1">
                <div class="list-group">
                    <?php foreach ($dados as $itemDados) { ?>
                    <a href="noticia.php?=<?=$itemDados['id']?>" class="list-group-item list-group-item-action">
                        <h3 class="fs-6"><?=$itemDados['titulo']?></h3>
                        <p><time><?=Utilitarios::formataData($itemDados['data'])?></time> - <?=$itemDados['autor']?></p>
                        <p><?=$itemDados['resumo']?></p>
                    </a>
                    <?php } ?>
                    
                </div>
            </div>
        </div>


    </article>
    

</div>        
        

<?php 
require_once "inc/todas.php";
require_once "inc/rodape.php";
?>

