<?php
/**
 * ============================================================
 * CLIENTES — PAINEL RECEPÇÃO
 * Listagem, cadastro, edição, exclusão e visualização
 * de dados dos clientes da oficina
 * ============================================================
 */

// Verifica se a sessão está ativa e se o usuário é da recepção
@session_start();
if (@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'recep') {
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

// Define a página atual para uso nos links e ações
$pag = "clientes";

// Inclui conexão com o banco de dados e configurações globais
require_once("../conexao.php");
?>

<!-- ── BOTÕES DE AÇÃO (TOPO) ── -->
<div class="row mt-4 mb-4">
    <!-- Botão desktop: texto completo -->
    <a type="button"
       class="btn-secondary btn-sm ml-3 d-none d-md-block"
       href="index.php?pag=<?php echo $pag ?>&funcao=novo">
        Novo Cliente
    </a>
    <!-- Botão mobile: apenas ícone "+" -->
    <a type="button"
       class="btn-primary btn-sm ml-3 d-block d-sm-none"
       href="index.php?pag=<?php echo $pag ?>&funcao=novo">
        +
    </a>
</div>

<!-- ═══════════════════════════════════════════════════════
     TABELA DE CLIENTES (DataTables)
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
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <!-- Corpo da tabela -->
                <tbody>

                    <?php
                    /**
                     * Busca todos os clientes
                     * Ordenados por ID (mais recentes primeiro)
                     */
                    $query = $pdo->query("SELECT * FROM clientes order by id desc ");
                    $res   = $query->fetchAll(PDO::FETCH_ASSOC);

                    // Percorre cada cliente retornado
                    for ($i = 0; $i < @count($res); $i++) {

                        // Itera pelas chaves do registro (mantido do original, sem efeito prático)
                        foreach ($res[$i] as $key => $value) {
                        }

                        // Extrai os dados do cliente
                        $nome     = $res[$i]['nome'];
                        $cpf      = $res[$i]['cpf'];
                        $telefone = $res[$i]['telefone'];
                        $data     = $res[$i]['data'];
                        $email    = $res[$i]['email'];
                        $id       = $res[$i]['id'];

                        // Converte data de 'YYYY-MM-DD' para 'DD/MM/YYYY'
                        $data = implode('/', array_reverse(explode('-', $data)));
                    ?>

                        <!-- Linha da tabela para cada cliente -->
                        <tr>
                            <td><?php echo $nome ?></td>
                            <td><?php echo $cpf ?></td>
                            <td><?php echo $telefone ?></td>
                            <td><?php echo $email ?></td>
                            <td><?php echo $data ?></td>

                            <!-- Coluna de ações -->
                            <td>
                                <!-- Editar cliente -->
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                   class='text-primary mr-1'
                                   title='Editar Dados'>
                                    <i class='far fa-edit'></i>
                                </a>

                                <!-- Excluir cliente -->
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                   class='text-danger mr-1'
                                   title='Excluir Registro'>
                                    <i class='far fa-trash-alt'></i>
                                </a>

                                <!-- Ver endereço do cliente -->
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=endereco&id=<?php echo $id ?>"
                                   class='text-info mr-1'
                                   title='Ver Endereço'>
                                    <i class='fas fa-home'></i>
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
     MODAL: CADASTRO / EDIÇÃO DE CLIENTE
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
                // Se for edição, busca os dados do cliente para preencher o formulário
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Registro";
                    $id2    = $_GET['id'];

                    // Busca dados do cliente pelo ID
                    $query      = $pdo->query("SELECT * FROM clientes where id = '$id2' ");
                    $res        = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome2      = $res[0]['nome'];
                    $cpf2       = $res[0]['cpf'];
                    $telefone2  = $res[0]['telefone'];
                    $email2     = $res[0]['email'];
                    $endereco2  = $res[0]['endereco'];
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

                    <!-- Nome (linha completa) -->
                    <div class="form-group">
                        <label>Nome</label>
                        <input value="<?php echo @$nome2 ?>"
                               type="text"
                               class="form-control"
                               id="nome_mec"
                               name="nome_mec"
                               placeholder="Nome">
                    </div>

                    <!-- CPF e Telefone (lado a lado) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CPF</label>
                                <input value="<?php echo @$cpf2 ?>"
                                       type="text"
                                       class="form-control"
                                       id="cpf"
                                       name="cpf_mec"
                                       placeholder="CPF">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input value="<?php echo @$telefone2 ?>"
                                       type="text"
                                       class="form-control"
                                       id="telefone"
                                       name="telefone_mec"
                                       placeholder="Telefone">
                            </div>
                        </div>
                    </div>

                    <!-- Email (linha completa) -->
                    <div class="form-group">
                        <label>Email</label>
                        <input value="<?php echo @$email2 ?>"
                               type="text"
                               class="form-control"
                               id="email"
                               name="email_mec"
                               placeholder="Email">
                    </div>

                    <!-- Endereço (linha completa) -->
                    <div class="form-group">
                        <label>Endereço</label>
                        <input value="<?php echo @$endereco2 ?>"
                               type="text"
                               class="form-control"
                               id="endereco"
                               name="endereco_mec"
                               placeholder="Endereço">
                    </div>

                    <!-- Área de mensagem de retorno (validação) -->
                    <small>
                        <div id="mensagem"></div>
                    </small>

                </div>

                <!-- Rodapé do modal -->
                <div class="modal-footer">
                    <!-- Campo oculto com ID do cliente (para edição) -->
                    <input value="<?php echo @$_GET['id'] ?>"
                           type="hidden"
                           name="txtid2"
                           id="txtid2">

                    <!-- Campo oculto com CPF antigo (para verificar duplicidade) -->
                    <input value="<?php echo @$cpf2 ?>"
                           type="hidden"
                           name="antigo"
                           id="antigo">

                    <!-- Campo oculto com email antigo (para verificar duplicidade) -->
                    <input value="<?php echo @$email2 ?>"
                           type="hidden"
                           name="antigo2"
                           id="antigo2">

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
                    <!-- ID do cliente a ser excluído -->
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
     MODAL: VISUALIZAÇÃO DOS DADOS DO CLIENTE
     ═══════════════════════════════════════════════════════ -->
<div class="modal"
     id="modal-endereco"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Dados do Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php
                /**
                 * Busca os dados completos do cliente
                 * para exibição no modal de visualização
                 */
                if (@$_GET['funcao'] == 'endereco') {

                    $id2 = $_GET['id'];

                    // Busca dados do cliente pelo ID
                    $query      = $pdo->query("SELECT * FROM clientes where id = '$id2' ");
                    $res        = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome3      = $res[0]['nome'];
                    $cpf3       = $res[0]['cpf'];
                    $telefone3  = $res[0]['telefone'];
                    $email3     = $res[0]['email'];
                    $endereco3  = $res[0]['endereco'];
                }
                ?>

                <!-- Exibição dos dados completos do cliente -->
                <span><b>Nome: </b> <i><?php echo $nome3 ?></i></span><br>
                <span><b>Telefone: </b> <i><?php echo $telefone3 ?></i>
                    <span class="ml-4"><b>CPF: </b> <i><?php echo $cpf3 ?></i></span>
                </span><br>
                <span><b>Email: </b> <i><?php echo $email3 ?></i></span><br>
                <span><b>Endereço: </b> <i><?php echo $endereco3 ?></i></span><br>

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

// Visualizar endereço → abre modal com dados completos
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "endereco") {
    echo "<script>$('#modal-endereco').modal('show');</script>";
}

?>


<!-- ═══════════════════════════════════════════════════════
     AJAX: INSERÇÃO E EDIÇÃO DE CLIENTES
     Envia os dados do formulário para clientes/inserir.php
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    // Intercepta o envio do formulário
    $("#form").submit(function() {
        var pag = "<?= $pag ?>";      // Nome da página atual
        event.preventDefault();        // Impede o envio tradicional
        var formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: pag + "/inserir.php",  // Arquivo que processa a inserção/edição
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

            cache: false,        // Não armazena em cache
            contentType: false,  // Deixa o jQuery definir o contentType
            processData: false,  // Não processa os dados (FormData já faz isso)
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
     AJAX: EXCLUSÃO DE CLIENTE
     Envia o ID para clientes/excluir.php
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
     (Mantida do original - usada em outros contextos)
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    function carregarImg() {

        var target = document.getElementById('target');
        var file   = document.querySelector("input[type=file]").files[0];
        var reader = new FileReader();

        // Quando a leitura terminar, exibe a imagem no elemento target
        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);  // Converte para base64
        } else {
            target.src = "";  // Limpa se nenhum arquivo selecionado
        }
    }
</script>


<!-- ═══════════════════════════════════════════════════════
     DATATABLES: INICIALIZAÇÃO
     Ativa o plugin DataTables na tabela de clientes
     ═══════════════════════════════════════════════════════ -->
<script type="text/javascript">
    $(document).ready(function() {
        // Inicializa DataTables com ordenação desabilitada
        $('#dataTable').dataTable({
            "ordering": false
        })
    });
</script>