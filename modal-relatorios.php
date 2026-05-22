<!--  Modal Rel Servicos--> <!-- Modal de relatório de serviços -->
<div class="modal fade" id="ModalRelServicos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Modal tamanho grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Relatório de Serviços</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <!-- Botão fechar -->
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="../rel/rel_servicos.php" method="POST" target="_blank"> <!-- Abre relatório em nova aba -->
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4"> <!-- Coluna 1/3 -->
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                        <div class="col-md-4"> <!-- Coluna 2/3 -->

                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal"> <!-- Data atual como padrão -->
                            </div>
                        </div>

                        <div class="col-md-4"> <!-- Coluna 3/3 -->
                            <div class="form-group">
                                <label>Concluídos</label>
                                <select class="form-control" name="status">
                                    <option value="">Todos</option> <!-- Filtro: todos -->
                                    <option value="Não">Não</option> <!-- Filtro: não concluídos -->
                                    <option value="Sim">Sim</option> <!-- Filtro: concluídos -->
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Rel Orçamntos--> <!-- Modal de relatório de orçamentos -->
<div class="modal fade" id="ModalRelOrc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Modal tamanho grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Relatório de Orçamentos</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <!-- Botão fechar -->
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="../rel/rel_orcamentos.php" method="POST" target="_blank"> <!-- Abre relatório em nova aba -->
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4"> <!-- Coluna 1/3 -->
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                        <div class="col-md-4"> <!-- Coluna 2/3 -->
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal"> <!-- Data atual como padrão -->
                            </div>
                        </div>

                        <div class="col-md-4"> <!-- Coluna 3/3 -->
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Todos</option> <!-- Filtro: todos -->
                                    <option value="Aberto">Aberto</option> <!-- Filtro: abertos -->
                                    <option value="Aprovado">Aprovado</option> <!-- Filtro: aprovados -->
                                    <option value="Concluído">Concluído</option> <!-- Filtro: concluídos -->
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Rel Movimentações--> <!-- Modal de relatório de movimentações -->
<div class="modal fade" id="ModalRelMov" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Modal tamanho grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Relatório de Movimentações</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <!-- Botão fechar -->
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="../rel/rel_mov.php" method="POST" target="_blank"> <!-- Abre relatório em nova aba -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4"> <!-- Coluna 1/3 -->
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                        <div class="col-md-4"> <!-- Coluna 2/3 -->
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal"> <!-- Data atual como padrão -->
                            </div>
                        </div>

                        <div class="col-md-4"> <!-- Coluna 3/3 -->
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Todos</option> <!-- Filtro: todos -->
                                    <option value="Entrada">Entrada</option> <!-- Filtro: entrada -->
                                    <option value="Saída">Saída</option> <!-- Filtro: saída -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Rel à Pagar--> <!-- Modal de relatório de contas a pagar -->
<div class="modal fade" id="ModalRelPagar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Modal tamanho grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contas à Pagar</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <!-- Botão fechar -->
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="../rel/rel_pagar.php" method="POST" target="_blank"> <!-- Abre relatório em nova aba -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4"> <!-- Coluna 1/3 -->
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial"> <!-- Data atual como padrão -->
                            </div>
                        </div>

                        <div class="col-md-4"> <!-- Coluna 2/3 -->
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal"> <!-- Data atual como padrão -->
                            </div>
                        </div>

                        <div class="col-md-4"> <!-- Coluna 3/3 -->
                            <div class="form-group">
                                <label>Pago</label>
                                <select class="form-control" name="status">
                                    <option value="">Todas</option> <!-- Filtro: todas -->
                                    <option value="Sim">Sim</option> <!-- Filtro: pagas -->
                                    <option value="Não">Não</option> <!-- Filtro: não pagas -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Rel à Receber--> <!-- Modal de relatório de contas a receber -->
<div class="modal fade" id="ModalRelReceber" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Modal tamanho grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contas à Receber</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <!-- Botão fechar -->
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="../rel/rel_receber.php" method="POST" target="_blank"> <!-- Abre relatório em nova aba -->
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4"> <!-- Coluna 1/3 -->
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                        <div class="col-md-4"> <!-- Coluna 2/3 -->
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal"> <!-- Data atual como padrão -->
                            </div>
                        </div>

                        <div class="col-md-4"> <!-- Coluna 3/3 -->
                            <div class="form-group">
                                <label>Pago</label>
                                <select class="form-control" name="status">
                                    <option value="">Todas</option> <!-- Filtro: todas -->
                                    <option value="Sim">Sim</option> <!-- Filtro: recebidas -->
                                    <option value="Não">Não</option> <!-- Filtro: não recebidas -->
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Rel Compras--> <!-- Modal de relatório de compras -->
<div class="modal fade" id="ModalRelCompras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Modal tamanho grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Relatório de Compras</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <!-- Botão fechar -->
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="../rel/rel_compras.php" method="POST" target="_blank"> <!-- Abre relatório em nova aba -->
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4"> <!-- Coluna 1/3 -->
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                        <div class="col-md-4"> <!-- Coluna 2/3 -->

                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Rel Vendas--> <!-- Modal de relatório de vendas -->
<div class="modal fade" id="ModalRelVendas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Modal tamanho grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Relatório de Vendas</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <!-- Botão fechar -->
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="../rel/rel_vendas.php" method="POST" target="_blank"> <!-- Abre relatório em nova aba -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4"> <!-- Coluna 1/3 -->
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                        <div class="col-md-4"> <!-- Coluna 2/3 -->

                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal"> <!-- Data atual como padrão -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>