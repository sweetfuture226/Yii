
<!--/**
* Created by PhpStorm.
 * User: joao
 * Date: 18/10/2017
 * Time: 11:20
 */-->
<!-- MODAL DE JUSTIFICATIVA 2 dias -->
<div class="modal " id="modal-questao-tecnica" abindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('smith', 'Justificativa Técnica') ?></h4>
</div>
<div class="modal-body">
    <div align="right">
        <button type="button" class = "btn btn-blue new-tecnica">
            <?= Yii::t('smith', 'Nova Justificativa')?>
        </button>
    </div>
    <input type="hidden" id="row_questao_tecnica">
    <input type="hidden" id="column_questao_tecnica">

    <p class="titulo-info"></p>
    <p>
        <?php
        $listData = QuestaoTecnica::model()->findAll();
        $data = array();
        foreach ($listData as $model)
            $data[$model->tipo] = $model->tipo;
        echo CHtml::label(Yii::t("smith", 'Justificativa'), 'Selecione o tipo');
        echo CHtml::dropDownList('questao-tecnica', null, $data, array("class" => "chzn-select", 'empty' => Yii::t("smith", 'Selecione'), "style" => "width:100%;"));
        ?>

    </p>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel"
            data-dismiss="modal"><?= Yii::t('smith', 'Fechar') ?></button>
    <button type="button"
            class="btn btn-primary saveJustificativa"><?= Yii::t('smith', 'Salvar') ?></button>
</div>
</div>
</div>
</div>

<script>
    $('.saveJustificativa').on('click', function () {
        saveTecnica();


    });

    $('.new-tecnica').on('click', function () {
        console.log("entrou");
        $('#modal-questao-tecnica').modal('hide');
        novaJustificativa();
    });

   function saveTecnica() {
        var row = $("#row_questao_tecnica").val();
        var column = $("#column_questao_tecnica").val();
       var obj = document.getElementById("questao-tecnica");
        var descricao = obj.options[obj.selectedIndex].value;
        var fk_colaborador = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('id');
        var data_inicial = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_inicial');
        var data_final = $("#table-2dias tbody tr:eq(" + row + ") td:eq(" + column + ")").find('a').first().data('data_final');
        $.ajax({
            method: "POST",
            url: baseUrl + '/ColaboradorSemProdutividade/SetJustificativa',
            data: {
                fk_colaborador: fk_colaborador,
                data_inicio: data_inicial,
                data_fim: data_final,
                descricao: descricao
            }
        }).done(function (retorno) {
            if (retorno == "success") {
                var nRow = $("#table-2dias tbody tr:eq(" + row + ")")[0];
                $('#table-2dias').dataTable().fnDeleteRow(nRow);
                $('#modal-questao-tecnica').modal('hide');
                $('#modalNotificacoes').modal('toggle');
                swal("Sucesso!", "<?php echo Yii::t('smith', 'Questão técnica inserida.') ?>", "success");
            }
            else
                swal("Error!", "<?php echo Yii::t('smith', 'Ocorreu um erro ao tentar questão técnica') ?>", "error");
        });
    }

    function novaJustificativa() {
        var campo = $(this);
        var row = campo.closest('tr').index();
        var column = campo.closest('td').index();
        //$('#justificativa_2_dias').html("");
        // $('#justificativa_2_dias').append("tipo",QuestaoTecnica::model->findAll()->tipo);

        $("#row_questao_tecnica").val(row);
        $("#column_questao_tecnica").val(column);
        $('#modal-questao-tecnica').modal('hide');

        $('#modal-nova-justificativa-tecnica').modal('show');

    }


</script>


