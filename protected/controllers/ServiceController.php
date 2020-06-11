<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebServiceController
 *
 * @author viva
 */
class ServiceController extends CController {


    public function actions()
    {
        return array(
            'log'=>array(
                'class'=>'CWebServiceAction',
                ),
        );
    }

    /**
     *
     * @param  mixed dadosLog
     * @return float
     * @soap
     */
    public function insertLog($dadosLog)
    {
        $empresaStatus = Empresa::model()->findByAttributes(array("serial"=>$dadosLog[7]))->ativo;
        $hoje = date('d/m/Y');
        $empresaId = Empresa::model()->findByAttributes(array("serial"=>$dadosLog[7]))->id;
        $hasIdenticalLog = LogAtividade::model()->find(array(
            'condition' => 'fk_empresa = :fk_empresa AND data = :data AND usuario = :usuario AND descricao = :descricao AND ( hora_host = :hora_host OR TIME_TO_SEC(hora_host) = TIME_TO_SEC(:hora_host) + 1 OR TIME_TO_SEC(hora_host) = TIME_TO_SEC(:hora_host) - 1 )',
            'params' => array(
                ':fk_empresa' => $empresaId,
                ':data' => $dadosLog[3],
                ':usuario' => $dadosLog[4],
                ':descricao' => $dadosLog[1],
                ':hora_host' => $dadosLog[6]
            )
        ));
        if($empresaStatus == 1){
            if (empty($hasIdenticalLog)) {
                $model = new LogAtividade();
                $model->programa = $dadosLog[0];
                $model->descricao = $dadosLog[1];
                $model->duracao = $dadosLog[2];
                $model->data = $dadosLog[3];
                $model->usuario = $dadosLog[4];
                $model->title_completo = $dadosLog[5];
                $model->hora_host = $dadosLog[6];
                $model->serial_empresa = $dadosLog[7];
                $model->nome_host = $dadosLog[8];
                $model->host_domain = $dadosLog[9];
                $model->fk_empresa = $empresaId;
                if ($model->save()){
                   // file_put_contents(Yii::app()->request->baseUrl."public/log1-{$model->data}-{$empresaId}.txt", $model);
                    return 1;
                }
                else{
                   // file_put_contents(Yii::app()->request->baseUrl."public/log2-{$model->data}-{$empresaId}.txt", $model);
                    return 0;
                }
            }
            else{
                //file_put_contents(Yii::app()->request->baseUrl."public/log10-{$hoje}-{$empresaId}.txt", 'Repetido');
                return 10;
            }
        } else{
           // file_put_contents(Yii::app()->request->baseUrl."public/log11-{$hoje}-{$empresaId}.txt", 'Empresa inativa');
            return 0;
        }

    }
    /**
     *
     * @param  mixed dadosVersao
     * @return float
     * @soap
     */
    public function informaVersao($dadosVersao){
        $empresaId = Empresa::model()->findByAttributes(array("serial"=>$dadosVersao[2]))->id;
        $fkUsuario = Colaborador::model()->findByAttributes(array("serial_empresa" => $dadosVersao[2], "ad" => $dadosVersao[1]));
        if(isset($fkUsuario)){
            $loadModel = Executavel::model()->findByAttributes(array('fk_usuario'=>$fkUsuario->id),array("order"=>"id DESC"));
            if(isset($loadModel)){
                if($dadosVersao[0]==$loadModel->versao){
                    $loadModel->data = date("Y-m-d H:i:s");
                    $loadModel->save();
                    return 11;
                }
                else{
                    $model = new Executavel();
                    $model->versao = $dadosVersao[0];
                    $model->data = date("Y-m-d H:i:s");
                    $model->fk_usuario = $fkUsuario->id;
                    $model->fk_empresa = $empresaId;
                    $model->save();
                    return 12;
                }
            }
            else{
                $model = new Executavel();
                $model->versao = $dadosVersao[0];
                $model->data = date("Y-m-d H:i:s");
                $model->fk_usuario = $fkUsuario->id;
                $model->fk_empresa = $empresaId;
                if($model->save())
                    return 10;
                else
                    return 00;
            }
        }
        else {
            return 00;
        }

    }


    /**
     *
     * @param  mixed serial
     * @return int
     * @soap
     */
    public function getTempoOcioso($serial){
        $empresaId = Empresa::model()->findByAttributes(array("serial"=>$serial))->id;
        $tempoOcioso = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $empresaId))->tempo_ocio;
        return $tempoOcioso;
    }


    /**
     *
     * @param  mixed dadosVersao
     * @return int
     * @soap
     */
    public function updateCliente($dadosVersao){
        $versao = VersaoInstalador::model()->find(array("order"=>"id DESC"));
        if(isset($versao)){
            if(((float)$dadosVersao[0]<(float)$versao->versao))
                return 1 ;
            else
                return 0 ;
        }
        else {
            return 0;
        }

    }


}
