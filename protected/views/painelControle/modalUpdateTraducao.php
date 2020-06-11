<div class="modal fade" id="modal_update_traducao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" style="width: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atualizar tradução</h4>
            </div>
            <div class="modal-body">
                <label>Literal: </label>
                <span style="font-weight: bold" id="literal"></span>

                <div id="body-panel">
                    <header style="margin-top: 10px" class="panel-heading tab-bg-dark-navy-blue ">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#ingles">Inglês</a></li>
                            <li class=""><a data-toggle="tab" href="#britanico">Britânico</a></li>
                            <li class=""><a data-toggle="tab" href="#espanhol">Espanhol</a></li>
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="ingles" class="tab-pane active">
                                <?= CHtml::label('Tradução', 'traducao'); ?>
                                <p>
                                    <?= CHtml::textArea('TraducaoLiteral[traducao_uk]', '', array('rows' => 4, 'id' => 'traducao_uk', 'class' => 'form-control')); ?>
                                </p>
                            </div>
                            <div id="britanico" class="tab-pane ">
                                <?= CHtml::label('Tradução', 'traducao'); ?>
                                <p>
                                    <?= CHtml::textArea('TraducaoLiteral[traducao_en]', '', array('rows' => 4, 'id' => 'traducao_en', 'class' => 'form-control')); ?>
                                </p>
                            </div>
                            <div id="espanhol" class="tab-pane">
                                <?= CHtml::label('Tradução', 'traducao'); ?>
                                <p>
                                    <?= CHtml::textArea('TraducaoLiteral[traducao_es]', '', array('rows' => 4, 'id' => 'traducao_es', 'class' => 'form-control')); ?>
                                </p>
                            </div>
                            <input type='hidden' id='id_literal' value="">
                            <input type='hidden' id='row_grid' value="">
                        </div>
                    </div>
                </div>
                <div class="notifications" style="margin-bottom: 5px; margin-left: 15px; width: 94.5%;">
                    <div class="alert alert-success" style="margin-bottom: 0px !important; display: none;"
                         id="sucesso_literal">
                        <?= Yii::t('smith', 'Salvo com sucesso!') ?>!
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="" data-dismiss="modal" class="btn btn-default"
                        type="button"><?php echo Yii::t('smith', 'Fechar'); ?></button>
                <button id="update_literal" class="btn btn-info"
                        type="button"><?php echo Yii::t('smith', 'Salvar'); ?></button>
                <button id="proximo_literal" class="btn btn-info"
                        type="button" onclick="proximo_literal()"><?php echo Yii::t('smith', 'Próximo'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#update_literal').click(function () {
        var id = $('#id_literal').val();
        var traducao_en = $('#traducao_en').val();
        var traducao_es = $('#traducao_es').val();
        var traducao_uk = $('#traducao_uk').val();
        $.ajax({
            'type': 'POST',
            'data': {'id': id, 'traducao_en': traducao_en, 'traducao_es': traducao_es, 'traducao_uk': traducao_uk},
            'url': baseUrl + '/painelControle/UpdateLiteral',
            success: function () {
                $("#sucesso_literal").show();
                $('#sucesso_literal').delay(2000).fadeOut('slow');
                proximo_literal();
            }
        })
    });

    function proximo_literal() {
        var row = parseInt($('#row_grid').val());
        var proximo = row + 1;
        var ultimo = $("tr:last")[0].rowIndex;
        if (proximo <= ultimo) {
            $('#id_literal').val($("tr:eq(" + proximo + ")").children(':nth-child(5)').find('a').attr('href'));
            $('#literal').html($("tr:eq(" + proximo + ")").children(':first-child').text());
            $('#traducao_en').val($("tr:eq(" + proximo + ")").children(':nth-child(2)').text());
            $('#traducao_es').val($("tr:eq(" + proximo + ")").children(':nth-child(3)').text());
            $('#traducao_uk').val($("tr:eq(" + proximo + ")").children(':nth-child(4)').text());
            $('#row_grid').val(proximo);
        }
        else
            alert('Não há mais literais nesta página!');
    }

    $('#modal_update_traducao').on('hidden.bs.modal', function () {
        $.fn.yiiGridView.update('traducao-literal-grid', {
            data: $(this).serialize()
        });
    })

</script>

