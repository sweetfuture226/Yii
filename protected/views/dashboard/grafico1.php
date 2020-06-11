<section class="panel">
    <header class="panel-heading">
        <?php echo Yii::t("smith", 'Últimos acessos'); ?>
    </header>
    <div class="panel-body">
        <br><br><br>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'log-atividade-grid',
            'dataProvider' => $logProgramaConsolidado->searchUltimosAcessos(10),
            'afterAjaxUpdate' => 'afterAjax',
            'columns' => array(
                array(
                    'name' => 'equipe',
                    'value' => 'Equipe::model()->getNome($data->usuario, $data->serial_empresa)'
                ),
                array(
                    'name'=>'usuario',
                    'value' => 'MetodosGerais::reduzirNome(Colaborador::model()->findByAttributes(array("ad"=>$data->usuario,"serial_empresa"=>$data->serial_empresa))->nomeCompleto)',
                ),
                array(
                    'name' => 'programa',
                    'value' => 'substr($data->programa,0,50)',
                ),
                'duracao',

            ),
        )); ?>
    </div>
</section>

