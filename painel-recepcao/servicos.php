<?php

/**
 * ============================================================
 * SERVIÇOS (ORDENS DE SERVIÇO) — PAINEL RECEPÇÃO
 * Listagem de todas as ordens de serviço com status
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links
$pag = "servicos";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");

// Captura o parâmetro 'funcao' da URL (se existir)
$funcao = @$_GET['funcao'];

?>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE ORDENS DE SERVIÇO (DataTables)
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
						<th>Cliente</th>
						<th>Mecânico</th>
						<th>Valor Serviço</th>
						<th>Serviço</th>
						<th>Veículo</th>
						<th>Data Entrega</th>
						<th>Ações</th>
					</tr>
				</thead>

				<!-- Corpo da tabela -->
				<tbody>

					<?php
					/**
					 * Busca todas as ordens de serviço
					 * Ordenadas por data de entrega (mais próximas primeiro)
					 * e por status (não concluídas primeiro)
					 */
					$query = $pdo->query("SELECT * FROM os order by data_entrega asc, concluido asc");
					$res   = $query->fetchAll(PDO::FETCH_ASSOC);

					// Percorre cada ordem de serviço retornada
					for ($i = 0; $i < @count($res); $i++) {

						// Itera pelas chaves do registro (mantido do original, sem efeito prático)
						foreach ($res[$i] as $key => $value) {
						}

						// Extrai os dados da OS
						$cliente        = $res[$i]['cliente'];
						$veiculo        = $res[$i]['veiculo'];
						$descricao      = $res[$i]['descricao'];
						$valor          = $res[$i]['valor'];
						$valor_mao_obra = $res[$i]['valor_mao_obra'];
						$data           = $res[$i]['data'];
						$data_entrega   = $res[$i]['data_entrega'];
						$concluido      = $res[$i]['concluido'];
						$mecanico       = $res[$i]['mecanico'];
						$tipo           = $res[$i]['tipo'];
						$id             = $res[$i]['id'];

						// Converte data de 'YYYY-MM-DD' para 'DD/MM/YYYY'
						$data         = implode('/', array_reverse(explode('-', $data)));
						$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));

						// Formata valores monetários (ex: 1500.00 → 1.500,00)
						$valor          = number_format($valor, 2, ',', '.');
						$valor_mao_obra = number_format($valor_mao_obra, 2, ',', '.');

						// Busca o nome do cliente pelo CPF
						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli  = $res_cat[0]['nome'];
						$email_cli = $res_cat[0]['email'];

						// Busca o nome do mecânico pelo CPF (primeira consulta)
						$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mec  = $res_cat[0]['nome'];

						// Busca marca e modelo do veículo pelo ID
						$query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res_cat   = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$modelo    = $res_cat[0]['modelo'];
						$marca     = $res_cat[0]['marca'];

						// Busca o nome do mecânico novamente (consulta duplicada mantida do original)
						$query_cat     = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat       = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mecanico = $res_cat[0]['nome'];

						// Define a cor do status:
						// Verde = Concluído | Vermelho = Pendente
						if ($concluido == 'Sim') {
							$cor_pago = 'text-success';
						} else {
							$cor_pago = 'text-danger';
						}
					?>

						<!-- Linha da tabela para cada OS -->
						<tr>
							<!-- Cliente com indicador colorido de status -->
							<td>
								<i class='fas fa-square mr-1 <?php echo $cor_pago ?>'></i>
								<?php echo $nome_cli ?>
							</td>
							<td><?php echo $nome_mec ?></td>
							<td>R$ <?php echo $valor ?></td>
							<td><?php echo $descricao ?></td>
							<td><?php echo $marca . ' ' . $modelo ?></td>
							<td><?php echo $data_entrega ?></td>

							<!-- Coluna de ações -->
							<td>
								<!-- Link para imprimir a OS (abre em nova aba) -->
								<a href="../painel-mecanico/rel/rel_os.php?id=<?php echo $id ?>"
									target="_blank"
									class='text-info mr-1'
									title='Imprimir OS'>
									<i class='far fa-file-alt'></i>
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
     DATATABLES: INICIALIZAÇÃO
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
	$(document).ready(function() {

		// Captura a função vinda da URL (ex: 'editar')
		var funcao = "<?= $funcao ?>";

		/**
		 * Se a função for 'editar', dispara o clique no botão de busca
		 * Caso contrário, exibe texto de orientação no elemento #div-veiculo
		 */
		if (funcao.trim() === 'editar') {
			$('#btn-buscar').click();
		} else {
			$('#div-veiculo').text('Busque pelo CPF ao Lado');
		}

		// Inicializa DataTables na tabela principal (ordenação desabilitada)
		$('#dataTable').dataTable({
			"ordering": false
		});

		// Inicializa DataTables na tabela secundária (ordenação desabilitada)
		$('#dataTable2').dataTable({
			"ordering": false
		});

	});
</script>