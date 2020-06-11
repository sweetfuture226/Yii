<div class="panel minimal minimal-gray">
    <div class="panel-heading">
        <div class="panel-title">
            <?php echo CHtml::link(Yii::t("smith", 'Ranking de produtividade no período de'), array('produtividade/RelatorioRanking'), array('target' => '_blank')); ?><?php echo ' ' . $dateInicio . ' ' . Yii::t('smith', 'até') . ' ' . $dateFim; ?>
        </div>
        <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
            <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
        </div>
    </div>
    <div class="panel-body">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'log-atividade-grid',
            'dataProvider' => $ranking->ranking(MetodosGerais::dataAmericana($dateInicio),
                                                MetodosGerais::dataAmericana($dateFim),
                                                $idEmpresa, 10),
            'afterAjaxUpdate' => 'afterAjax',
            'columns' => array( 
                array(
                    'name' => 'equipe',
                    'header' => Yii::t('smith', 'Equipe'),
                    'value' => 'wordwrap($data["equipe"],9,"\n",true)'
                ),
                array(
                    'name' => 'nome',
                    'header' => Yii::t('smith', 'Nome')
                ),
                array(
                    'name' => 'produtividade',
                    'value' => 'GrfProdutividadeConsolidado::formatarProdutividade($data["produtividade"])',
                    'header' => Yii::t('smith', 'Produtividade')
                ),
                array(
                    'name' => 'meta',
                    'header' => Yii::t('smith', 'Meta')
                ),
                array(
                    'name' => 'coeficiente',
                    'header' => Yii::t('smith', 'Coeficiente')
                ),
                array(
                    'name' => 'ocioso',
                    'value' => 'GrfProdutividadeConsolidado::formatarProdutividade($data["ocioso"])',
                    'header' => Yii::t('smith', 'Ausência do computador')
                ),
            ),
        ));
        ?>
    </div>
</div>
