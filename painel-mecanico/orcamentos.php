<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'mecanico'){
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "orcamentos";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];

?>

<div class="row mt-4 mb-4">
	<a type="button" class="btn-secondary btn-sm ml-3 d-none d-md-block" href="index.php?pag=<?php echo $pag ?>&funcao=novo">Abrir Orçamento</a>
	<a type="button" class="btn-primary btn-sm ml-3 d-block d-sm-none" href="index.php?pag=<?php echo $pag ?>&funcao=novo">+</a>

</div>



<!-- DataTales Example -->
<div class="card shadow mb-4">

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Cliente</th>
						<th>Veículo</th>
						<th>Valor</th>
						<th>Serviço</th>
						<th>Data</th>
						<th>Mecânico</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>

					<?php 

					$query = $pdo->query("SELECT * FROM orcamentos where mecanico = '$_SESSION[cpf_usuario]' and status = 'Aberto' order by id desc ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$cliente = $res[$i]['cliente'];
						$veiculo = $res[$i]['veiculo'];
						$descricao = $res[$i]['descricao'];
						$valor = $res[$i]['valor'];
						$servico = $res[$i]['servico'];
						$data = $res[$i]['data'];
						$data_entrega = $res[$i]['data_entrega'];
						$garantia = $res[$i]['garantia'];
						$mecanico = $res[$i]['mecanico'];
						$id = $res[$i]['id'];

						$data = implode('/', array_reverse(explode('-', $data)));
						$valor = number_format($valor, 2, ',', '.');


						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli = $res_cat[0]['nome'];
						$email_cli = $res_cat[0]['email'];

						$query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$modelo = $res_cat[0]['modelo'];
						$marca = $res_cat[0]['marca'];

						$query_cat = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_serv = $res_cat[0]['nome'];

						$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mecanico = $res_cat[0]['nome'];

						?>

						<tr>
							<td><?php echo $nome_cli ?></td>
							<td><?php echo $marca .' '.$modelo ?></td>
							<td>R$ <?php echo $valor ?></td>
							<td><?php echo $nome_serv ?></td>
							<td><?php echo $data ?></td>
							<td><?php echo $nome_mecanico ?></td>

							<td>
								<a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>" class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a>
								<a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>

								<a href="index.php?pag=<?php echo $pag ?>&funcao=produtos&id=<?php echo $id ?>" class='text-success mr-1' title='Adicionar Produtos'><i class='fab fa-product-hunt'></i></a>

								<a href="rel/rel_orcamento.php?id=<?php echo $id ?>&email=<?php echo $email_cli ?>" target="_blank" class='text-info mr-1' title='Imprimir Orçamento'><i class='far fa-file-alt'></i></a>

							</td>
						</tr>
					<?php } ?>





				</tbody>
			</table>
		</div>
	</div>
</div>





