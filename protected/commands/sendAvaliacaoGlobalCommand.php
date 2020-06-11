<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 13/05/2016
 * Time: 13:39
 */
class sendAvaliacaoGlobalCommand extends CConsoleCommand
{
    public function run()
    {
        Yii::setPathOfAlias('userGroups.models.UserGroupsGroup', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsGroup');
        Yii::setPathOfAlias('userGroups.models.UserGroupsAccess', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsAccess');
        $this->AvaliacaoGlobal();
    }



    public function AvaliacaoGlobal(){
        
        $pocs = Empresa::model()->searchPoc()->getData();
        foreach ($pocs as $value) {
            $dataInicio = date('Y-m-d', strtotime("-7 days"));
            $dataFim = date("Y-m-d");
            $diffDias = MetodosGerais::DataDiff($dataInicio, $dataFim);
            $dataIincioAnterior = date('Y-m-d', strtotime('-' . ($diffDias + 1) . ' days', strtotime($dataInicio)));
            $dataFimAnterior = date('Y-m-d', strtotime('-' . ($diffDias + 1) . ' days', strtotime($dataFim)));

            /*Tópico 1 - Produtividade*/
            $produtividadeEquipes = PainelControle::getProdutividadeEquipesRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $value->id);
            $custo = PainelControle::getCustoRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $value->id);
            $produtividadeColaborador = PainelControle::getProdutividadeColaboradorRelGlobalApi($dataInicio, $dataFim, $value->id);
            $horaExtra = PainelControle::getHoraExtraRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $value->id);
            $ausenciaColaborador = PainelControle::getAusenciaColaboradoresRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $value->id);
            $mediaHorasTrabalhadas = PainelControle::getMediaHorasTrabalhadasRelGlobalApi($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $diffDias, $value->id);
            $atividadesExternas = PainelControle::getAtividadesExternasRelGlobalApi($dataInicio, $dataFim, $value->id);
            $arrayProdutividade = array(
                'produtividadeEquipe' => $produtividadeEquipes, 'custo' => $custo, 'produtividadeColaborador' => $produtividadeColaborador,
                'hora_extra' => $horaExtra, 'ausenciaColaborador' => $ausenciaColaborador, 'mediaHorasTrabalhadas' => $mediaHorasTrabalhadas,
                'atividadeExternas' => $atividadesExternas
            );

            /*Tópico 2 - Métrica*/
            $metricasMetaAlcancada = PainelControle::getMetaAlcancadaMetricaRelGlobalApi($dataInicio, $dataFim, $value->id);
            $metricasMaximoLimite = PainelControle::getMetricaLimiteMaximoRelGlobalApi($dataInicio, $dataFim, $value->id);
            $metricasMinimoLimite = PainelControle::getMetricaLimiteMinimoRelGlobalApi($dataInicio, $dataFim, $value->id);
            $arrayMetrica = array(
                'metricaMetaAlcancada' => $metricasMetaAlcancada, 'metricaMaximoLimite' => $metricasMaximoLimite, 'metricaMinimoLimite' => $metricasMinimoLimite
            );

            /*Topico 3 - Projetos*/

            $projetosAdiantados = PainelControle::getContratosAdiantadosApi($dataInicio, $dataFim, $diffDias, $value->id);
            $projetosAtrasados = PainelControle::getContratosAtrasadosApi($value->id);
            $arrayProjeto = array(
                'projetoAdiantado' => $projetosAdiantados, 'projetoAtrasado' => $projetosAtrasados
            );

            /*Topico 4 - Informações complementares*/
            $colaboradores = count(Colaborador::model()->findAllByAttributes(array('serial_empresa' => $value->serial)));
            $colaboradoresAtivos = count(Colaborador::model()->findAllByAttributes(array('fk_empresa' => $value->id, 'status' => 1)));
            $equipes = count(Equipe::model()->findAllByAttributes(array('fk_empresa' => $value->id)));
            $coordenadores = count(UserGroupsUser::model()->findAllByAttributes(array('fk_empresa' => $value->id, 'group_id' => 4)));
            $programasNaoPermitidos = ListaNegraPrograma::model()->findAllByAttributes(array('fk_empresa' => $value->id), array('limit' => 3, 'order' => 'porcentagem DESC'));
            $sitessNaoPermitidos = ListaNegraSite::model()->findAllByAttributes(array('fk_empresa' => $value->id), array('limit' => 3, 'order' => 'porcentagem DESC'));
            $arrayInformacoes = array(
                'colaboradores' => $colaboradores, 'colaboradoresAtivos' => $colaboradoresAtivos, 'equipes' => $equipes,
                'coordenadores' => $coordenadores, 'programasNaoPermitidos' => $programasNaoPermitidos, 'sitesNaoPermitidos' => $sitessNaoPermitidos
            );
           $nome =  BackupRelatorios::geraRelGlobalPDF($arrayProdutividade, $arrayMetrica, $arrayProjeto, $arrayInformacoes, $value, $dataInicio, $dataFim);
            $this->send($nome, $value->nome);
        }
    }

    public function send($nome, $nomeEmpresa)
    {
        echo "ARQUIVO: ".utf8_decode($nome) . "\n\n";
        $html = "Segue avaliação global da empresa: <strong>".$nomeEmpresa."</strong>";
        SendMail::send('notificacao@vivainovacao.com', array("robsonferreira@vivainovacao.com"), 'Avaliação global', $html, 
            'robsonferreira@vivainovacao.com', array(utf8_decode($nome)));    
    }
}