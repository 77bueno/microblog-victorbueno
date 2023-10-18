<?php
use Microblog\Usuario;
require_once "../inc/cabecalho-admin.php";

$sessao->verificaAcessoAdmin();


/* Script para carregamento */
$usuario = new Usuario;
$usuario->setId($_GET['id']);
$dadosUsuario = $usuario->listarUm();

/* Script para atualização */ 
if( isset($_POST['atualizar']) ){
	$usuario->setNome($_POST['nome']);
	$usuario->setEmail($_POST['email']);
	$usuario->setTipo($_POST['tipo']);

	/* Algoritmo geral para tratamento de senha */

	/* Se o campo senha no formulário estiver vazio, 
	significa que o usuário NÃO MUDOU A SENHA. */
	if ( empty($_POST['senha']) ) {

		/* Portanto, simplesmente repasssamos a senha já
		existente no banco ($dados['senha']) para o objeto
		através do setSenha, sem qualquer alteração. */
		$usuario->setSenha($dadosUsuario['senha']);
	} else {
		/* Caso contrário, se o usuário digitou alguma coisa no campo,
		precisaremos verificar o que foi digitado. */
		$usuario->setSenha(
			$usuario->verificaSenha($_POST['senha'], $dadosUsuario['senha'])
		);
	}

	$usuario->atualizar();
}
?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">
		
		<h2 class="text-center">
		Atualizar dados do usuário
		</h2>
				
		<form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar">

			<div class="mb-3">
				<label class="form-label" for="nome">Nome:</label>
				<input value="<?=$dadosUsuario['nome']?>" class="form-control" type="text" id="nome" name="nome" required>
			</div>

			<div class="mb-3">
				<label class="form-label" for="email">E-mail:</label>
				<input value="<?=$dadosUsuario['email']?>" class="form-control" type="email" id="email" name="email" required>
			</div>

			<div class="mb-3">
				<label class="form-label" for="senha">Senha:</label>
				<input class="form-control" type="password" id="senha" name="senha" placeholder="Preencha apenas se for alterar">
			</div>

			<div class="mb-3">
				<label class="form-label" for="tipo">Tipo:</label>
				<select class="form-select" name="tipo" id="tipo" required>
					<option value=""></option>
					<option value="editor" <?php if ( $dadosUsuario['tipo'] === 'editor') echo " selected "; ?>>Editor</option>
					<option value="admin" <?php if ( $dadosUsuario['tipo'] === 'admin') echo " selected "; ?>>Administrador</option>
				</select>
			</div>
			
			<button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
		</form>
		
	</article>
</div>


<?php 
require_once "../inc/rodape-admin.php";
?>

