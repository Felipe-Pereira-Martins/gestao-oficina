<?php 

/* $nome_oficina = "Oficina Freitas"; */ // Nome da oficina
$url = "http://localhost/oficina/"; // URL base do sistema
/* $endereco_oficina = "Rua Alameda Campos, 157, Belo Horizonte"; */ // Endereço (comentado)
/* $telefone_oficina = "(31)97527-5084"; */ // Telefone da oficina
$email_adm = 'hvfadvocacia@gmail.com'; // Email do administrador
 $rodape_relatorios = "Desenvolvido por Martins Sistemas";  // Rodapé (comentado)

//VARIAVEIS DO BANCO DE DADOS LOCAL
$servidor = 'localhost'; // Servidor MySQL
$usuario = 'root'; // Usuário do banco
$senha = ''; // Senha do banco (vazia)
$banco = 'oficina'; // Nome do banco de dados


//ALGUMAS VARIAVEIS GLOBAIS

//A PARTIR DE X PRODUTOS O NIVEL DO ESTOQUE ESTARÁ BAIXO
$nivel_estoque = 5; // Quantidade mínima para alerta de estoque
$desconto_orc = 'Sim'; // Permite desconto em orçamentos
$valor_desconto = 5; //VALOR EM PORCENTAGEM, POR EXEMPLO 5 VAI SER 5 % SOBRE O VALOR FINAL
$validade_orcamento_dias = 5; //5 DIAS PARA VALIDADE DO ORÇAMENTO
$excluir_orcamento_dias = 15; //APÓS 15 DIAS O ORÇAMENTO QUE NÃO FOR APROVADO PELO CLIENTE SERÁ EXCLUÍDO
$comissao_mecanico = 'Sim';  // Se não for ter comissão no sisteema mude para não
$valor_comissao = 0.30; // COLOCAR O VALOR DA COMSISÃO COM A PORCENTAGEM MANTENDO O 0 NA FRENTEM, 0.30 COORESPONDE A 30% 
$dias_alerta_retorno = 180; //DIAS PARA AVISAR A RECEPÇÃO QUE O VEÍCULO NÃO RETORNOU AO SERVIÇO A PARTIR DE 180 DIAS
$mensagem_retorno = "Vimos que já faz um tempo que não fazemos nenhum serviço em seu veículo, estamos com uma promoção para serviços de Balanceamento, troca de óleo e vários outros, aproveite nossa promoção... "; //TEXTO DA MENSAGEM NO EMAIL PARA O CLIENTE QUANDO COMPLETAR XX DIAS QUE ELE NÃO FAZ NENHUM SERVIÇO
 ?>