<?php

/**
 * Class InsereEmpresaLogCommand
 *
 * CRON auxiliar para inserir o fk_empresa na tabela de log.
 */
class InsereEmpresaLogCommand extends CConsoleCommand {

    
    public function run() {
        
        $empresas = Empresa::model()->findAll();
        foreach ($empresas as $empresa){
            LogAtividade::model()->updateAll(array("fk_empresa"=>$empresa->id), array("condition"=>'serial_empresa = "'.$empresa->serial .'" AND data BETWEEN "2015-01-01" AND "2015-03-20" '));
            echo $empresa->id;
        }
    }
}

