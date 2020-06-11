<form class="form valid" id="topContratosGrid-form" enctype="multipart/form-data"
      action="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioIndividual" method="POST" target="_blank">
    <div class="modal fade" id="modaltopContratosGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><?= Yii::t('smith', 'Top 10 contratos') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group  col-lg-12">
                        <p><?= Yii::t('smith', 'Você deseja realmente gerar o <b>relatório de acompanhamento de contratos
</b> de ') ?><div id="codigo_obra"></div>
                    </div>
                    <?php
                        echo CHtml::hiddenField('Obra','');
                        echo CHtml::hiddenField('date_to',$dataFim);
                        echo CHtml::hiddenField('date_from',$dataInicio);
                    ?>
                    <div style="clear: both"></div>
                </div>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <button class="btn btn-info submitForm" type="button" onclick="gerarRankingProdutividade2()"><?= Yii::t('smith', 'Gerar relatório') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>

function gerarRankingProdutividade2(){
    $('#topContratosGrid-form').submit();
    $('.close').click();
}

</script>
