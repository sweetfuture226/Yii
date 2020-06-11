<?php

/**
 * Class alertaColaboradorEmFaltaCommand
 *
 * CRON utilizado para verificar frequência de falta de captura de registos dos colaboradores e informando no painel
 * de notificações.
 */
class alertaColaboradorEmFaltaCommand extends CConsoleCommand{
    public function run(){
        $dataInicial = date('Y-m-d',strtotime('-2 days'));
        $dataFinal = date('Y-m-d',strtotime('-1 days'));


        $empresas = Empresa::model()->findAll();


        foreach($empresas as $objEmpresa){
            $sql = "select id,nome from colaborador where fk_empresa= $objEmpresa->id and status = 1 and ativo =1
                and id not in (select fk_colaborador from colaborador_has_ferias where fk_empresa = $objEmpresa->id
                and (data_inicio <= '$dataInicial' and data_fim >= '$dataFinal')) and id not in (select distinct(fk_colaborador)
                from grf_produtividade_consolidado where fk_empresa = $objEmpresa->id
                and data between '$dataInicial' and '$dataFinal' order by fk_colaborador )";
            $command = Yii::app()->getDb()->createCommand($sql);
            $colaboradores = $command->queryAll();
            foreach($colaboradores as $objCol){
                $modelColHasFalta = new ColaboradorHasFalta();
                $modelColHasFalta->fk_colaborador = $objCol['id'];
                $modelColHasFalta->fk_empresa = $objEmpresa->id;
                $modelColHasFalta->data = $dataInicial;
                if($modelColHasFalta->save()){
                    $modelColHasFalta = new ColaboradorHasFalta();
                    $modelColHasFalta->fk_colaborador = $objCol['id'];
                    $modelColHasFalta->fk_empresa = $objEmpresa->id;
                    $modelColHasFalta->data = $dataFinal;
                    $modelColHasFalta->save();
                }
            }

        }



    }
}
