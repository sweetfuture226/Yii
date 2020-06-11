<?php
$this->breadcrumbs=array(
	Yii::t("smith",'Contratos')=>array('index'),
	Yii::t("smith",'Acompanhamento'),
); ?>
<?php $l = (!isset($larg)) ? 12 : $larg; ?>

<div class="grid_<?php echo $l ?>">
    <div class="block-border">

        <div class="block-content">
            <div class="widget">
<?php //aqui novo grafico
    if(isset($obra)){
        if(empty($documentos)){
            echo "<p>Não foram encontrados registros que se identificassem com o código de alguma obra.</p>";
        }else{
         ?>
                <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery.treeview.css');?>
                <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/screen.css');?>
                <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/treeview/jquery.cookie.js', CClientScript::POS_END);?>
                <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/treeview/jquery.treeview.js', CClientScript::POS_END);?>
                <script type="text/javascript">

                    function expandir_tora(){
                        $('.expandir_tora').css('display', 'block');
                    }
                    function fechar_tora(){
                        $('.expandir_tora').css('display', 'none');
                    }
                    function toogle_tora(){
                        $('.expandir_tora').toggle();
                    }
                    function redirect(data)
                    {
                        window.location(data);
                    }
                    $(function() {
                        $("#tree").treeview({
                            collapsed: true,
                            animated: "medium",
                            control:"#sidetreecontrol",
                            persist: "location"
                        });
                        console.log("oi!");
                    });

                    $(document).ready(function(){
                        $('.expandir_tora').css('display', 'none');
                        console.log($('.expandir_tora'));
                        console.log("oi");
                    });
                </script>

                <div id="sidetree" style="font-size: 14px">
                    <div class="treeheader">&nbsp;</div>
                    <div id="sidetreecontrol"><a href="#" onclick="fechar_tora()">Fechar Todos</a> | <a href="#" onclick="expandir_tora()">Expandir Todos</a> |


                    </div>

                    <ul id="tree" class="treeview">
                        <?php
                            $tam  =  count($documentos );
                            $previstoT = '00:00:00';
                            $feitoT = '00:00:00';
                            for ($i = 0; $i< $tam ; $i++){

                                //pre calculo de filhos
                                $num_logs = count($documentos[$i]['logs'][0]);
                                $previstoT = sum_the_time($previstoT, $documentos[$i]['previsto']);
                                $num = 1;
                                $filhos = "";
                                $duracao_filhos = "00:00:00";
                                foreach ($documentos[$i]['logs'][0]  as $doc) {
                                    //$duracao_documento = time_to_seconds($doc->duracao);
                                    $duracao_formatado = sec_to_time($doc->duracao);

                                    $duracao_filhos = sum_the_time ($duracao_filhos , $duracao_formatado);
                                    if ($num = $num_logs) {
                                        $filhos .= '<li>' . $doc->documento . ' - ' . Colaborador::model()->findByPk($doc->fk_colaborador)->nomeCompleto . ' <a href="#">' . $duracao_formatado . '</a> ' . Helper::dateDB2View($doc->data) . '</li>';
                                    } else {
                                        $filhos .= '<li class="last">' . $doc->documento . ' - ' . Colaborador::model()->findByPk($doc->fk_colaborador)->nomeCompleto . ' <a href="#">' . $duracao_formatado . '</a> ' . Helper::dateDB2View($doc->data) . '</li>';
                                    }
                                }

                                $feitoT = sum_the_time($feitoT, $duracao_filhos);

                                $linkA = '<a href="#';
                                $linkF = '">Delete</a>';

                                $last = (false) ? ' last ' : '';
			//d($i);
                                if ($duracao_filhos == "00:00:00"){
                                    $percentage = '0';
							}else{
									$tempo1sec = time_to_seconds($documentos[$i]['previsto']);
									$tempo2sec = time_to_seconds($duracao_filhos);
                                     $percentage = round(($tempo2sec*100)/$tempo1sec, 2);
									 }
                                //d($percentage);
                                echo '<li class="expandable '.$last.'"><div class="hitarea expandable-hitarea "></div>
                                '. $documentos[$i]['documento'] . ' - <a href="#"> </a> Previsto: ' . $documentos[$i]['previsto'] . ' Realizado: '.$duracao_filhos. ' Porcentagem: '. $percentage . '%  Documentos (' .$num_logs. ') ';

                              echo CHtml::link('Editar',Yii::app()->getBaseUrl(true).'/documento/atualizar/'.$documentos[$i]['documento_id']).'  ';
                              echo CHtml::link('Deletar',Yii::app()->getBaseUrl(true).'/documento/deletar/'.$documentos[$i]['documento_id'],array('confirm' => 'Deseja realmente excluir este documento?'));

                        if(!$documentos[$i]['finalizado'] && (Yii::app()->user->groupName == "coordenador"||Yii::app()->user->groupName == "empresa")) {
                            echo CHtml::ajaxlink(
                            ' Finalizar',          // the link body (it will NOT be HTML-encoded.)
                            array('documento/close/'.$documentos[$i]['documento_id']), // the URL for the AJAX request. If empty, it is assumed to be the current URL.
                            array(
                                'data'=>$documentos[$i]['documento_id'],
                                'datatype'=>'text',
                                'success' => "js:function(){window.location='" . Yii::app()->getBaseUrl(true) . "/contrato/andamentoObra'}",
                                )
                            );
                        }
                        elseif ($documentos[$i]['finalizado'] && (Yii::app()->user->groupName == "coordenador"||Yii::app()->user->groupName == "empresa"))
                            echo ' - Finalizado';


                                //$ultimo = count($filhos[$id]['filhos']) - 1;
                                echo '<ul class="expandir_tora" style="display: block; ">';

                                echo $filhos;
                                echo '</ul></li>';
                            }
//                            $feitoT = 10; Teste do calculo.
				echo '<li>Total: ' . $feitoT . ' <!-- horas --> de ' . $previstoT. ' (';
                            $feitoT = time_to_seconds($feitoT);
                            $previstoT = time_to_seconds($previstoT);
                            if ($feitoT != 0)
                                echo str_replace('.',',',round(((($feitoT*100)/$previstoT)),2));
                            else
                                echo '0';
                            echo '%)</li>';
                        //expandable collapsable
                        ?>

                    </ul>
                </div>
                <?php
        }
        ?>
                <div class="carlaila" style="float: left; margin-top: 10px; margin-left: 10px;">
                <?php
                        if(!$obra->finalizada) {
                            echo CHtml::ajaxlink(
                            'Finalizar contrato ',          // the link body (it will NOT be HTML-encoded.)
                                array('contrato/close/' . $obra->id), // the URL for the AJAX request. If empty, it is assumed to be the current URL.
                            array(
                                'data'=>$obra->id,
                                'datatype'=>'text',
                                'success' => "js:function(){window.location='" . Yii::app()->getBaseUrl(true) . "/contrato/andamentoObra'}",
                                )
                            );
                        }
                    ?>
                    </div>

             <div class="clear"></div>

            <?php

    }else{
         $form=$this->beginWidget('CActiveForm', array(
            'id'=>'log-atividade-form',
            'enableAjaxValidation'=>false,
            'htmlOptions'=>array('class'=>'form valid'),
        ));
        ?>

        <div class="form-group  col-lg-4">
            <p>
            <?php
                $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
                $condicao = ' fk_empresa = '.$user->fk_empresa;
                echo CHtml::label(Yii::t('smith', 'Obra'),'Obra');
            echo CHtml::dropDownList('Obra', null, CHtml::listData(Contrato::model()->findAll(array('order' => 'nome', 'condition' => $condicao)), 'codigo', 'nome'), array("class" => "chzn-select", "style" => "width:100%;"));
            ?>
            </p>
        </div>
        <div style="clear: both"></div>
<div class="buttons">
    <div style="float: right; margin-bottom: 15px ">
            <?php echo CHtml::submitButton('Pesquisar', array('class'=>'btn btn-info submitForm','id'=>'btn_enviar')); ?>
        </div>
    </div>

<?php $this->endWidget();
    }


    function sec_to_time($seconds){

        $time = gmdate("H:i:s",$seconds);
//
        return $time;
                    }










  function sum_the_time($time1, $time2) {
  $times = array($time1, $time2);
  $seconds = 0;
  foreach ($times as $time)
  {
    list($hour,$minute,$second) = explode(':', $time);
    $seconds += $hour*3600;
    $seconds += $minute*60;
    $seconds += $second;
  }
  $hours = floor($seconds/3600);
  $seconds -= $hours*3600;
  $minutes  = floor($seconds/60);
  $seconds -= $minutes*60;
  // return "{$hours}:{$minutes}:{$seconds}";
  return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Thanks to Patrick
}
 function time_to_seconds($time) {
  $seconds = 0;
    list($hour,$minute,$second) = explode(':', $time);
    $seconds += $hour*3600;
    $seconds += $minute*60;
    $seconds += $second;
	return $seconds;
}
?>

            </div>
        </div>
    </div>
</div>
