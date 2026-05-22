<?php

/**
 * ============================================================
 * VEÍCULOS — PAINEL RECEPÇÃO
 * Listagem, cadastro, edição, exclusão e visualização de veículos
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links e ações
$pag = "veiculos";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

?>

<!-- ── BOTÕES DE AÇÃO (TOPO) ── -->
<div class="row mt-4 mb-4">
	<!-- Botão desktop: texto completo -->
	<a type="button"
		class="btn-secondary btn-sm ml-3 d-none d-md-block"
		href="index.php?pag=<?php echo $pag ?>&funcao=novo">
		Novo Veículo
	</a>
	<!-- Botão mobile: apenas ícone "+" -->
	<a type="button"
		class="btn-primary btn-sm ml-3 d-block d-sm-none"
		href="index.php?pag=<?php echo $pag ?>&funcao=novo">
		+
	</a>
</div>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE VEÍCULOS (DataTables)
     ═══════════════════════════════════════════════════════ -->
<div class="card shadow mb-4">

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered"
				id="dataTable"
				width="100%"
				cellspacing="0">

				<!-- Cabeçalho da tabela -->
				<thead>
					<tr>
						<th>Marca</th>
						<th>Modelo</th>
						<th>Placa</th>
						<th>Cor</th>
						<th>Cliente</th>
						<th>Data</th>
						<th>Ações</th>
					</tr>
				</thead>

				<!-- Corpo da tabela -->
				<tbody>

					<?php
					// Busca todos os veículos ordenados do mais recente para o mais antigo
					$query = $pdo->query("SELECT * FROM veiculos order by id desc ");
					$res   = $query->fetchAll(PDO::FETCH_ASSOC);

					// Percorre cada veículo retornado
					for ($i = 0; $i < @count($res); $i++) {

						// Itera pelas chaves do registro (mantido do original, sem efeito prático)
						foreach ($res[$i] as $key => $value) {
						}

						// Extrai os dados do veículo
						$marca   = $res[$i]['marca'];
						$modelo  = $res[$i]['modelo'];
						$cor     = $res[$i]['cor'];
						$data    = $res[$i]['data'];
						$placa   = $res[$i]['placa'];
						$cliente = $res[$i]['cliente'];
						$id      = $res[$i]['id'];

						// Converte data de 'YYYY-MM-DD' para 'DD/MM/YYYY'
						$data = implode('/', array_reverse(explode('-', $data)));

						// Busca o nome do cliente pelo CPF
						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli  = $res_cat[0]['nome'];
					?>

						<!-- Linha da tabela para cada veículo -->
						<tr>
							<td><?php echo $marca ?></td>
							<td><?php echo $modelo ?></td>
							<td><?php echo $placa ?></td>
							<td><?php echo $cor ?></td>
							<td><?php echo $nome_cli ?></td>
							<td><?php echo $data ?></td>

							<!-- Coluna de ações (ícones) -->
							<td>
								<!-- Editar veículo -->
								<a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
									class='text-primary mr-1'
									title='Editar Dados'>
									<i class='far fa-edit'></i>
								</a>

								<!-- Excluir veículo -->
								<a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
									class='text-danger mr-1'
									title='Excluir Registro'>
									<i class='far fa-trash-alt'></i>
								</a>

								<!-- Ver dados do veículo -->
								<a href="index.php?pag=<?php echo $pag ?>&funcao=dados&id=<?php echo $id ?>"
									class='text-info mr-1'
									title='Ver Dados do Veículo'>
									<i class='fas fa-info-circle'></i>
								</a>

								<!-- Relatório de serviços do veículo -->
								<a href="../rel/rel_os_veiculo.php?id=<?php echo $id ?>"
									target="_blank"
									class='text-success mr-1'
									title='Relatório Serviços Veículo'>
									<i class='far fa-envelope'></i>
								</a>

								<!-- Relatório do último serviço -->
								<a href="../rel/rel_ult_serv.php?id=<?php echo $id ?>"
									target="_blank"
									class='text-primary mr-1'
									title='Relatório Último Serviço'>
									<i class='far fa-envelope-open'></i>
								</a>
							</td>
						</tr>

					<?php } ?>

				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: CADASTRO / EDIÇÃO DE VEÍCULO
     ═══════════════════════════════════════════════════════ -->
<div class="modal fade"
	id="modalDados"
	tabindex="-1"
	role="dialog"
	aria-labelledby="exampleModalLabel"
	aria-hidden="true">

	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<!-- Cabeçalho do modal -->
			<div class="modal-header">
				<?php
				// Se for edição, busca os dados do veículo para preencher o formulário
				if (@$_GET['funcao'] == 'editar') {
					$titulo = "Editar Registro";
					$id2    = $_GET['id'];

					// Busca dados do veículo pelo ID
					$query     = $pdo->query("SELECT * FROM veiculos where id = '$id2' ");
					$res       = $query->fetchAll(PDO::FETCH_ASSOC);
					$marca2    = $res[0]['marca'];
					$modelo2   = $res[0]['modelo'];
					$cor2      = $res[0]['cor'];
					$placa2    = $res[0]['placa'];
					$cliente2  = $res[0]['cliente'];
					$km2       = $res[0]['km'];
					$ano2      = $res[0]['ano'];
				} else {
					// Se for novo cadastro
					$titulo = "Inserir Registro";
				}
				?>

				<h5 class="modal-title" id="exampleModalLabel"><?php echo $titulo ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- Formulário de cadastro/edição -->
			<form id="form" method="POST">
				<div class="modal-body">

					<!-- Linha 1: Cliente e Marca -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Cliente</label>
								<input value="<?php echo @$cliente2 ?>"
									type="text"
									class="form-control"
									id="cpf"
									name="cliente"
									placeholder="CPF do Cliente">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Marca</label>
								<input value="<?php echo @$marca2 ?>"
									type="text"
									class="form-control"
									id="marca"
									name="marca"
									placeholder="Marca">
							</div>
						</div>
					</div>

					<!-- Linha 2: Modelo e Cor -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Modelo</label>
								<input value="<?php echo @$modelo2 ?>"
									type="text"
									class="form-control"
									id="modelo"
									name="modelo"
									placeholder="Modelo">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Cor</label>
								<input value="<?php echo @$cor2 ?>"
									type="text"
									class="form-control"
									id="cor"
									name="cor"
									placeholder="Cor">
							</div>
						</div>
					</div>

					<!-- Placa (linha completa) -->
					<div class="form-group">
						<label>Placa</label>
						<input value="<?php echo @$placa2 ?>"
							type="text"
							class="form-control"
							id="placa"
							name="placa"
							placeholder="Placa">
					</div>

					<!-- Linha 3: Ano e KM -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Ano</label>
								<input value="<?php echo @$ano2 ?>"
									type="text"
									class="form-control"
									id="ano"
									name="ano"
									placeholder="Ano">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>KM</label>
								<input value="<?php echo @$km2 ?>"
									type="text"
									class="form-control"
									id="km"
									name="km"
									placeholder="KM Rodada">
							</div>
						</div>
					</div>

					<!-- Área de mensagem de retorno (validação) -->
					<small>
						<div id="mensagem"></div>
					</small>

				</div>

				<!-- Rodapé do modal -->
				<div class="modal-footer">
					<!-- Campo oculto com ID do veículo (para edição) -->
					<input value="<?php echo @$_GET['id'] ?>"
						type="hidden"
						name="txtid2"
						id="txtid2">

					<!-- Campo oculto com placa antiga (para verificar duplicidade) -->
					<input value="<?php echo @$placa2 ?>"
						type="hidden"
						name="antigo"
						id="antigo">

					<button type="button"
						id="btn-fechar"
						class="btn btn-secondary"
						data-dismiss="modal">
						Cancelar
					</button>
					<button type="submit"
						name="btn-salvar"
						id="btn-salvar"
						class="btn btn-primary">
						Salvar
					</button>
				</div>
			</form>

		</div>
	</div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: CONFIRMAÇÃO DE EXCLUSÃO
     ═══════════════════════════════════════════════════════ -->
<div class="modal"
	id="modal-deletar"
	tabindex="-1"
	role="dialog">

	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Excluir Registro</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<p>Deseja realmente Excluir este Registro?</p>

				<!-- Área para mensagem de retorno da exclusão -->
				<div align="center" id="mensagem_excluir" class=""></div>
			</div>

			<div class="modal-footer">
				<button type="button"
					class="btn btn-secondary"
					data-dismiss="modal"
					id="btn-cancelar-excluir">
					Cancelar
				</button>

				<form method="post">
					<!-- ID do veículo a ser excluído -->
					<input type="hidden"
						id="id"
						name="id"
						value="<?php echo @$_GET['id'] ?>"
						required>

					<button type="button"
						id="btn-deletar"
						name="btn-deletar"
						class="btn btn-danger">
						Excluir
					</button>
				</form>
			</div>

		</div>
	</div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODAL: VISUALIZAÇÃO DOS DADOS DO VEÍCULO
     ═══════════════════════════════════════════════════════ -->
<div class="modal"
	id="modal-veiculo"
	tabindex="-1"
	role="dialog">

	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Dados do Veículo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<?php
				// Se a função for 'dados', busca as informações completas do veículo
				if (@$_GET['funcao'] == 'dados') {

					$id2 = $_GET['id'];

					// Busca dados do veículo
					$query     = $pdo->query("SELECT * FROM veiculos where id = '$id2' ");
					$res       = $query->fetchAll(PDO::FETCH_ASSOC);
					$marca3    = $res[0]['marca'];
					$modelo3   = $res[0]['modelo'];
					$cor3      = $res[0]['cor'];
					$placa3    = $res[0]['placa'];
					$cliente3  = $res[0]['cliente'];
					$km3       = $res[0]['km'];
					$ano3      = $res[0]['ano'];
					$data3     = $res[0]['data'];

					// Converte data para formato brasileiro
					$data3 = implode('/', array_reverse(explode('-', $data3)));

					// Busca nome do cliente pelo CPF
					$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente3' ");
					$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
					$nome_cli2 = $res_cat[0]['nome'];
				}
				?>

				<!-- Exibição dos dados do veículo -->
				<span><b>Cliente: </b> <i><?php echo $nome_cli2 ?></i></span><br>
				<span><b>Marca: </b> <i><?php echo $marca3 ?></i>
					<span class="ml-4"><b>Modelo: </b> <i><?php echo $modelo3 ?></i></span>
				</span><br>
				<span><b>Cor: </b> <i><?php echo $cor3 ?></i>
					<span class="ml-4"><b>Placa: </b> <i><?php echo $placa3 ?></i></span>
				</span><br>
				<span><b>Ano: </b> <i><?php echo $ano3 ?></i>
					<span class="ml-4"><b>KM: </b> <i><?php echo $km3 ?></i></span>
					<span class="ml-4"><b>Data: </b> <i><?php echo $data3 ?></i></span>
				</span><br>

			</div>

		</div>
	</div>
</div>


<?php
/**
 * ============================================================
 * LÓGICA DE ABERTURA DOS MODAIS
 * Verifica o parâmetro 'funcao' na URL e exibe o modal correto
 * ============================================================
 */

