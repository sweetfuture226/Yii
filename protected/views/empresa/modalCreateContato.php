<form id="form-contato">
    <div class="modal fade" id="createContato" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true"
         style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><?php echo Yii::t('smith', 'Adicionar Vendedor'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group  col-lg-12">
                        <p>
                            <?php echo CHtml::label('Revenda', 'revenda'); ?>
                            <?php echo CHtml::dropDownList('Contato[fk_revenda]', 'revenda_contato', CHtml::listData(Revenda::model()->findAll(), 'id', 'nome'), array('class' => 'chzn-select revendaDrop', 'id' => 'revenda_contato', 'empty' => 'Selecione')); ?>
                        </p>
                    </div>
                    <div class="form-group  col-lg-12">
                        <p>
                            <?php echo CHtml::label(Yii::t('smith', 'Nome'), 'nome'); ?>
                            <?php echo CHtml::textField('Contato[nome]', '', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                        </p>
                    </div>
                    <div class="form-group  col-lg-12">
                        <p>
                            <?php echo CHtml::label(Yii::t('smith', 'Email'), 'email'); ?>
                            <?php echo CHtml::textField('Contato[email]', '', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                        </p>
                    </div>
                    <div class="form-group  col-lg-12">
                        <p>
                            <?php echo CHtml::label(Yii::t('smith', 'Telefone'), 'nome'); ?>
                            <?php echo CHtml::textField('Contato[telefone]', '', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control telefone')); ?>
                        </p>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Fechar</button>
                    <?php echo CHtml::button('Salvar', array('class' => 'btn btn-info submitForm', 'onclick' => 'salvarContato();')); ?>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function salvarContato() {
        $.ajax({
            url: baseUrl + "/empresa/createContato",
            type: 'POST',
            data: $("#form-contato").serialize(),
            success: function (data) {
                $(':input', '#form-contato')
                    .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .removeAttr('checked')
                    .removeAttr('selected');
                $('#createContato').modal('hide');
                $("#responsavel").empty();
                $("#responsavel").append(data);
                $("#responsavel").trigger("change");
            },
        })
    }
</script>