<a id="btn_modal_detalhar" class="invisible" data-toggle="modal" href="#modal_detalhar">Dialog</a>
<form class="form valid" id="metrica-form" action="<?= Yii::app()->request->baseUrl ?>/Metrica/relatorioMetrica"
      method="post">
    <div class="modal fade" id="modal_detalhar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Pesquisar</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group  col-lg-4">
                        <p>
                            <?php echo CHtml::label('Selecione uma Opção', 'opcao'); ?>
                            <?php
                            echo CHtml::dropDownList(
                                'opcao', 'opcao', array('equipe' => 'Equipes', 'colaborador' => 'Colaboradores'), array('class' => 'chzn-select', 'prompt' => 'Selecione ')
                            );
                            ?>
                        </p>
                    </div>


                    <div class="form-group  col-lg-4" id="selec" style="display: none">
                        <p>
                            <?php echo CHtml::label('Escolha um(a)', "colaborador_id"); ?>
                            <?php echo CHtml::dropDownList('selecionado', '', array(), array('class' => 'chzn-select', 'id' => 'selecionado')); ?>
                        </p>
                    </div>

                    <div style="clear: both"></div>
                    <? $dataIni = '01/' . date('m/Y'); ?>
                    <? $dataEnd = date('d/m/Y'); ?>
                    <div class="form-group  col-lg-4">
                        <label for="date_from"><?php echo Yii::t("smith", 'Data Inicial') ?></label>

                        <p><?php echo CHtml::textField('date_from', $dataIni, array('class' => 'date form-control ')); ?></p>

                    </div>

                    <div class="form-group  col-lg-4">
                        <label for="date_to"><?php echo Yii::t("smith", 'Data Final') ?></label>

                        <p><?php echo CHtml::textField('date_to', $dataEnd, array('class' => 'date form-control ')); ?></p>

                    </div>

                </div>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <button id="btn_modal_confirm_programa" class="btn btn-success" type="button" onclick="valida();">
                        Confirmar
                    </button>
                    <button id="btn_fechar_modal_confirm_programa" data-dismiss="modal" class="btn btn-default"
                            type="button">Fechar
                    </button>

                    <input type='hidden' id='id_metrica' name='id_metrica' value="">
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function valida() {

        $('#metrica-form').submit();
        Loading.show();

    }

</script>