<!-- Modal -->
<div id="favoritarMetricaModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Definir métrica</h4>
            </div>
            <div class="modal-body">
                <?php CHtml::beginForm('metrica/modalFavoritar','post', array('class' => 'form valid')); ?>
                <div class="form-group  col-lg-12">
                    <p>
                        <?php
                        echo CHtml::label(Yii::t('smith', 'Métrica'), 'metrica');
                        echo CHtml::dropdownlist('metrica', 'metrica', CHtml::listData(Metrica::model()->findAll(array('order' => 'titulo', 'condition' => 'favorito = "0" AND fk_empresa = :empresa', 'params'=>array(':empresa' => MetodosGerais::getEmpresaId()))), 'id', 'titulo'), array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Selecione '), "style" => "width:100%;"));
                        ?>
                    </p>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <?php echo CHtml::Button(Yii::t('smith', 'Favoritar'), array('class' => 'btn btn-info submitForm','onclick'=>'favoritarMetrica();')); ?>
                <?php CHtml::endForm(); ?>
            </div>
        </div>

    </div>
</div>

<script>
    function favoritarMetrica() {
        var metrica = $("#metrica").val();
        $.ajax({
            url: baseUrl +  "/metrica/modalFavoritar",
            type: 'POST',
            data:{metrica: metrica},
            success: function(data) {
                loadingFavoritos()
                $('#favoritarMetricaModal').modal('toggle');
                $('#metrica option[value="' + metrica + '"]').remove();
                $("#metrica").trigger("liszt:updated");
            }
        });
    }

</script>