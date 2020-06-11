<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipe-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('class'=>'mainForm valid', 'enctype'=>'multipart/form-data'),
)); ?>


        <p><?=Yii::t('wizard','Agora que a instalação do Smith foi concluída em todas máquinas, cadastre as equipes ou departamentos.')?></p>
        <div id="novasEquipes">
            <p><?php echo CHtml::button(Yii::t('wizard','Adicionar equipe'), array('id'=>'bt_add_equipe','class'=>'btn btn-info', 'style'=>''))?></p><br>
        <?php
        $i=1;
        if(!empty($model)){
                
                foreach ($model as $equipe){ ?>
            <div id="Equipe_<?=$i?>">
                    <div class="form-group  col-lg-4">
                        <p>
                            <?php echo $form->labelEx($equipe,'nome'); ?>
                            <?php echo $form->textField($equipe,'nome',array('size'=>60,'maxlength'=>255,'class'=>'form-control','id'=>'equipe_'.$i,'name'=>'Equipe[equipe_'.$i.']')); ?>
                        </p>
                        <p><input id="bt_rm_equipe" onclick="remover_equipe(<?=$i?>);" class="btn btn-danger"  type="button" value="<?=Yii::t('wizard','Remover equipe')?>"></p>
                     </div>
            </div>
            
                <?php $i++; }
            
        } ?>
        
        
        
             <?php echo CHtml::hiddenField('next_index', $i); ?>
            
        </div>
        

        

<?php $this->endWidget(); ?>

        <script>       
           $("#bt_add_equipe").click(function(){
               var index = parseInt($("#next_index").val());
               
               var template = '<div id="Equipe_'+index+'">\
                                <div class="form-group  col-lg-4">\
                                <p><label for="equipe">Nome</label><span class="required">*</span>\
                                <input size="60" maxlength="255" class="form-control" type="text" value="" name="Equipe[equipe_'+index+']" id="equipe_'+index+'" /></p>\
                                \<p><input id="bt_rm_equipe" onclick="remover_equipe('+index+');" class="btn btn-danger"  type="button" value="<?=Yii::t('wizard','Remover equipe')?>" /></p>\
                                </div></div>';
               
               $('#novasEquipes').append(template);
               $("#next_index").val(index+1);
               
           }
          );
  
  function remover_equipe(index){
    $("#Equipe_"+index).remove();
    
    
}
        
        </script>
