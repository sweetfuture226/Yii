<div class="panel minimal minimal-gray">
    <div class="panel-heading">
        <div class="panel-title">
            <a data-toggle="modal" href="#modalTopContratos">
                <?php echo Yii::t("smith", 'Top 10 contratos de'); ?>
            </a>
            <?php echo ' ' . $dateInicio . ' ' . Yii::t('smith', 'até') . ' ' . $dateFim; ?>
        </div>
            <div class="panel-options">
                <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
            </div>
    </div>
    <div class="panel-body">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'contrato-grid',
            'dataProvider' => $dataProviderContrato,
            'afterAjaxUpdate' => 'afterAjax',
            'columns' => array(
                array(
                    'header'=>Yii::t('smith','Contrato'),
                    'name'=>'contrato',
                ),
                array(
                    'header'=>Yii::t('smith','Código'),
                    'name' => 'codigo',
                ),
                array(
                    'header'=>Yii::t('smith','Duração'),
                    'name'=>'duracao',
                ),
                array(
                    'header'=>Yii::t('smith','Custo produzido'),
                    'value'=>'"R$".$data["valorContrato"]',
                ),
            ),
        )); ?>
    </div>
</div>