<!-- Modal -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php 
				if (@$_GET['funcao'] == 'editar') {
					$titulo = "Editar Registro";
					$id2 = $_GET['id'];

					$query = $pdo->query("SELECT * FROM orcamentos where id = '$id2' ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$cliente2 = $res[0]['cliente'];
					$veiculo2 = $res[0]['veiculo'];
					$descricao2 = $res[0]['descricao'];
					$valor2 = $res[0]['valor'];
					$servico2 = $res[0]['servico'];
					$data2 = $res[0]['data'];
					$data_entrega2 = $res[0]['data_entrega'];
					$garantia2 = $res[0]['garantia'];
					$mecanico2 = $res[0]['mecanico'];
					$obs2 = $res[0]['obs'];



				} else {
					$titulo = "Inserir Registro";

				}


				?>

				<h5 class="modal-title" id="exampleModalLabel"><?php echo $titulo ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form" method="POST">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label >CPF Cliente</label>
								<div class="row">
									<div class="col-sm-9">
										<input value="<?php echo @$cliente2 ?>" type="text" class="form-control" id="cpf" name="cliente" placeholder="CPF do Cliente">
									</div>
									<div class="col-sm-3">
										<a href="" name="btn-buscar" id="btn-buscar" class="btn btn-primary text-light"><i class="fas fa-search"></i></a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label >Veículo</label>
								<div id="div-veiculo">

								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label >Serviço</label>
								<select name="servico" class="form-control" id="servico">

									<?php 

									$query = $pdo->query("SELECT * FROM servicos order by nome asc ");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									
									for ($i=0; $i < @count($res); $i++) { 
										foreach ($res[$i] as $key => $value) {
										}
										$nome_reg = $res[$i]['nome'];
										$id_reg = $res[$i]['id'];
										?>									
										<option <?php if(@$servico2 == $id_reg){ ?> selected <?php } ?> value="<?php echo $id_reg ?>"><?php echo $nome_reg ?></option>
									<?php } ?>
									
								</select>
							</div>
						</div>

					</div>



					<div class="form-group">
						<label >Descrição</label>
						<textarea type="text" class="form-control" id="descricao" name="descricao"><?php echo @$descricao2 ?></textarea>
					</div>
					
					
					

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label >Data da Entrega</label>
								<input value="<?php echo @$data2 ?>" type="date" class="form-control" id="data_entrega" name="data_entrega" >
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label >Garantia (Somente Dias)</label>
								<input value="<?php echo @$garantia2 ?>" type="text" class="form-control" id="garantia" name="garantia" placeholder="Total de Dias Garantia">
							</div>

						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label >Valor (Mão de Obra)</label>
								<input value="<?php echo @$valor2 ?>" type="text" class="form-control" id="valor" name="valor" placeholder="Valor da Mão de Obra">
							</div>

						</div>
					</div>


					<div class="form-group">
						<label >Observações do Veículo</label>
						<textarea type="text" class="form-control" id="obs" name="obs"><?php echo @$obs2 ?></textarea>
					</div>
					

					<small>
						<div id="mensagem">

						</div>
					</small> 

				</div>



				<div class="modal-footer">



					<input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
					<input value="<?php echo @$placa2 ?>" type="hidden" name="antigo" id="antigo">
					

					<button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>






<div class="modal" id="modal-deletar" tabindex="-1" role="dialog">
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

				<div align="center" id="mensagem_excluir" class="">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-excluir">Cancelar</button>
				<form method="post">

					<input type="hidden" id="id"  name="id" value="<?php echo @$_GET['id'] ?>" required>

					<button type="button" id="btn-deletar" name="btn-deletar" class="btn btn-danger">Excluir</button>
				</form>
			</div>
		</div>
	</div>
</div>





<div class="modal" id="modal-produtos" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Selecionar Produto - <a href="index.php?pag=<?php echo $pag ?>&funcao=detalhes&id=<?php echo $_GET['id'] ?>" class="text-dark">Ver Produtos</a></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

			<div class="card shadow mb-4">

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Nome</th>
						<th>Valor Venda</th>
						<th>Estoque</th>
						<th>Imagem</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>

					<?php 

					$query = $pdo->query("SELECT * FROM produtos order by id desc ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$nome = $res[$i]['nome'];
						
						$valor_venda = $res[$i]['valor_venda'];
						$estoque = $res[$i]['estoque'];
						
						$imagem = $res[$i]['imagem'];
						$id_prod = $res[$i]['id'];

						if($estoque < $nivel_estoque){
							$cor = "text-danger";
						}else{
							$cor = "";
						}

						
						$valor_venda = number_format($valor_venda, 2, ',', '.');
			

						?>

						<tr>
							<td><?php echo $nome ?></td>
							<td>R$ <?php echo $valor_venda ?></td>
							<td><span class="<?php echo $cor ?>"><?php echo $estoque ?></span></td>
							<td><img src="../img/produtos/<?php echo $imagem ?>" width="35" ></td>

							<td>
								<a href="index.php?pag=<?php echo $pag ?>&funcao=produtos&funcao2=adicionar&id_prod=<?php echo $id_prod ?>&id=<?php echo @$_GET['id'] ?>" class='text-success mr-1' title='Selecionar Produto'><i class='fas fa-check'></i></a>
							</td>
						</tr>
					<?php } ?>





				</tbody>
			</table>
		</div>
	</div>
</div>



			</div>
			
		</div>
	</div>
</div>






<div class="modal fade " data-backdrop="static" id="modal-detalhes" tabindex="-1" role="dialog">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5 class="modal-title">Ver Produtos</h5>
				<a type="button" class="close text-light" href="index.php?pag=<?php echo $pag ?>&funcao=produtos&id=<?php echo $_GET['id'] ?>">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">

				<?php 
					$id_orc = $_GET['id'];
					
					$query = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id_orc' ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);

					$total_prod = 0;
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$prod = $res[$i]['produto'];

						$query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
						$res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
						$nome_prod = $res_pro[0]['nome'];
						$valor_prod = $res_pro[0]['valor_venda'];
						$id_prd = $res_pro[0]['id'];

						$total_prod = $valor_prod + $total_prod;

						$valor_prod = number_format($valor_prod, 2, ',', '.');
						$total_prod = number_format($total_prod, 2, ',', '.');

				 ?>

				<span><small><i><?php echo $nome_prod ?> Valor: R$ <?php echo $valor_prod ?></i></span><a href="index.php?pag=<?php echo $pag ?>&funcao=produtos&funcao2=adicionar&id_prod=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluir"><i class='far fa-trash-alt ml-1 text-danger'></i></a><br>
				<span class="text-secondary">---------------------------------------------------------------
				</span>
				</small><br>


				<?php } ?>
			

				<div align="center" id="mensagem_excluir_produto" class="">

				</div>

			</div>
			<div class="modal-footer bg-danger text-light">

				<small>Total R$ Produtos : R$ <?php echo $total_prod ?></small>
			</div>
		</div>
	</div>
