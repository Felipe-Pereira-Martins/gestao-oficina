<?php
/**
 * ============================================================
 * LAYOUT BASE DOS RELATÓRIOS — GESTÃO DE OFICINA
 * Template reutilizável com cabeçalho, título e período
 * ============================================================
 */

// Fallback seguro para variáveis que podem estar comentadas no config.php
$endereco_oficina  = isset($endereco_oficina) ? $endereco_oficina : '';
$rodape_relatorios = isset($rodape_relatorios) ? $rodape_relatorios : '';

// Configuração padrão das flags
if (!isset($mostrar_apuracao)) $mostrar_apuracao = true;
if (!isset($mostrar_total))    $mostrar_total    = true;
if (!isset($total_label))      $total_label      = 'Total';
if (!isset($total_valor))      $total_valor      = '0,00';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo_rel . ' ' . $subtitulo_rel ?></title>

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">

    <link rel="stylesheet" href="../css/relatorios.css">
</head>
<body>

    <!-- CABEÇALHO: Logo + Nome da Oficina + Endereço -->
    <div class="cabecalho">
        <div class="container">
            <div class="row titulos">
                <div class="col-sm-2 esquerda_float image">
                    <img src="../img/logo2.png" width="100px" alt="Logo">
                </div>
                <div class="col-sm-10 esquerda_float">
                    <h2 class="titulo"><b><?php echo strtoupper($nome_oficina) ?></b></h2>
                    <h6 class="subtitulo"><?php echo $endereco_oficina . ' Tel: ' . $telefone_oficina ?></h6>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="container">

        <!-- TÍTULO DO RELATÓRIO + DATA -->
        <div class="row">
            <div class="col-sm-8 esquerda">
                <span class="titulorel"> <?php echo $titulo_rel ?> <?php echo $subtitulo_rel ?> </span>
            </div>
            <div class="col-sm-4 direita" align="right">
                <big><small> Data: <?php echo $data_hoje; ?></small></big>
            </div>
        </div>

        <hr>

        <!-- PERÍODO DA APURAÇÃO (opcional) -->
        <?php if ($mostrar_apuracao): ?>
        <div class="row margem-superior">
            <div class="col-md-12">
                <div class="esquerda_float margem-direita50">
                    <span class=""><b> Período da Apuração </b></span>
                </div>
                <div class="esquerda_float margem-direita50">
                    <span class=""> <?php echo $apuracao ?> </span>
                </div>
            </div>
        </div>
        <hr>
        <?php endif; ?>