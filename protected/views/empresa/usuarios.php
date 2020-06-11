<?php
    $this->breadcrumbs = array(
        Yii::t('smith', 'Colaboradores a mais'),
    );

    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'usuarios-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'columns' => array(
            array(
                'name' => 'nome',
                'value' => '$data->nome',
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'filter' => CHtml::listData(Empresa::model()->findAll(array('order' => 'nome ASC')), 'nome', 'nome'),
            ),
            array(
                'name' => Yii::t('smith', 'Colaboradores previstos'),
                'value' => '$data->colaboradores_previstos',
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'htmlOptions' => array('style' => 'text-align: center'),
                'filter' => false
            ),
            array(
                'name' => Yii::t('smith', 'Novos usuários'),
                'value' => 'count(Colaborador::model()->findAll(array("condition" => "fk_empresa = $data->id"))) - $data->colaboradores_previstos',
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'htmlOptions' => array('style' => 'text-align: center'),
                'filter' => false
            ),
            array(
                'name' => Yii::t('smith', 'Ativos e configurados'),
                'value' => 'count(Colaborador::model()->findAll(array("condition" => "fk_empresa = $data->id AND ativo = 1 AND status = 1")))',
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'htmlOptions' => array('style' => 'text-align: center'),
                'filter' => false
            ), array(
                'name' => Yii::t('smith', 'Ativos e não configurados'),
                'value' => 'count(Colaborador::model()->findAll(array("condition" => "fk_empresa = $data->id AND ativo = 1 AND status = 0")))',
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'htmlOptions' => array('style' => 'text-align: center'),
                'filter' => false
            ), array(
                'name' => Yii::t('smith', 'Inativos'),
                'value' => 'count(Colaborador::model()->findAll(array("condition" => "fk_empresa = $data->id AND ativo = 0")))',
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'htmlOptions' => array('style' => 'text-align: center'),
                'filter' => false
            ), array(
                'name' => Yii::t('smith', 'Total de colaboradores'),
                'value' => 'count(Colaborador::model()->findAll(array("condition" => "fk_empresa = $data->id")))',
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'htmlOptions' => array('style' => 'text-align: center'),
                'filter' => false
            ),
            array(
                'name' => Yii::t('smith', 'Última atualização'),
                'value' => array($this,'getUltimaAtualizacao'),
                'headerHtmlOptions' => array('style' => 'text-align: center'),
                'htmlOptions' => array('style' => 'text-align: center'),
                'filter' => false
            )
        ),
    ));
?>