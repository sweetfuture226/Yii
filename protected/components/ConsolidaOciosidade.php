<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 16/09/2016
 * Time: 14:06
 */
class ConsolidaOciosidade
{
    public static function consolidar($fk_empresa, $data, $duracao = '02:30:00')
    {
        try {
            $logs = LogAtividade::model()->getAltaOciosidadeByDuracao($fk_empresa, $data, $duracao);
            foreach ($logs as $log) {
                $objGrfOciosoConsolidado = new GrfOciosidadeConsolidado();
                $objGrfOciosoConsolidado->fk_empresa = $fk_empresa;
                $objGrfOciosoConsolidado->fk_log = $log->id;
                $objGrfOciosoConsolidado->fk_colaborador = $log->fk_colaborador;
                $objGrfOciosoConsolidado->data = $data;
                $objGrfOciosoConsolidado->hora_final = $log->hora_final;
                $objGrfOciosoConsolidado->duracao = $log->duracao;
                $objGrfOciosoConsolidado->hora_inicial = self::getHoraInicial($log->hora_final, $log->duracao);
                $objGrfOciosoConsolidado->save();
            }

        } catch (Exception $e) {
            Logger::saveException($e, $fk_empresa);
        }
    }

    public static function getHoraInicial($hora_final, $duracao)
    {
        $a = new DateTime($hora_final);
        $b = new DateTime($duracao);
        $hora_inicial = $a->diff($b);
        return $hora_inicial->format('%H:%I:%S');
    }

}