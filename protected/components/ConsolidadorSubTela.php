<?php

/**
 * Class ConsolidadorSubTela
 * Componente para consolidar acesso a subtela do AutoCAD
 * utilizado pelo cron de consolidação de tabelas.
 */

class ConsolidadorSubTela{
    public static function consolidar($data, $empresa,$serial) {
        try{
            $sql="SELECT * FROM smith.log_atividade where
              fk_empresa = $empresa and data like '$data'
              and programa like '%AutoCAD%' order by usuario,id DESC";
            $command = Yii::app()->getDb()->createCommand($sql);
            $registros = $command->queryAll();
            $tempo = 0;
            $flag = 0;
            $arraySubTelas = array('CAcDynInputWndControl', 'Insert', 'Hatch Pattern Palette', 'Hatch and Gradient', 'Batch Plot', 'Select Drawing File', 'Plot - Model', 'Properties', 'Text Formatting'
            , 'Enhanced Attribute Editor', 'Edit Block Definition', 'AdApplicationButton', 'Purge', 'Reference Edit', 'Selection');
            foreach ($registros as $value) {
                if (in_array($value['descricao'], $arraySubTelas)) {
                    $flag++;
                    $tempo += MetodosGerais::time_to_seconds($value['duracao']);
                }
                elseif(strpos($value['descricao'],'.dwg') && $flag>0){
                    ConsolidadorSubTela::salvarRegistro($value, $tempo, $data, $empresa, $serial);
                    $flag = 0;
                    $tempo = 0;
                }
            }
        }
        catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function salvarRegistro($registro,$tempo,$data,$empresa,$serial){
        $descricao = addslashes($registro['descricao']);
        $sql2 = "select  id , nome  FROM contrato 
                  WHERE fk_empresa = $empresa AND INSTR ('{$descricao}',codigo)>1  LIMIT 1";
        $command = Yii::app()->getDb()->createCommand($sql2);
        $projeto = $command->queryAll();

        if(isset($projeto[0]['id'])){
            $projetoConsolidado = new GrfProjetoConsolidado();
            $projetoConsolidado->documento = $registro['descricao'];
            $projetoConsolidado->duracao = $tempo;
            $projetoConsolidado->data = $data;
            $projetoConsolidado->fk_empresa = $empresa;
            $projetoConsolidado->fk_obra = $projeto[0]['id'];
            $projetoConsolidado->fk_colaborador = Colaborador::model()->findByAttributes(array("ad" => $registro['usuario'], "serial_empresa" => $serial))->id;
            if($projetoConsolidado->save()){
                $log_consolidado = new LogAtividadeConsolidado();
                $log_consolidado->usuario = $registro['usuario'];
                $log_consolidado->programa = "AutoCAD";
                $log_consolidado->descricao = $registro['descricao'];
                $log_consolidado->duracao = MetodosGerais::formataTempo($tempo);
                $log_consolidado->data = $data;
                $log_consolidado->title_completo = $registro['descricao'];
                $log_consolidado->nome_host = "subtela";
                $log_consolidado->host_domain = "subtela";
                $log_consolidado->serial_empresa = $serial;
                $log_consolidado->num_logs = 1;
                $log_consolidado->save();
            }
        }

    }

}
