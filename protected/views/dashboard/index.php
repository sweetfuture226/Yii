<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/count.js', CClientScript::POS_END); ?>
<?php
$this->breadcrumbs = array(
    Yii::t("smith", 'Página Inicial'),
);

Yii::app()->clientScript->registerScript('row_view', '
    function row_view(){
        $(".odd td:not(.button-column), .even td:not(.button-column)").click(function(){
            url = $(this).parent().children().children(".view").attr("href");
            Boxy.load(url, {title:"Dados"});
        });
    }
');

Yii::app()->clientScript->registerScript('afterAjax', "
    function afterAjax(id, data) {

    }
");

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    //use the same parameters that you had set in your widget else the datepicker will be refreshed by default
    $('#datepicker_for_data').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['en'],{'dateFormat':'yy/mm/dd'}));
}
");

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('cliente-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('hide_summary_grid', "
	$('.summary').hide();
");

$date = MetodosGerais::setStartAndEndDate();

Yii::app()->clientScript->registerScript("click-grid", "
	$('#log-atividade-grid').css('cursor','pointer');
	$('#contrato-grid').css('cursor','pointer');

	$('#contrato-grid tbody tr').click(function(){
		var tr = $(this).html();
		var tds = tr.split('<td>');
		var codigo = tds[2].replace('</td>','');
		$('#codigo_obra').html('<b>'+codigo+'</b>');
		$('#Obra').val(codigo);
		$('#modaltopContratosGrid').modal();
	});

	$('#log-atividade-grid tbody tr').click(function(){
		var tr = $(this).html();
		var tds = tr.split('<td>');
		var nome = tds[2].replace('</td>','');
		$('#nome_colaborador').html('<b>'+nome+'</b>');

		$.ajax({
			url: baseUrl+'/colaborador/getIdByNomeCompleto',
			dataType: 'json',
			data: { nome: nome},
		}).done(function(data) {
			$('#colaborador_id').val(data);
			$('#modalRankingProdutividade').modal();
		});
	});
");
//MODAL CRIAR MÉTRICA FAVORITA
$this->renderPartial('//metrica/modalCriarFavorita');
//MODAL DASHBOARD RANKING PRODUTIVIDADE
$this->renderPartial('//site/modalRankingProdutividade', array('dataInicio' => $date['start'], 'dataFim' => $date['end']));
//MODAL DASHBOARD TOP CONTRATOS TITULO
$this->renderPartial('//site/modalTopContratos', array('dataInicio' => $date['start'], 'dataFim' => $date['end']));
//MODAL DASHBOARD TOP CONTRATOS GRID
$this->renderPartial('//metrica/modalTopContratosGrid', array('dataInicio' => $date['start'], 'dataFim' => $date['end']));
$notificacao_colaboradores = Notificacao::model()->findByAttributes(array('fk_empresa' => MetodosGerais::getEmpresaId(), 'tipo' => 9));
if (!empty($notificacao_colaboradores))
    //  $this->renderPartial('modalColaboradores', array('notificacao' => $notificacao_colaboradores));
?>



<?php $tipoEmpresa = EmpresaHasParametro::model()->findByAttributes(array('fk_empresa' => $idEmpresa))->tipo_empresa;
        if ($tipoEmpresa != 'projetos') { ?>
            <div class="col-lg-6 left">
                <?php $this->renderPartial('grafico1', array('logProgramaConsolidado' => $logProgramaConsolidado)); ?>
            </div>
        <?php } else {
            ?>
            <div class="col-lg-6 left">
                <?php $this->renderPartial('gridContrato', array('dataProviderContrato' => $dataProviderContrato, 'dateInicio' => $date['start'], 'dateFim' => $date['end'])); ?>
            </div>
        <?php } ?>
        <div class="col-lg-6 left-floated">
            <?php $this->renderPartial('graficoEquipe', array('dadosGraficoEquipe' => $dadosGraficoEquipe, 'dateInicio' => $date['start'], 'dateFim' => $date['end'])); ?>
        </div>

<div style="clear: both"></div>

        <div class="col-lg-6 left">
            <?php $this->renderPartial('grafico3', array('graficoPrograma' => $graficoPrograma, 'dateInicio' => $date['start'], 'dateFim' => $date['end'], 'grafico3' => $grafico3, 'extras' => $extras)); ?>
        </div>
        <div class="col-lg-6 left-floated">
            <?php $this->renderPartial('grafico4', array('ranking' => $ranking, 'idEmpresa' => $idEmpresa, 'dateInicio' => $date['start'], 'dateFim' => $date['end'])); ?>
        </div>


<?php $ntcPendentes = NotificacoesPendencias::getNotificacoes();
    //$questao = QuestaoTecnica::model()->findAll();?>

<?php
$usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
$middle = strtotime($usuario->last_login);
$newDate = date("Y-m-d", $middle);
Yii::app()->user->setState('boasVindas', TRUE);
if ((!empty($ntcPendentes)) && (Yii::app()->user->getState('boasVindas'))) {
    Yii::app()->user->setState('boasVindas', TRUE);
    $this->renderPartial('//site/modalNotificacoesPendentes', array('ntcPendentes' => $ntcPendentes));
    $this->renderPartial('//NotificacoesPendentes/modalNovoDocumento');
    $this->renderPartial('//NotificacoesPendentes/modalNovoContrato');
    $this->renderPartial('//NotificacoesPendentes/modalAtividadeExterna');
    $this->renderPartial('//NotificacoesPendentes/modalAssociaDocumento');
    $this->renderPartial('//NotificacoesPendentes/modalJustificativa2dias');
    $this->renderPartial('//questaoTecnica/modalQuestaoTecnica');
    $this->renderPartial('//questaoTecnica/modalNovaJustificativa');
    $this->renderPartial('//justificativaAusencia/modalJustificativaAusencia');
    $this->renderPartial('//justificativaAusencia/modalNovaJustificativa');
}
?>

<script>
    $(document).ready(function () {
        $('.dataTables_info').hide();
    })
</script>



