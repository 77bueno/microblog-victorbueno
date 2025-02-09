<?php
use Microblog\{Usuario, Utilitarios};
require_once "../inc/cabecalho-admin.php";

/* Verificando se quem está acessando esta página tem permissão
(se o if do método abaixo for TRUE, então significa que o usuário 
NÃO É um admin e portando esta página não será autorizada para uso.) */
$sessao->verificaAcessoAdmin();

$usuario = new Usuario;
$listaDeUsuarios = $usuario->listar();


?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">
		
		<h2 class="text-center">
		Usuários <span class="badge bg-dark"><?=count($listaDeUsuarios)?></span>
		</h2>

		<p class="text-center mt-5">
			<a class="btn btn-primary" href="usuario-insere.php">
			<i class="bi bi-plus-circle"></i>	
			Inserir novo usuário</a>
		</p>
				
		<div class="table-responsive">
		
			<table class="table table-hover">
				<thead class="table-light">
					<tr>
						<th>Nome</th>
						<th>E-mail</th>
						<th>Tipo</th>
						<th class="text-center">Operações</th>
					</tr>
				</thead>

				<tbody>

				<?php foreach ($listaDeUsuarios as $dadosUsuario) { ?>
				<tr>
						
						<td> <?=$dadosUsuario['nome']?> </td>
						<td> <?=$dadosUsuario['email']?> </td>
						<td> <?=$dadosUsuario['tipo']?> </td>
						<?php ?>
				
						
						<td class="text-center">
							<a class="btn btn-warning" 
							href="usuario-atualiza.php?id=<?=$dadosUsuario['id']?>">
							<i class="bi bi-pencil"></i> Atualizar
							</a>
						
							<a class="btn btn-danger excluir" 
							href="usuario-exclui.php?id=<?=$dadosUsuario['id']?>">
							<i class="bi bi-trash"></i> Excluir
							</a>
						</td>
				</tr>
				<?php } ?>

				</tbody>                
			</table>
	</div>
		
	</article>
</div>


<?php 
require_once "../inc/rodape-admin.php";
?>