</div>



<?php 

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "novo") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
	echo "<script>$('#modal-deletar').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "produtos") {
	echo "<script>$('#modal-produtos').modal('show');</script>";
}

if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionar") {
	$id_orc = $_GET['id'];
	$id_prod = $_GET['id_prod'];

	if(!isset($_GET["funcao3"])){
		$pdo->query("INSERT INTO orc_prod SET orcamento = '$id_orc', produto = '$id_prod'");
	}
	
	
	echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhes';</script>";
	
}


if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhes") {
	echo "<script>$('#modal-detalhes').modal('show');</script>";
}


if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluir") {
	$id_orc = $_GET['id'];
	$id_prod = $_GET['id_prod'];
	$pdo->query("DELETE FROM orc_prod WHERE orcamento = '$id_orc' AND produto = '$id_prod'");
}

?>




<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM OU SEM IMAGEM -->
<script type="text/javascript">
	$("#form").submit(function () {
		var pag = "<?=$pag?>";
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: pag + "/inserir.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso!") {
                    //$('#nome').val('');
                    $('#btn-fechar').click();
                    window.location = "index.php?pag="+pag;
                } else {
                	$('#mensagem').addClass('text-danger')
                }
                $('#mensagem').text(mensagem)
            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {  // Custom XMLHttpRequest
            	var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                	myXhr.upload.addEventListener('progress', function () {
                		/* faz alguma coisa durante o progresso do upload */
                	}, false);
                }
                return myXhr;
            }
        });
	});
</script>





<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function () {
		var pag = "<?=$pag?>";
		$('#btn-deletar').click(function (event) {
			event.preventDefault();
			$.ajax({
				url: pag + "/excluir.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function (mensagem) {

					if (mensagem.trim() === 'Excluído com Sucesso!') {
						$('#btn-cancelar-excluir').click();
						window.location = "index.php?pag=" + pag;
					}
					$('#mensagem_excluir').text(mensagem)

				},

			})
		})
	})
</script>






<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function () {
						
		$('#btn-buscar').click(function (event) {
			event.preventDefault();

			var pag = "<?=$pag?>";
			var funcao = "<?=$funcao?>";

			if(funcao.trim() === 'editar'){
				var veiculo = "<?=$veiculo2?>";
				var cpf = "<?=$cliente2?>";
			}else{
				var veiculo = "";
				var cpf = document.getElementById('cpf').value;
			}
			
			$.ajax({
				url: pag + "/buscar-veiculo.php",
				method: "post",
				data: {cpf, veiculo},
				dataType: "html",
				success: function (result) {

					$('#div-veiculo').html(result);

				},

			})
		})
	})
</script>




<script type="text/javascript">
	$(document).ready(function () {


		var funcao = "<?=$funcao?>";
			
			if(funcao.trim() === 'editar'){
				$('#btn-buscar').click();
			}else{
				$('#div-veiculo').text('Busque pelo CPF ao Lado');
			}


		$('#dataTable').dataTable({
			"ordering": false
		})

		$('#dataTable2').dataTable({
			"ordering": false
		})

	});
</script>



