<?php 
require_once("../../conexao.php"); 

$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$cor = $_POST['cor'];
$placa = $_POST['placa'];
$ano = $_POST['ano'];
$km = $_POST['km'];
$cliente = $_POST['cliente'];

$antigo = $_POST['antigo'];
$id = $_POST['txtid2'];


//VERIFICAR SE O CLIENTE EXISTE
$query = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg == 0){
		echo 'O Cliente não está cadastrado ou o CPF está incorreto!';
		exit();
}

if($modelo == ""){
	echo 'O Modelo é Obrigatório!';
	exit();
}

if($marca == ""){
	echo 'A Marca é Obrigatória!';
	exit();
}

if($placa == ""){
	echo 'O Placa é Obrigatória!';
	exit();
}

//VERIFICAR SE O REGISTRO JÁ EXISTE NO BANCO
if($antigo != $placa){
	$query = $pdo->query("SELECT * FROM veiculos where placa = '$placa' ");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){
		echo 'O Veículo já está Cadastrado com esta placa!';
		exit();
	}
}



if($id == ""){
	$res = $pdo->prepare("INSERT INTO veiculos SET marca = :marca, modelo = :modelo, cor = :cor, placa = :placa, ano = :ano, km = :km, cliente = :cliente, data = curDate()");	

}else{
	$res = $pdo->prepare("UPDATE veiculos SET marca = :marca, modelo = :modelo, cor = :cor, placa = :placa, ano = :ano, km = :km, cliente = :cliente WHERE id = '$id'");
	
}

$res->bindValue(":marca", $marca);
$res->bindValue(":modelo", $modelo);
$res->bindValue(":cor", $cor);
$res->bindValue(":placa", $placa);
$res->bindValue(":ano", $ano);
$res->bindValue(":km", $km);
$res->bindValue(":cliente", $cliente);

$res->execute();


echo 'Salvo com Sucesso!';

?>