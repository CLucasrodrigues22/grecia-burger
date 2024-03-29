<?php
include_once('header.php');
include_once('menu.php');
if (isset($_GET['tipo'])) {
	$tipo = $_GET['tipo'];
} else {
	$tipo = 'logar';
}

if (!isset($_SESSION['perfil']) && $_SESSION['perfil'] == '') {
	$msg = 'Usuário não efetuou login.';
	$alert = 'danger';
	header("Location: login.php?msg=$msg&alert=$alert");
}
?>
<main class="container sobre">
	<div class="row">
		<div class="col-12">
			<h1>Dados do cliente</h1>
			<div class="card">
				<div class="card-body">
					<p>Nome: <strong><?= $_SESSION['nome'] ?></strong></p>
					<p>E-mail: <strong><?= $_SESSION['email'] ?></strong></p>
					<p>Telefone: <strong><?= $_SESSION['telefone'] ?></strong></p>
					<p>Endereço: <strong><?= $_SESSION['logradouro'] . ' ' . $_SESSION['numero'] . ', ' . $_SESSION['logradouro'] ?></strong></p>
					<p>Cidade/Estado/CEP: <strong><?= $_SESSION['cidade'] . '/' . $_SESSION['estado'] . '/' . $_SESSION['cep'] ?></strong></p>
					<p>CEP: <strong><?= $_SESSION['cep'] ?></strong></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<h1>Dados do pedido</h1>
			<div id="tabela_pedido">
				<?php include('tabela_pedidos.php'); ?>
			</div>
		</div>
	</div>
	<form action="pagamento.php" method="post">
		<h1>Pagamento</h1>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="status">Status do pedido:</label>
					<select name="status" class="form-control" required>
						<option value="">Escolha</option>
						<option value="Iniciada">Iniciada</option>
						<option value="Pendente">Pendente</option>
						<option value="Finalizada">Finalizada</option>
					</select>
				</div>

			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="forma_pagamento">Forma de Pagamento:</label>
					<select name="forma_pagamento" class="form-control" required>
						<option value="">Escolha</option>
						<option value="Dinheiro">Dinheiro</option>
						<option value="Débito">Débito</option>
						<option value="Cartão de crédito">Cartão de crédito</option>
						<option value="Cartão">Cartão</option>
					</select>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary w-100">Finalizar pagamento</button>
				</div>
			</div>
		</div>
	</form>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</main>
<?php
include_once('footer.php');
?>

<script>
	$('.cep').on('change', function() {
		var cep = $(this).val().replace('-', '');

		$.ajax({
			url: 'https://viacep.com.br/ws/' + cep + '/json/',
			type: 'GET',
			dataType: 'json',
			beforeSend: function() {
				$('#logradouro').val('...');
				$('#bairro').val('...');
				$('#cidade').val('...');
				$('#estado').val('...');
			},
			success: function(resultado) {
				if (typeof resultado.logradouro === "undefined") {
					$('#msg-cep').show();
					$('#cep').val('');
					$('#logradouro').val('');
					$('#bairro').val('');
					$('#cidade').val('');
					$('#estado').val('');
					$('#cep').focus();
				} else {
					$('#msg-cep').hide();
					$('#logradouro').val(resultado.logradouro);
					$('#bairro').val(resultado.bairro);
					$('#cidade').val(resultado.localidade);
					$('#estado').val(resultado.uf);
					$('#numero').focus();

				}
			},
		})

	})
</script>
?>