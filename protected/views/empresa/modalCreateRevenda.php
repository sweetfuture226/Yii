<div class="modal fade" id="createRevenda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Nova Revenda</h4>
            </div>
            <div class="modal-body">
                <div class="form-group  col-lg-12">
                    <p>
                        <?php echo CHtml::label(Yii::t('smith', 'Revenda'), 'nome'); ?>
                        <?php echo CHtml::textField('nome_revenda', '', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'id' => 'nome_revenda')); ?>
                    </p>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Fechar</button>
                <?php echo CHtml::button('Salvar', array('class' => 'btn btn-info submitForm', 'onclick' => 'salvarRevenda();')); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function salvarRevenda() {
        var revenda = $("#nome_revenda").val();
        $.ajax({
            url: baseUrl + "/empresa/createRevenda",
            type: 'POST',
            data: {revenda: revenda},
            success: function (data) {
                $("#nome_revenda").val('');
                $('#createRevenda').modal('hide');
                $(".revendaDrop").empty();
                $(".revendaDrop").append(data);
                $(".revendaDrop").trigger("change");
                loadContato($('#revenda').val());
            },
        });
    }
</script>