<?php

/**
 * Class alertaContratoCommand
 *
 * CRON para verificar o andamento da produtividade dos documentos e enviar email alertando ao gestor
 * sobre a situação (se encontra acima de 70% ou 100%).
 */
class alertaContratoCommand extends CConsoleCommand
{
    public function run()
    {
        $documentos = Documento::model()->findAll(array('condition' => 'finalizado = 0 AND porcentagem_concluida < 100'));
        $arrayDocumentoAtrasado = $arrayDocumentoAlerta = array();
        $i = 0;
        foreach ($documentos as $obj) {
            $logsDocumento = GrfProjetoConsolidado::model()->getTempoDocumento($obj->nome);
            if (isset($logsDocumento->duracao)) {
                $porcentagem = round(($logsDocumento->duracao * 100) / MetodosGerais::time_to_seconds($obj->previsto), 2);
                if ($porcentagem > 100 && $obj->porcentagem_concluida < 100) {
                    echo "Maior que 100 \t" . $obj->nome . "\t" . $porcentagem . "\n";
                    $arrayDocumentoAtrasado[$obj->fk_empresa][$obj->fk_contrato][$i]['porcentagem'] = $porcentagem;
                    $arrayDocumentoAtrasado[$obj->fk_empresa][$obj->fk_contrato][$i]['documento'] = $obj->nome;
                    $obj->porcentagem_concluida = 100;
                    $obj->save(false);
                } elseif ($porcentagem > 70 && $obj->porcentagem_concluida < 70) {
                    echo "Maior que 70 \t" . $obj->nome . "\t" . $porcentagem . "\n";
                    $arrayDocumentoAlerta[$obj->fk_empresa][$obj->fk_contrato][$i]['porcentagem'] = $porcentagem;
                    $arrayDocumentoAlerta[$obj->fk_empresa][$obj->fk_contrato][$i]['documento'] = $obj->nome;
                    $obj->porcentagem_concluida = 70;
                    $obj->save(false);
                }
            }
            $i++;
        }
        $this->sendEmailAlerta($arrayDocumentoAlerta);
        $this->sendEmailAtrasados($arrayDocumentoAtrasado);
    }

    /**
     * @param $arrayDocumentoAlerta
     *
     * Envio de email para os clientes com a relação dos documentos que se encontram com produtividade
     * acima de 70%
     */
    private function sendEmailAlerta($arrayDocumentoAlerta)
    {
        foreach ($arrayDocumentoAlerta as $idEmpresa => $contratos) {
            foreach ($contratos as $idContrato => $documentos) {
                $html = '<table  border="1px" style="font-family: arial,sans-serif; border-spacing: 0;border: 0;border-collapse:collapse;">' .
                    '<tr style="font-size: 15px;padding: 10px;text-align:center;border: 1px solid #CCC;">'
                    . '<th colspan="2" style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;text-align: center">'
                    . Contrato::model()->findByPk($idContrato)->nome
                    . '</th></tr>';
                $html .= '<tr>'
                    . '<td style="width: 400px;font-weight: bold;font-size: 13px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">Documento</td>'
                    . '<td style="width: 400px;font-weight: bold;font-size: 13px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">Porcentagem</td></tr>';
                foreach ($documentos as $value) {
                    $html .= '<tr><td style="width: 400px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">' . $value['documento'] . '</td>';
                    $html .= '<td style="width: 400px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">' . MetodosGerais::float2real($value['porcentagem']) . '%</td></tr>';
                }
                $html .= '</table>';
            }
            $mensagem = 'Alguns contratos possuem documentos com mais de 70% de produtividade:';
            $obs = '<br>É sugerido que os arquivos acima sejam verificados junto ao profissional responsável, a fim de garantir que o tempo dispendido esteja proporcional com o progresso do trabalho.';
            // SendMail::send('Documentos com mais de 70% do tempo dispendido', 'lucascardoso@vivainovacao.com', 'gestor', $mensagem . $html . $obs);
            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), 'Documentos com mais de 70% do tempo dispendido', $mensagem . $html . $obs);
        }
    }

    /**
     * @param $arrayDocumentoAtrasado
     *
     * Envio de email para os clientes com a relação dos documentos que se encontram com produtividade
     * acima de 100%
     */
    private function sendEmailAtrasados($arrayDocumentoAtrasado)
    {
        foreach ($arrayDocumentoAtrasado as $idEmpresa => $contratos) {
            foreach ($contratos as $idContrato => $documentos) {
                $html = '<table  border="1px" style="font-family: arial,sans-serif; border-spacing: 0;border: 0;border-collapse:collapse;">' .
                    '<tr style="font-size: 15px;padding: 10px;text-align:center;border: 1px solid #CCC;">'
                    . '<th colspan="2" style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;text-align: center">'
                    . Contrato::model()->findByPk($idContrato)->nome
                    . '</th></tr>';
                $html .= '<tr>'
                    . '<td style="width: 400px;font-weight: bold;font-size: 13px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">Documento</td>'
                    . '<td style="width: 400px;font-weight: bold;font-size: 13px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">Porcentagem</td></tr>';
                foreach ($documentos as $value) {
                    $html .= '<tr><td style="width: 400px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:left;border-collapse:collapse;">' . $value['documento'] . '</td>';
                    $html .= '<td style="width: 400px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;white-space: nowrap;text-align:center;border-collapse:collapse;">' . MetodosGerais::float2real($value['porcentagem']) . '%</td></tr>';
                }
                $html .= '</table>';
            }
            $mensagem = 'Alguns contratos possuem documentos atrasados:';
            $obs = '<br>É sugerido que os arquivos acima sejam verificados junto ao profissional responsável, a fim de verificar se houveram inconsistências no planejamento ou na execução das atividades.';
            // SendMail::send('Documentos com mais de 100% do tempo dispendido', 'lucascardoso@vivainovacao.com', 'gestor', $mensagem . $html . $obs);
            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), 'Documentos com mais de 100% do tempo dispendido', $mensagem . $html . $obs);
        }
    }
}