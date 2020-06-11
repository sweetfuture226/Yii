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
                <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.treeview.css">
                <!--    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css">-->
                <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/treeview/jquery.cookie.js" type="text/javascript"></script>
                <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/treeview/jquery.treeview.js" type="text/javascript"></script>
                <script type="text/javascript">
                    $(function() {
                        $("#tree").treeview({
                            collapsed: true,
                            animated: "medium",
                            persist: "location",
                        });
                    })
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
                            //for ($i = 0; $i< $tam ; $i++){
                            foreach ($documentos as $documento){
                                
                                //pre calculo de filhos
                                $num_logs = count($documento['logs']);
                                $num = 1;
                                $filhos = "";
                                $duracao_filhos = "00:00:00";
                                foreach ($documento['logs']  as $doc) {
                                    if(!empty($doc)){
                                    $duracao_formatado = sec_to_time($doc->duracao);
                                        $colaborador = Colaborador::model()->findByPk($doc->fk_colaborador)->nomeCompleto;
                                    $duracao_filhos = sum_the_time ($duracao_filhos , $duracao_formatado); 
                                    if ($num = $num_logs) {
                                        $filhos .= '<li>' . $doc->documento . ' - '.$colaborador.' <a href="#">' . $duracao_formatado . '</a> '.Helper::dateDB2View($doc->data).'</li>';
                                    } else {
                                       $filhos .= '<li class="last">' . $doc->documento  . ' - '.$colaborador.' <a href="#">' . $duracao_formatado . '</a> '.Helper::dateDB2View($doc->data).'</li>';
                                    }
                                  }
                                }
                                
                                $feitoT = sum_the_time($feitoT, $duracao_filhos);
                                
                                $linkA = '<a href="#';
                                $linkF = '">Delete</a>';
                                
                                $last = (false) ? ' last ' : '';
                                echo '<li class="expandable '.$last.'"><div class="hitarea expandable-hitarea "></div>
                                '. $documento['documento'] . ' - <a href="#"> </a>' . ' Realizado: '.$duracao_filhos. ' Documentos (' .$num_logs. ') ';

                             
                                echo '<ul class="expandir_tora" style="display: block; ">';
                                
                                echo $filhos;
                                echo '</ul></li>';
                            }
				echo '<li>Total: ' . $feitoT . ' <!-- horas -->  '.'</li>';
                            
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
                            'Finalizar',          // the link body (it will NOT be HTML-encoded.)
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
        <div style="float: right; ">
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


