<?php

/**
 * Class ProdutivoContratoCommand
 *
 * CRON utilizado para gerar relatorios de produtividade dos contratos semanalmente e enviar por email para o gestor.
 */
class ProdutivoContratoCommand extends CConsoleCommand {
    public function run() {
        $dataInicio = date('Y-m-d',  strtotime('-4 days'));
        $dataFim = date('Y-m-d');
        $contratosProdutivos = GrfProjetoConsolidado::model()->getProdutivideContratosCron($dataInicio, $dataFim);
        $projetos = array();
        foreach ($contratosProdutivos as $projeto){
            $projetos[$projeto->fk_empresa][$projeto->nome][] = $projeto;
        }

        $html ='';
        $style = MetodosGerais::getStyleTable();
        $rodape = MetodosGerais::getRodapeTable();

        foreach ($projetos as $idEmpresa=>$projetoEmpresa){
            $pathImg = Empresa::model()->findByPk($idEmpresa)->logo;
            $imagem = "/opt/lampp/htdocs/viva-smith/".$pathImg;
            $html =  '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                            <page_header>
                            <div class="header_page">
                            <img class="header_logo_page" src="' . $imagem . '">
                            <div class="header_title">
                                <span>RESUMO SEMANAL DE ACOMPANHAMENTO DOS CONTRATOS</span><br>
                                <span style="font-size: 10px">'.Yii::t("smith", 'No período de').' ' . MetodosGerais::dataBrasileira($dataInicio) . ' a ' . MetodosGerais::dataBrasileira($dataFim) . ' </span>

                            </div>


                            <div class="header_date">
                            <p>Data:  ' . date("d/m/Y") . '
                                <br>Pág. ([[page_cu]]/[[page_nb]]) </p>
                            </div>
                            </div>

                            </page_header>
                            </page>';
            $html .= $rodape;

            foreach ($projetoEmpresa as $contrato=>$LogsColaborador){
                $codigoContrato = Contrato::model()->findByPk($LogsColaborador[0]->fk_obra)->codigo;
                $html .= "<table class='table_custom' border='1px'>";
                $html .= "<tr>"
                        . "<th  colspan='4' >Contrato - [ $contrato | $codigoContrato ]</th>
                        </tr>
                    <tr style='background-color: #CCC; text-decoration: bold;'>
                     <th>Equipe</th>
                     <th>Colaborador</th>
                     <th>Tempo produzido</th>
                     <th>Custo parcial</th>
                    </tr>";
                foreach ($LogsColaborador as $log){
                        $html .= "<tr ><td style='width: 200px'>{$log->equipe}</td>";
                        $html .= "<td style='width: 200px'>{$log->colaborador}</td>";
                        $html .= "<td style='width: 130px; text-align: center'>".MetodosGerais::formataTempo($log->duracao)."</td>";
                        $html .= "<td style='width: 130px; text-align: center'>R$".  MetodosGerais::float2real(round((($log->salario)*($log->duracao/3600)),2))."</td></tr>";
                }

                $html .= "</table>";
                $html .= "<p style='margin-top: 15px'></p>";
            }
            $empresaNome = Empresa::model()->findByPk($idEmpresa)->nome;
            $nome = Yii::app()->baseUrl . "/../public/produtivoContrato/Relatório de produtividade dos contratos - ".$empresaNome.".pdf";
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($html . $style);
            $html2pdf->Output($nome, 'F');

            $body = "Segue em anexo o relatório com a produtividade semanal dos contratos";
            $body.= "<div><br><span>Atenciosamente,</span><br><span> Equipe Viva Inovação</span></div>";
            // $message = new YiiMailMessage;
            // $message->setBody($body, 'text/html');
            // $message->subject = "[Smith] Produtividade dos contratos - verificação semanal";
            // $destinatario = "smith@vivainovacao.com"; //retirar quando for pro ar
            // $message->addTo($destinatario);
            // $message->addBCC("contato@vivainovacao.com");
            // $swiftAttachment = Swift_Attachment::fromPath($nome); // create a Swift Attachment
            // $message->attach($swiftAttachment);
            // Yii::app()->mail->send($message);

            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo, array de nomes dos arquivos);
            SendMail::send('lucascardoso@vivainovacao.com', array($destinatario), '[Smith] Produtividade dos contratos - verificação semanal', $body, 'contato@vivainovacao.com', array(utf8_decode('Relatório de produtividade dos contratos - '. $empresaNome .'.pdf')));
        }



    }

}
?>

