<?php

/**
 * Class ConsolidadorColaborador
 * Componente para consolidar produtividade dos colaboradores
 * utilizado pelo cron de consolidação de tabelas.
 */

class ConsolidadorColaborador {

    public static function consolidar($data, $idEmpresa,$serial){
        try {
            ConsolidadorColaborador::consolidarColaborador($data, $idEmpresa,$serial);
            ConsolidadorColaborador::consolidarHoraExtra($data, $idEmpresa,$serial);
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function consolidarColaborador($data, $idEmpresa,$serial) {
        try {
            $empresa = Empresa::model()->findByPk($idEmpresa);
            $arrayConsolidado = array();

            $sql = "SELECT log.data as data , date_sub(min(log.data_hora_servidor), interval 3 hour) as hora_inicio , p.nome , p.id, log.usuario
                FROM log_atividade as log INNER JOIN colaborador as p on log.usuario = p.ad
                WHERE  log.data = '$data' "
                . "AND log.serial_empresa like '$serial' "
                . "AND p.serial_empresa like '$serial'";
            $sql .= " GROUP BY p.id ORDER BY log.id ASC";

            $command = Yii::app()->getDb()->createCommand($sql);
            $chegada = $command->queryAll();
            foreach ($chegada as $value){
                $id = $value['id'];
                $colaborador = new GrfColaboradorConsolidado;
                $colaborador->data = $data;
                $colaborador->fk_empresa = $idEmpresa;
                $colaborador->nome = $value['nome'];
                $colaborador->hora_entrada = MetodosGerais::getHoraServidor(date('H:i:s', strtotime($value['hora_inicio'])));
                $colaborador->fk_colaborador = $value['id'];
                $sql = "SELECT  date_sub(log.data_hora_servidor, interval 3 hour) as hora_final, p.nome, p.id
                    FROM log_atividade as log INNER JOIN colaborador as p on log.usuario = p.ad
                    WHERE p.id = '$colaborador->fk_colaborador' AND log.data = '$data' "
                    . "AND log.serial_empresa like '$serial' "
                    . "AND p.serial_empresa like '$serial'"
                    . " ORDER BY log.id DESC LIMIT 1";
                $command = Yii::app()->getDb()->createCommand($sql);
                $saida =  $command->queryAll();
                $colaborador->hora_saida = MetodosGerais::getHoraServidor(date('H:i:s', strtotime($saida[0]['hora_final'])));
                if (!$colaborador->save()) {
                    print_r($colaborador->errors);
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }

    public static function consolidarHoraExtra($data, $idEmpresa,$serial) {
        try {
            $empresa = Empresa::model()->findByPk($idEmpresa);
            $horaEntradaEmpresa = EmpresaHasParametro::model()->findByAttributes(
                array("fk_empresa" => $idEmpresa))->horario_entrada;
            $horaSaidaEmpresa = EmpresaHasParametro::model()->findByAttributes(
                array("fk_empresa" => $idEmpresa))->horario_saida;
            $colaboradorPonto = GrfColaboradorConsolidado::model()->findAllByAttributes(
                array("fk_empresa"=> $idEmpresa, "data" => $data));

            foreach ($colaboradorPonto as $value){
                if (($value->hora_entrada < $horaEntradaEmpresa)
                    || ($value->hora_saida > $horaSaidaEmpresa)) {
                    $horaExtra = new GrfHoraExtraConsolidado();
                    $horaExtra->data = $value->data;
                    $horaExtra->fk_colaborador = $value->fk_colaborador;
                    $horaExtra->fk_empresa = $idEmpresa;

                    //Calculando a duracao da hora extra
                    $diffHoraEntrada = $diffHoraSaida = 0;

                    if ($value->hora_entrada < $horaEntradaEmpresa) {
                        $diffHoraEntrada = (float) (strtotime($horaEntradaEmpresa) - strtotime($value->hora_entrada));
                    }
                    if ($value->hora_saida > $horaSaidaEmpresa) {
                        $diffHoraSaida = (float) (strtotime($value->hora_saida) - strtotime($horaSaidaEmpresa));
                    }

                    $horaExtra->duracao = round(($diffHoraEntrada + $diffHoraSaida)/3600, 2);

                    $horaExtraInicio = date('Y-m-d H:i:s', strtotime($value->data . $value->hora_entrada));
                    $horaExtraFim = date('Y-m-d H:i:s', strtotime($value->data . $value->hora_saida));
                    $entradaEmpresaAux = date('Y-m-d H:i:s', strtotime($value->data . MetodosGerais::setHoraServidor($horaEntradaEmpresa)));
                    $saidaEmpresaAux = date('Y-m-d H:i:s', strtotime($value->data . MetodosGerais::setHoraServidor($horaSaidaEmpresa)));
                    $horaExtra->hora_inicio = $horaExtraInicio;
                    $horaExtra->hora_fim = $horaExtraFim;

                    $produtividadeAux1 = array();
                    $produtividadeAux2 = array();
                    if ($value->hora_entrada < $horaEntradaEmpresa) {
                        $sql = "SELECT at.usuario , SUM(TIME_TO_SEC(at.duracao)) as duracao "
                        . "FROM log_atividade as at "
                            . "INNER JOIN colaborador as pe ON pe.ad = at.usuario AND pe.id = $value->fk_colaborador"
                        . " WHERE at.serial_empresa like '$serial' "
                        . "AND (data_hora_servidor < '$entradaEmpresaAux') "
                        . "AND (data = '$value->data') "
                        . "AND (at.`programa` IN "
                        . "(SELECT nome FROM programa_permitido "
                            . "WHERE (fk_empresa = $empresa->id AND fk_equipe = pe.fk_equipe) "
                        . "OR (fk_empresa = $empresa->id AND fk_equipe IS NULL))) "
                        . "GROUP BY at.usuario";

                        $command = Yii::app()->getDb()->createCommand($sql);
                        $produtividadeAux1 = $command->queryAll();
                    }
                    if ($value->hora_saida > $horaSaidaEmpresa) {
                        $sql = "SELECT at.usuario , SUM(TIME_TO_SEC(at.duracao)) as duracao "
                        . "FROM log_atividade as at "
                            . "INNER JOIN colaborador as pe ON pe.ad = at.usuario AND pe.id = $value->fk_colaborador"
                        . " WHERE at.serial_empresa like '$serial' "
                        . "AND (data_hora_servidor > '$saidaEmpresaAux') "
                        . "AND (data = '$value->data') "
                        . "AND (at.`programa` IN "
                        . "(SELECT nome FROM programa_permitido "
                            . "WHERE (fk_empresa = $empresa->id AND fk_equipe = pe.fk_equipe) "
                        . "OR (fk_empresa = $empresa->id AND fk_equipe IS NULL))) "
                        . "GROUP BY at.usuario";

                        $command = Yii::app()->getDb()->createCommand($sql);
                        $produtividadeAux2 = $command->queryAll();
                    }
                    $produtividade = $produtividadeAux2 + $produtividadeAux1;

                    if (!empty($produtividade)){
                        $tempoProduzido = round(($produtividade[0]['duracao']/3600), 2);
                        $horaExtra->produtividade = ($tempoProduzido > $horaExtra->duracao)?$horaExtra->duracao :$tempoProduzido;
                    } else {
                        $horaExtra->produtividade = 0;
                    }
                    $horaExtra->save();
                }
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}
