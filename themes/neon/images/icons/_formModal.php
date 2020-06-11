<div id="popupContact" style="float:left;<?php if ($this->title_action == "StandBy") echo "height: 320px; width: 30%; left: 495px;"; ?>">
    <a id="popupContactClose">x</a>
    <h1>Nova Atividade</h1>
    <?php $form2=$this->beginWidget('CActiveForm', array(
            'id'=>'form',
            'enableAjaxValidation'=>false,
            'htmlOptions'=>array('class'=>'mainForm valid'),
    )); ?>
    <?php 
         $model->data = date("d/m/Y"); 
         $model->usergroups_user_id = Yii::app()->user->id;
    ?> 
    <div id="flash_messagem" style="float: left;margin: 5px 11px; width: 97%"></div>

    <div class="rowElem noborder three_columns">
         <?php echo $form2->labelEx($model,'descricao'); ?>
         <div class="formBottom">
             <?php echo CHtml::textArea('Atividade[desc]',"",array('cols'=>10,'rows'=>6,'id'=>'descricao')); ?>
         </div>
         <div class="fix"></div>            
     </div>
    
    <?php if ($this->title_action != "StandBy")
    {
    ?>
    <div class="rowElem noborder three_columns">
        <?php echo $form2->labelEx($model,'data'); ?>
        <div class="formBottom">
            <?php echo CHtml::textField('Atividade[dat]',date("d/m/Y"),array('class'=>'date','size'=>60,'maxlength'=>64)); ?>
        </div>
        <div class="fix"></div>            
    </div>
    
    <div class="rowElem noborder three_columns">
         <?php 
                 echo $form2->labelEx($model,'duracao');
                 echo $form2->label($model,'(HH:MM)');
         ?>
         <div class="formBottom">
             <?php echo $form2->textField($model,'duracao',array('class' => 'duracao','size'=>60,'maxlength'=>10,'value'=>'00:00:00')); ?>
         </div>
         <div class="fix"></div>            
     </div>
    
    <div style="margin-top: 0px;float: left;width: 100%;">
        <div class="rowElem noborder three_columns" style="width:200px">
            <?php echo CHtml::label(Yii::t('smith', 'Status'),'Atividade[stats]'); ?>
            <div class="formBottom" style="width:150px">
                <?php if((Yii::app()->user->groupname=="root") || (Yii::app()->user->groupname=="admin")) {?>
                <?php   echo CHtml::dropdownlist('Atividade[stats]',"",array("Em desenvolvimento"=>"Em desenvolvimento", "avaliacao"=>"Em avaliação", "standby"=>"Stand-By", "finalizado"=>"Finalizado"),array("class" => "chzn-select", "style" => "width:140px;")); ?>
                <?php }else{ ?>
                <?php   echo CHtml::dropdownlist('Atividade[stats]',"",array("Em desenvolvimento"=>"Em desenvolvimento", "avaliacao"=>"Em avaliação", "standby"=>"Stand-By"),array("class" => "chzn-select", "style" => "width:140px;")); ?>
                <?php } ?>
            </div>
            <div class="fix"></div>            
        </div>
        
        <div class="rowElem noborder three_columns"  style="width:200px">
            <?php echo CHtml::label(Yii::t('smith', 'Prioridade'), 'Atividade[prioridade]'); #echo $form2->label($model,'prioridade'); ?>
            <div class="formBottom" style="width:150px; ">
                <?php echo CHtml::dropdownlist("Atividade[priority]","baixa",array("selecione"=>"Selecione","baixa"=>"Baixa","media"=>"Media","alta"=>"Alta"),array("class" => "chzn-select", "style" => "width:140px;")); #echo $form2->dropdownlist($model,"prioridade",array("baixa"=>"Baixa","media"=>"Media","alta"=>"Alta"),array("class" => "chzn-select", "style" => "width:140px;")); ?>
            </div>
            <div class="fix"></div>            
        </div>
        
        <div class="rowElem noborder three_columns"  style="width:200px; left:35px;">
             <?php echo CHtml::label(Yii::t('smith', 'Usuário'),'usergroups_user_id'); ?>
             <div class="formBottom" style="width:150px;">
                 <?php   if((Yii::app()->user->groupname=="root") || (Yii::app()->user->groupname=="admin"))
                            echo $form2->dropdownlist($model,'usergroups_user_id',CHtml::listData(UserGroupsUser::model()->findAll(),'id','username'),array("class" => "chzn-select", "prompt" => "Selecione", "style" => "width:120px;"));
                         else 
                         {
                            echo CHtml::textField("Atividade[usergroups_user_id]",Yii::app()->user->name,array('disabled'=>'true'));
                            echo CHtml::hiddenField("Atividade[usergroups_user_id]",Yii::app()->user->id);
                         } ?>
             </div>
             <div class="fix"></div>            
         </div>
         
        <?php } ?>
        
         <div class="rowElem noborder three_columns"  style="width:200px">
             <?php echo $form2->labelEx($model,'projeto_id'); ?>
             <div class="formBottom" style="">
                 <?php echo $form2->dropdownlist($model,'projeto_id',CHtml::listData(Projeto::model()->findAll(), 'id', 'nome'),array("class" => "chzn-select", "prompt" => "Selecione", "style" => "width:200px;")); ?>
             </div>
             <div class="fix"></div>            
         </div>
        
    <?php if ($this->title_action != "StandBy")
        echo '</div>'; ?>
        
    <div class="buttons" style="position: relative; bottom: -10px;" id="footer_form_modal">    
        <div style="float: left; ">
           <?php echo CHtml::button('Salvar +', array('class'=>'greyishBtn submitForm','id'=>'save_plus')); ?>
        </div>
        <div style="float: right; ">
           <?php echo CHtml::button('Salvar', array('class'=>'greyishBtn submitForm','id'=> ($this->title_action != "StandBy") ? 'save' : 'saveS')); ?>
        </div>
    </div>
     <?php $this->endWidget(); ?>
</div>
<div id="backgroundPopup"></div>