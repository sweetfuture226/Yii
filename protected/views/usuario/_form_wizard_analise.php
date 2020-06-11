<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'decor-tipologia-' . $index . '-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'mainForm valid'),
        ));
?>
<?php echo $form->hiddenField($model_cotacao, 'id'); ?>
<?php echo CHtml::hiddenField('DecorCotacao[wizard_passo]', $index); ?>

<p class="note"><h5><?= Yii::t('smith', 'Selecione um dos produtos abaixo para proceder com a análise das cotações. Foram analisadas') ?> <?= $index; ?> de <?= $total_tipologias; ?> <?= Yii::t('smith', 'tipologias') ?>.</h5></p><br />

<p class="note"><h5><?= Yii::t('smith', 'Tipologia atual') ?>: <?= $model_tipologia->tipologia->nome; ?>.</h5></p>

<?php echo $form->errorSummary($model_tipologia); ?>

<!-- BEGIN PRODUTOS -->


    <?php
    $i = 0;
    $tipologia_id = $model_tipologia->id;
    echo CHtml::hiddenField('DecorCotacao[tipologia_id]', $tipologia_id);
    foreach ($model_tipologia->decorTipologiaHasProdutos as $other_model) {
        ?>
        <fieldset id="div_produto_<?php echo $i . '_' . $tipologia_id; ?>" class="cotacao_produto produto produto_<?php echo $tipologia_id; ?> <?php echo ($other_model->produto_escolhido != 0) ? "produto_selecionado" : ""; ?>" onclick="selecionarProduto('<?php echo $i . '_' . $tipologia_id; ?>', '<?php echo $tipologia_id; ?>');">
            <legend><?php echo '<h5>' . $other_model->produto->nome . '</h5>'; ?></legend>
            <?php echo $form->hiddenField($other_model, '[' . $i . '_' . $tipologia_id . ']id'); ?>
            <div class="cotacao_radio"><?php echo CHtml::radioButton('DecorCotacao[' . $tipologia_id . '][produto_escolhido]', ($other_model->produto_escolhido != 0), array('value' => $i . '_' . $tipologia_id)); ?></div>

            <div class="thumbnail" style="width: 170px;">
                <? $j = 0; ?>
                <?php if (!empty($other_model->produto->fotos)) { ?>
                    <?php foreach ($other_model->produto->fotos as $foto): ?>
                        <a href="http://<?= Yii::app()->request->getServerName() . Yii::app()->getBaseUrl(); ?>/public/fotos_produtos/<?php echo $foto->arquivo_foto ?>" data-lightbox="produto_<?= $other_model->id . '_' . $other_model->produto->id; ?>" <?= ($j != 0) ? "style='display:none;'" : ""; ?>>
                            <div class="nailthumb-container">
                                <img src="http://<?= Yii::app()->request->getServerName() . Yii::app()->getBaseUrl(); ?>/public/fotos_produtos/<?php echo $foto->arquivo_foto ?>" style="height: 170px; width: 170px;"/>
                            </div>
                        </a>
                        <?php $j++;
                    endforeach;
                    ?>
    <?php } else { ?>
                    <div class="nailthumb-container">
                        <img src="http://<?= Yii::app()->request->getServerName() . Yii::app()->theme->baseUrl; ?>/images/placeholder-square.jpg"/>
                    </div>
    <?php } ?>
            </div>
            <div class="cotacao_item form-group col-lg-3">
                <?php echo $form->labelEx($other_model->produto, '[' . $i . '_' . $tipologia_id . ']fornecedor_id'); ?>
    <?php echo $form->textField($other_model->produto->fornecedor, '[' . $i . '_' . $tipologia_id . ']nome', array('disabled' => 'disabled', 'class' => 'form-control')); ?>

            </div>
            <div class="cotacao_item form-group col-lg-3">
                <?php echo $form->labelEx($other_model, '[' . $i . '_' . $tipologia_id . ']quantidade'); ?>
    <?php echo $form->textField($other_model, '[' . $i . '_' . $tipologia_id . ']quantidade', array('disabled' => 'disabled', 'onblur' => 'precoTotalProduto("' . $i . '_' . $tipologia_id . '",this.id);', 'class' => 'form-control')); ?>

            </div>
            <div class="cotacao_item form-group ">
                <?php echo CHtml::label(Yii::t('smith', 'Preço unitário enviado (R$)'), 'DecorTipologiaHasProduto[' . $i . '_' . $tipologia_id . '][preco_enviado_fornecedor]'); ?>
                <?php $valor = ($other_model->preco_enviado_fornecedor != '') ? Conta::model()->getValor($other_model->preco_enviado_fornecedor) : ''; ?>
    <?php echo CHtml::textField('DecorTipologiaHasProduto[' . $i . '_' . $tipologia_id . '][preco_enviado_fornecedor]', $valor, array('disabled' => 'disabled', 'class' => 'validate[required] valor form-control', 'onblur' => 'precoTotalProduto("' . $i . '_' . $tipologia_id . '",this.id);')); ?>

            </div>
            <div class="cotacao_item form-group col-lg-3">
                <?php echo CHtml::label(Yii::t('smith', 'Preço total (R$)'), 'DecorTipologiaHasProduto[' . $i . '_' . $tipologia_id . '][preco_total]'); ?>
    <?php echo CHtml::textField('DecorTipologiaHasProduto[' . $i . '_' . $tipologia_id . '][preco_total]', $valor, array('class' => 'validate[required] valor form-control', 'disabled' => 'disabled')); ?>

            </div>
            <div class="cotacao_item form-group col-lg-3">
                <?php echo CHtml::label(Yii::t('smith', 'Prazo de entrega'), 'DecorTipologiaHasProduto[' . $i . '_' . $tipologia_id . ']prazo_previsto_fornecedor'); ?>
    <?php echo $form->textField($other_model, '[' . $i . '_' . $tipologia_id . ']prazo_previsto_fornecedor', array('disabled' => 'disabled', 'class' => 'date validate[required] form-control')); ?>

            </div>
            <div class="cotacao_item form-group col-lg-3">
                <?php echo CHtml::label(Yii::t('smith', 'Pontuação (%)'), 'DecorTipologiaHasProduto[' . $i . '_' . $tipologia_id . '][rt_fechada]'); ?>
                <?php $valor = ($other_model->rt_fechada != '') ? Conta::model()->getValor($other_model->rt_fechada) : ''; ?>
    <?php echo CHtml::textField('DecorTipologiaHasProduto[' . $i . '_' . $tipologia_id . '][rt_fechada]', $valor, array('disabled' => 'disabled', 'class' => 'validate[required] valor form-control', 'onblur' => 'RTPercentagem("' . $i . '_' . $tipologia_id . '",this.id);')); ?>

            </div>
            <div style="clear: both"></div>
            <div class="col-lg-6 form-group">
                <?php echo $form->labelEx($other_model, '[' . $i . '_' . $tipologia_id . ']informacoes_adicionais'); ?>
    <?php echo $form->textArea($other_model, '[' . $i . '_' . $tipologia_id . ']informacoes_adicionais', array('disabled' => 'disabled', 'cols' => '185', 'rows' => '6', 'onblur' => 'precoTotalProduto("' . $i . '_' . $tipologia_id . '",this.id);', 'class' => 'form-control')); ?>

            </div>
    
    </fieldset>
    <script type="text/javascript">
        $(document).ready(function() {
            calcPrecoTotalProduto('<?= $i . '_' . $tipologia_id; ?>');
                        $(".produto").mouseover(function(){
                            $(this).addClass('produto_hover');
                        });
                        $(".produto").mouseout(function(){
                            $(this).removeClass('produto_hover');
                        });
        });
    </script>
    <?php $i++;
}$i--;
?>
<?php echo CHtml::hiddenField('cont_produto_' . $index, "$i", array("id" => 'cont_produto')); ?>


<!-- END PRODUTOS -->
<?php $this->endWidget(); ?>
