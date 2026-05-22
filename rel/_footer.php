<?php
require_once("../conexao.php"); 

/**
 * ============================================================
 * RODAPÉ DOS RELATÓRIOS
 * Incluir no final de cada relatório
 * ============================================================
 */
?>

        <!-- ÁREA DE TOTAL (opcional) -->
        <?php if ($mostrar_total): ?>
        <hr>
        <div class="row margem-superior">
            <div class="col-md-12">
                <div class="" align="right">
                    <span class="areaTotal">
                        <b> <?php echo $total_label ?> : R$ <?php echo $total_valor ?> </b>
                    </span>
                </div>
            </div>
        </div>
        <hr>
        <?php endif; ?>

    </div>
    <!-- /container -->

    <!-- RODAPÉ DO RELATÓRIO -->
    <div class="footer">
        <p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p>
    </div>

</body>
</html> 