// Novo cadastro → abre modal de cadastro
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "novo") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

// Edição → abre modal de edição (mesmo modal, mas com dados preenchidos)
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

// Exclusão → abre modal de confirmação de exclusão
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
	echo "<script>$('#modal-deletar').modal('show');</script>";
}

// Visualização → abre modal com dados do veículo
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "dados") {
	echo "<script>$('#modal-veiculo').modal('show');</script>";
}

?>


<!-- ═══════════════════════════════════════════════════════
     AJAX: INSERÇÃO E EDIÇÃO DE VEÍCULOS
     Envia os dados do formulário para veiculos/inserir.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
	// Intercepta o envio do formulário
	$("#form").submit(function() {
		var pag = "<?= $pag ?>"; // Nome da página atual
		event.preventDefault(); // Impede o envio tradicional
		var formData = new FormData(this); // Captura os dados do formulário

		$.ajax({
			url: pag + "/inserir.php", // Arquivo que processa a inserção/edição
			type: 'POST',
			data: formData,

			// Callback de sucesso
			success: function(mensagem) {
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso!") {
					// Fecha o modal e recarrega a página
					$('#btn-fechar').click();
					window.location = "index.php?pag=" + pag;
				} else {
					// Exibe mensagem de erro em vermelho
					$('#mensagem').addClass('text-danger')
				}
				$('#mensagem').text(mensagem)
			},

			cache: false, // Não armazena em cache
			contentType: false, // Deixa o jQuery definir o contentType
			processData: false, // Não processa os dados (FormData já faz isso)
			xhr: function() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					// Monitora progresso do upload (se houver arquivo)
					myXhr.upload.addEventListener('progress', function() {
						/* faz alguma coisa durante o progresso do upload */
					}, false);
				}
				return myXhr;
			}
		});
	});
