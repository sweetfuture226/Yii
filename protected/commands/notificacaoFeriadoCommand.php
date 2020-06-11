<?php

/**
 * Class notificacaoFeriadoCommand
 *
 * CRON utilizado para verificar se houve ausência de captura de atividade em mais de 50% dos colaboradores da empresa
 * e alertar através do painel de notificações.
 */

class notificacaoFeriadoCommand extends CConsoleCommand{
    public function run(){
        $data = date('Y-m-d',strtotime('-1 days'));
        $empresas = Empresa::model()->findAll();
        foreach($empresas as $empresa){
            $TotalColSemProd = ColaboradorSemProdutividade::model()->getTotalColSemProd($empresa->id,$data);
            $TotalColAtivos = Colaborador::model()->findAllByAttributes(array("fk_empresa" => $empresa->id, 'status' => 1));
            if(count($TotalColAtivos)>0){
                $porcentagem = round((float)($TotalColSemProd->total*100)/count($TotalColAtivos),2);
                if($porcentagem>50){
                    $objNotificacao = Notificacao::model()->findByAttributes(array('tipo'=>6,'fk_empresa'=>$empresa->id));
                    if(empty($objNotificacao)){
                        $modelNotificao = new Notificacao();
                        $modelNotificao->notificacao = Yii::t("smith", "Mais de 50% dos colaboradores não tiveram a produtividade contabilizada <br> em algumas datas. Verifique se foi feriado.");
                        $modelNotificao->fk_empresa = $empresa->id;
                        $modelNotificao->tipo = 6;
                        $modelNotificao->action = 'calendarioFeriados/feriadoInativo';
                        $modelNotificao->save(false);
                    }
                    $feriado = new CalendarioFeriados();
                    $feriado->data = $data;
                    $feriado->fk_empresa = $empresa->id;
                    $feriado->ativo = 0;
                    $feriado->save();
                }
            }

        }

    }
}
