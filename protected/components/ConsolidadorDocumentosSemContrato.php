<?php

/**
 * Class ConsolidadorDocumentosSemContrato
 * Componente para consolidar acesso a documentos que não possuem contratos associados
 * utilizado pelo cron de consolidação de tabelas.
 */
class ConsolidadorDocumentosSemContrato {
    public static function consolidar($idEmpresa, $serial,$data) {
        try {
            $tipoEmpresa = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $idEmpresa))->tipo_empresa;
            if($tipoEmpresa == "projetos"){
                $criteria=new CDbCriteria;
                $criteria->select = 'programa,descricao,sum(TIME_TO_SEC(duracao)) as duracao,data , usuario';
                $criteria->addCondition("data like '$data'");
                $criteria->addCondition("programa in (select nome from programa_permitido_contrato)");
                $criteria->addCondition("descricao not in (SELECT documento FROM grf_projeto_consolidado where fk_empresa = $idEmpresa)");
                $criteria->addCondition("serial_empresa = '$serial'");
                $criteria->addCondition("duracao > '00:10:00'");
                $criteria->addCondition("programa not like descricao");
                $criteria->group = 'descricao';
                $documentosSemContrato = LogAtividadeConsolidado::model()->findAll($criteria);
                foreach($documentosSemContrato as $log){
                    $fkColaborador = Colaborador::model()->findByAttributes(array("fk_empresa" => $idEmpresa, "ad" => $log->usuario));
                    if(isset($fkColaborador)){
                        $consolidar = new DocumentoSemContrato();
                        $consolidar->programa = $log->programa;
                        $consolidar->documento = $log->descricao;
                        $consolidar->duracao = $log->duracao;
                        $consolidar->fk_colaborador = $fkColaborador->id;
                        $consolidar->fk_empresa = $idEmpresa;
                        $consolidar->data = $log->data;
                        if (!$consolidar->save()) {
                            print_r($consolidar->errors);
                        }
                    }
                }
            }
            else
                echo "empresa não é de projetos\n";
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}
