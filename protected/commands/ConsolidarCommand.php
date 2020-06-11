<?php

/*
 * TODO
 * verificar necessidade deste cron e remover
 */

class ConsolidarCommand extends CConsoleCommand {


    public function run() {
        // Start date
        $date = '2015-01-01';
        // End date
        $end_date = date('Y-m-d');

        while (strtotime($date) <= strtotime($end_date)) {
            $data = date('Y-m-d', strtotime($date));
            echo "$data\n";
//            $dataInicio = date('Y-m-' . 01);
//            $dataFim = date('Y-m-d');
            $this->consolidarProdutividadeEquipe($data, 25);
            $this->consolidarPrograma($data, 25);
            $this->consolidarColaborador($data, 25);
            $this->consolidarHoraExtra($data, 25);

            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
    }

    public function consolidarHoraExtra($data, $idEmpresa) {
        $empresa = Empresa::model()->findByPk($idEmpresa);
        $hora_entrada = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $idEmpresa))->horario_entrada;
        $hora_saida = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $idEmpresa))->horario_saida;
        $tempo_almoco = (float)(EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $idEmpresa))->almoco_fim
            - EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $idEmpresa))->almoco_inicio);
        $hora_total_empresa = (float) (($hora_saida - $hora_entrada) - $tempo_almoco);
        $colaborador_ponto = GrfColaboradorConsolidado::model()->findAllByAttributes(array("fk_empresa"=> $idEmpresa,
                                                                                           "data" => $data));

        foreach ($colaborador_ponto as $value){
            $hora_total_colaborador = (float) (($value->hora_saida - $value->hora_entrada) - $tempo_almoco);

            if($hora_total_colaborador > $hora_total_empresa){
                $hora_extra = new GrfHoraExtraConsolidado();
                $hora_extra->data = $value->data;
                $hora_extra->fk_colaborador = $value->fk_colaborador;
                $hora_extra->fk_empresa = $idEmpresa;

                $hora_total_empresa += $tempo_almoco;
                $diff_hora = date("H:i:s", strtotime("+$hora_total_empresa hour", strtotime($value->hora_entrada)));

                $hora_extra_inicio = date('Y-m-d H:i:s', strtotime($value->data . $diff_hora));
                $hora_extra_fim = date('Y-m-d H:i:s', strtotime($value->data . $value->hora_saida));

                $hora_extra->hora_inicio = $hora_extra_inicio;
                $hora_extra->hora_fim = $hora_extra_fim;
                $hora_extra->duracao = round((strtotime($value->hora_saida) - strtotime($diff_hora))/3600, 2);

                $auxInicio = date("Y-m-d H:i:s", strtotime("-2 hour", strtotime($hora_extra_inicio)));
                $auxFim = date("Y-m-d H:i:s", strtotime("-2 hour", strtotime($hora_extra_fim)));

                $sql = "SELECT at.usuario , SUM(TIME_TO_SEC(at.duracao)) as duracao "
                    . "FROM log_atividade as at "
                    . "INNER JOIN colaborador as pe ON pe.ad = at.usuario AND pe.id = $value->fk_colaborador"
                    . " WHERE at.serial_empresa like '$empresa->serial' "
                    . "AND (data_hora_servidor BETWEEN '$auxInicio' AND '$auxFim') "
                    . "AND (at.`programa` IN "
                    . "(SELECT nome FROM programa_permitido "
                    . "WHERE (fk_empresa = $empresa->id AND fk_equipe = pe.fk_equipe) "
                    . "OR (fk_empresa = $empresa->id AND fk_equipe IS NULL))) "
                    . "GROUP BY at.usuario";

                $command = Yii::app()->getDb()->createCommand($sql);
                $produtividade = $command->queryAll();

                if (!empty($produtividade)){
                    $hora_extra->produtividade = round(($produtividade[0]['duracao']/3600), 2);
                } else {
                    $hora_extra->produtividade = 0;
                }
                $hora_extra->save();
            }
        }
    }

    public function consolidarColaborador($data, $idEmpresa) {
        $empresa = Empresa::model()->findByPk($idEmpresa);
        $arrayConsolidado = array();

        $sql = "SELECT log.data as data , date_sub(min(log.data_hora_servidor), interval 4 hour) as hora_inicio , p.nome , p.id, log.usuario
		FROM log_atividade as log INNER JOIN colaborador as p on log.usuario = p.ad
		WHERE  log.data = '$data' "
                . "AND log.serial_empresa like '{$empresa->serial}' "
                . "AND p.serial_empresa like '{$empresa->serial}'";
        $sql .= " GROUP BY p.nome ORDER BY log.id ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        $chegada = $command->queryAll();
        foreach ($chegada as $value){
            $id = $value['id'];
            $colaborador = new GrfColaboradorConsolidado;
            $colaborador->data = $data;
            $colaborador->fk_empresa = $idEmpresa;
            $colaborador->nome = $value['nome'];
            $colaborador->hora_entrada = $this->getHoraServidor2(date('H:i:s', strtotime($value['hora_inicio'])));
            $colaborador->fk_colaborador = $value['id'];
            $sql = "SELECT  date_sub(log.data_hora_servidor, interval 4 hour) as hora_final, p.nome, p.id 
                FROM log_atividade as log INNER JOIN colaborador as p on log.usuario = p.ad
                WHERE p.id = '$colaborador->fk_colaborador' AND log.data = '$data' "
                . "AND log.serial_empresa like '{$empresa->serial}' "
                . "AND p.serial_empresa like '{$empresa->serial}'"
                . " ORDER BY log.id DESC LIMIT 1";
            $command = Yii::app()->getDb()->createCommand($sql);
            $saida =  $command->queryAll();
            $colaborador->hora_saida = $this->getHoraServidor2(date('H:i:s', strtotime($saida[0]['hora_final'])));
            if (!$colaborador->save()) {
                print_r($colaborador->errors);
            }
        }
    }

    public function consolidarPrograma($data, $idEmpresa) {
        $empresa = Empresa::model()->findByPk($idEmpresa);
        $somaProduzido = $somaNaoIdentificado = $somaSites = 0;

        $programa = $this->getTempoProduzidoByPrograma($data, $empresa);
        foreach ($programa as $value){
            $consolidar =  new GrfProgramaConsolidado();
            $consolidar->categoria = 'programa';
            $consolidar->programa = $value['programa'];
            $consolidar->duracao = $value['duracao'];
            $somaProduzido += $value['duracao'];
            $consolidar->data = $data;
            $consolidar->fk_empresa = $empresa->id;
            if (!$consolidar->save()) {
                print_r($consolidar->errors);
            }
        }
        $sites = $this->getTempoProduzidoBySites($data, $empresa);
        foreach ($sites as $value){
            $consolidar =  new GrfProgramaConsolidado();
            $consolidar->categoria = 'site';
            $consolidar->programa = $value['programa'];
            $consolidar->duracao = $value['duracao'];
            $somaSites += $value['duracao'];
            $consolidar->data = $data;
            $consolidar->fk_empresa = $empresa->id;
            if (!$consolidar->save()) {
                print_r($consolidar->errors);
            }
        }

        $nao_identificado = $this->getTempoProgramaNaoIdentificado($data, $empresa);
        foreach ($nao_identificado as $value){
            $consolidar =  new GrfProgramaConsolidado();
            $consolidar->categoria = 'nao_identificado';
            $consolidar->programa = $value['programa'];
            $consolidar->duracao = $value['duracao'];
            $somaNaoIdentificado += $value['duracao'];
            $consolidar->data = $data;
            $consolidar->fk_empresa = $empresa->id;
            if (!$consolidar->save()) {
                print_r($consolidar->errors);
            }
        }
    }

    public function consolidarProdutividadeEquipe($data, $idEmpresa) {
        $sql = " SELECT FORMAT( (pe.salario) /30, 2 ) AS salario_colaborador,
                        ((TIME_TO_SEC( pe.`horas_semana` )) /3600) /5 AS hora_total,
                        pe.nome as colaborador,
                        SUM( TIME_TO_SEC( at.`duracao` ) )/3600 AS Duracao_colaborador , eq.nome
                        FROM  `log_atividade_consolidado` AS at
                        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
                        INNER JOIN equipe AS eq ON eq.id = pe.fk_equipe
                        WHERE at.data = '$data'
                        AND  (at.`programa` IN 
                        (SELECT nome FROM programa_permitido 
                        WHERE (fk_empresa = '$idEmpresa' AND fk_equipe = pe.fk_equipe)
                        OR (fk_empresa = '$idEmpresa' AND fk_equipe IS NULL)))
                        AND at.descricao NOT LIKE ''
                        AND pe.fk_empresa = '$idEmpresa'";
        $sql .= ' GROUP BY pe.nome
                        ORDER BY colaborador ASC';
        $command = Yii::app()->getDb()->createCommand($sql);
        $resultEquipe = $command->queryAll();

        $sql2 = "SELECT DISTINCT pe.nome as colaborador , SUM(TIME_TO_SEC(log.duracao))/3600 as duracao 
                    FROM site_permitido AS p INNER JOIN log_atividade_consolidado AS log 
                    ON log.descricao LIKE CONCAT( '%', p.nome, '%' ) INNER JOIN colaborador AS pe ON pe.ad = log.usuario
                    INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
                    WHERE pe.fk_empresa = '$idEmpresa' AND p.fk_empresa = '$idEmpresa' AND log.data = '$data'  
                    GROUP BY pe.nome ORDER BY pe.nome ASC";

        $command2 = Yii::app()->getDb()->createCommand($sql2);
        $sitesProducao = $command2->queryAll();

        if (!empty($resultEquipe)) {
            $i = 0;
            if (!empty($sitesProducao)) {
                $i = 0;
                foreach ($resultEquipe as $value) {
                    $duracao_sites = (isset($sitesProducao[$i]['duracao'])) ? $sitesProducao[$i]['duracao'] : 0;
                    $newGPC = new GrfProdutividadeConsolidado();
                    $newGPC->equipe = $value['nome'];
                    $newGPC->duracao = (float) ($value['Duracao_colaborador'] + $duracao_sites);
                    $newGPC->data = $data;
                    $newGPC->hora_total = $value['hora_total'];
                    $newGPC->nome = $value['colaborador'];
                    $newGPC->fk_empresa = 25;
                    if (!$newGPC->save()) {
                        print_r($newGPC->errors);
                    }
                    $i++;
                }
            } else {
                foreach ($resultEquipe as $value) {
                    $newGPC = new GrfProdutividadeConsolidado();
                    $newGPC->equipe = $value['nome'];
                    $newGPC->duracao = (float) ($value['Duracao_colaborador']);
                    $newGPC->data = $data;
                    $newGPC->hora_total = $value['hora_total'];
                    $newGPC->nome = $value['colaborador'];
                    $newGPC->fk_empresa = 25;
                    if (!$newGPC->save()) {
                        print_r($newGPC->errors);
                    }
                    $i++;
                }
            }
        }
    }

    private function getTempoProduzidoByPrograma($data, $empresa) {
        $sql = "SELECT eq.nome, at.`programa`, SUM(TIME_TO_SEC(at.`duracao`))/3600 as duracao
                    FROM  `log_atividade_consolidado` AS at
                    INNER JOIN colaborador AS pe ON pe.ad = at.usuario
                    INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
                    WHERE 
                      (at.`programa` IN 
                    (SELECT nome FROM programa_permitido WHERE (fk_empresa = $empresa->id 
                        AND fk_equipe = pe.fk_equipe)
                    OR (fk_empresa = $empresa->id AND fk_equipe IS NULL)))
                    AND eq.fk_empresa = '" . $empresa->id . "' "
                . "AND at.data ='{$data}' AND at.descricao NOT LIKE '' "
                . "AND at.serial_empresa like '$empresa->serial'";
        $sql .= " GROUP BY at.programa";
        $sql .= " ORDER BY duracao desc";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    private function getTempoProduzidoBySites($data, $empresa) {
        $sql = "SELECT DISTINCT p.nome as programa , log.descricao , sum(log.duracao)/3600 as duracao "
            . "FROM site_permitido AS p INNER JOIN log_atividade_consolidado AS log ON log.descricao LIKE CONCAT( '%', p.nome, '%' )"
            . " INNER JOIN colaborador AS pe ON pe.ad = log.usuario "
            . "INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id "
            . "WHERE pe.fk_empresa = $empresa->id "
            . "AND p.fk_empresa = $empresa->id "
                . "AND log.data = '$data' ";
        $sql .= " GROUP BY p.nome";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    private function getTempoProgramaNaoIdentificado($data, $empresa) {
        $sql = "SELECT
                eq.nome,
                at.`programa`, at.descricao,
                SUM(TIME_TO_SEC(at.`duracao`))/3600 as duracao
                FROM  `log_atividade_consolidado` AS at
                INNER JOIN colaborador AS pe ON pe.ad = at.usuario
                INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
                WHERE (TRIM(at.`programa`) NOT IN 
                (SELECT TRIM(nome) FROM programa_permitido WHERE (fk_empresa = $empresa->id AND fk_equipe = pe.fk_equipe)
                OR (fk_empresa = $empresa->id AND fk_equipe IS NULL)))
                AND at.descricao not like '' AND at.descricao not like 'Ocioso'
                AND eq.fk_empresa = '" . $empresa->id .
                "' AND at.data = '{$data}'";
        $sql .= " GROUP BY at.descricao, at.programa ORDER BY duracao desc LIMIT 5";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getHoraServidor2($data){
        $hora = date('d-m-Y H:i:s', strtotime('+2 hour', strtotime($data)));
        $hora = explode(" ", $hora);
        $hora = $hora[1];
        return $hora;

    }
}