</script>


<!-- ═══════════════════════════════════════════════════════
     AJAX: EXCLUSÃO DE VEÍCULOS
     Envia o ID para veiculos/excluir.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
	$(document).ready(function() {
		var pag = "<?= $pag ?>";

		// Clique no botão de excluir (dentro do modal de confirmação)
		$('#btn-deletar').click(function(event) {
			event.preventDefault();

			$.ajax({
				url: pag + "/excluir.php",
				method: "post",
				data: $('form').serialize(), // Serializa o ID do formulário
				dataType: "text",

				success: function(mensagem) {
					if (mensagem.trim() === 'Excluído com Sucesso!') {
						// Fecha o modal e recarrega a página
						$('#btn-cancelar-excluir').click();
						window.location = "index.php?pag=" + pag;
					}
					$('#mensagem_excluir').text(mensagem)
				},
			})
		})
	})
</script>


<!-- ═══════════════════════════════════════════════════════
     FUNÇÃO: CARREGAR IMAGEM (PRÉ-VISUALIZAÇÃO)
     Exibe preview da imagem selecionada no input file
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
	function carregarImg() {

		var target = document.getElementById('target');
		var file = document.querySelector("input[type=file]").files[0];
		var reader = new FileReader();

		// Quando a leitura terminar, exibe a imagem no elemento target
		reader.onloadend = function() {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file); // Converte para base64
		} else {
			target.src = ""; // Limpa se nenhum arquivo selecionado
		}
	}
</script>


<!-- ═══════════════════════════════════════════════════════
     DATATABLES: INICIALIZAÇÃO
     Ativa o plugin DataTables na tabela de veículos
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
	$(document).ready(function() {
		// Inicializa DataTables com ordenação desabilitada
		$('#dataTable').dataTable({
			"ordering": false
		})
	});
</script>