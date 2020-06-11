<?php

/**
 * Class ProdutividadeCommand
 *
 * CRON utilizado para gerar relatorios de produtividade dos colaboradores semanalmente e enviar por email para o gestor.
 */
class ProdutividadeCommand extends CConsoleCommand {


    public function run() {


        $dataAtual = gmdate('Y-m-d', time()-(3600*27));

        $produzido = GrfProdutividadeConsolidado::model()->produtividadeCronDiario($dataAtual);
        $dadosConsolidados  = array();
        $html ='';
        $style = MetodosGerais::getStyleTable();
        $rodape = '<page_footer>
                                <div style="text-align: center ; font-size: 10px; color: #9C9C9C">
                                Relatório gerado na plataforma Viva Smith
                                </div>
                                </page_footer>';

        foreach ($produzido as $prodColaborador){
            $nomeEmpresa = Empresa::model()->findByPk($prodColaborador->fk_empresa)->nome;
            $dadosConsolidados[$nomeEmpresa][$prodColaborador->nome] = $prodColaborador;
            $entradaSaida = GrfColaboradorConsolidado::model()->getEntradaSaida($dataAtual,$prodColaborador->fk_colaborador);
            $dadosConsolidados[$nomeEmpresa][$prodColaborador->nome]['tempo_trabalhado'] = $prodColaborador->hora_total;
            $dadosConsolidados[$nomeEmpresa][$prodColaborador->nome]['horario_inicio'] = (!empty($entradaSaida))? $entradaSaida->hora_entrada: '08:00:00';
            $dadosConsolidados[$nomeEmpresa][$prodColaborador->nome]['horario_fim'] = (!empty($entradaSaida))? $entradaSaida->hora_saida: '16:00:00';
            $dadosConsolidados[$nomeEmpresa]['logo'][] = Empresa::model()->findByPk($prodColaborador->fk_empresa)->logo;
            $dadosConsolidados[$nomeEmpresa]['email'][] = Empresa::model()->findByPk($prodColaborador->fk_empresa)->email;
            $dadosConsolidados[$nomeEmpresa]['fk_empresa'][] = $prodColaborador->fk_empresa;
        }

        foreach ($dadosConsolidados as $key=>$value){
            $imagem = Yii::app()->baseUrl .'/../'. $dadosConsolidados[$key]['logo'][0];
            //$imagem = "/opt/lampp/htdocs/viva-smith/".$dadosConsolidados[$key]['logo'][0]; //retirar quando for pro ar
            $colImprod = ColaboradorSemProdutividade::model()->findAllByAttributes(array("fk_empresa"=>$dadosConsolidados[$key]['fk_empresa'][0],"data"=>$dataAtual));
            $serial = Empresa::model()->findByPk($dadosConsolidados[$key]['fk_empresa'][0])->serial;
            $novoUsuario = Colaborador::model()->findAll(array('condition' => "ativo = 0 AND serial_empresa like '$serial'"));
            $stringCol = $stringColInativo = "";
            $sizeColImprod = count($colImprod);
            $sizeColNovos = count($novoUsuario);
            $i = 1;
            foreach ($colImprod as $improd){
                if($i == $sizeColImprod)
                    $stringCol .= $improd->nome ;
                else
                    $stringCol .= $improd->nome .", ";
                $i++;
            }
            $j= 1;
            foreach ($novoUsuario as $inativo){
                if($j == $sizeColNovos)
                    $stringColInativo .= $inativo->ad ;
                else
                    $stringColInativo .= $inativo->ad .", ";
                $j++;
            }
            $html = '<page orientation="portrait" backtop="30mm" backbottom="20mm" format="A4" >
                        <page_header>
                            <div class="header_page">
                             <img class="header_logo_page" src="' . $imagem . '">
                            <div class="header_title">
                                <span>RELATÓRIO DE ACOMPANHAMENTO DE</span><br><span> PRODUTIVIDADE DOS '.  (count($value)-3) .' COLABORADORES</span>
                            </div>
                            <br><br>
                            <div class="header_date">
                            <p>Data:  ' . date("d/m/Y") . '
                                <br>Pág. ([[page_cu]]/[[page_nb]]) </p>
                            </div>
                            </div>
                        </page_header>
                    </page>';
            $html .= $rodape;
            $inativos = ($stringColInativo == "")? "" : "<span>Perfis que necessitam complementação: $stringColInativo.</span><br>";
            $nprodutivo = ($stringCol == "")? "" :   "<span>Não tiveram produtividade contabilizada nesta data: $stringCol.</span><br>";
            $html .= '<div style="width: 730px ; margin-left: 20px">
                            <span>Referência: '.  MetodosGerais::dataBrasileira($dataAtual).'.</span><br>'
                         .$nprodutivo.$inativos;
            $html .= '</div>';
            $html .= "<p margin-top='5px'> </p><div><table class='table_custom' border='1px'>";

            $html .= "
            <tr style='background-color: #CCC;
	                            text-decoration: bold;'>
                         <th>Equipe</th>
                         <th>Colaborador</th>
                         <th>Horário Início</th>
                         <th>Horário Fim</th>
                          <th>Produzido</th>
                          <th>%</th>

                          </tr>";
            foreach ($value as $name=>$colaborador){
                if($name != 'logo' && $name != 'fk_empresa' && $name != 'email'){
                    $porcentagem = str_replace('.',',',round(($colaborador->duracao*100)/$colaborador->hora_total,2));
                    $html .= "<tr><td style='width: 200px'>{$colaborador->equipe}</td>";
                    $html .= "<td style='width: 180px'>" . MetodosGerais::reduzirNome(Colaborador::model()->findByPk($colaborador->fk_colaborador)->nomeCompleto) . "</td>";
                    $html .= "<td style='width: 15px; text-align: center'>{$colaborador->horario_inicio}</td>";
                    $html .= "<td style='width: 15px; text-align: center'>{$colaborador->horario_fim}</td>";
                    $html .= "<td style='width: 15px ; text-align: center'>".gmdate('H:i:s', ($colaborador->duracao*3600))."</td>";
                    $html .= "<td style='width: 30px; text-align: center'>".$porcentagem."%</td></tr>";
                }
            }
            $html .= "</table></div>";
            $nome = Yii::app()->baseUrl . "/../public/produtivo/Relatório de produtividade - ".$key.".pdf";
            //$nome = "/opt/lampp/htdocs/viva-smith/public/produtivo/Relatório de produtividade - ".$key.".pdf"; //retirar quando for pro ar
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($html . $style);
            $html2pdf->Output(utf8_decode($nome), 'F');

            //$destinatario = $dadosConsolidados[$key]['email'][0];
            $body = "Segue em anexo o relatório com a produtividade dos colaboradores em ".MetodosGerais::dataBrasileira($dataAtual);
            $body.= "<div><br><span>Atenciosamente,</span><br><span> Equipe Viva Inovação</span></div>";
            // $message = new YiiMailMessage;
            // $message->setBody($body, 'text/html');
            // $message->subject = "[SMITH] Produtividade dos colaboradores em ".  MetodosGerais::dataBrasileira($dataAtual);

            // $message->addTo($destinatario);
            // $message->addBCC("contato@vivainovacao.com");
            // //$message->addBCC("lucascardoso@vivainovacao.com");
            // $swiftAttachment = Swift_Attachment::fromPath($nome); // create a Swift Attachment
            // $message->attach($swiftAttachment);
            // Yii::app()->mail->send($message);

            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo, array de nomes dos arquivos);
            SendMail::send('lucascardoso@vivainovacao.com', array($destinatario), '[SMITH] Produtividade dos colaboradores em ' . MetodosGerais::dataBrasileira($dataAtual), $body, 'contato@vivainovacao.com', array(utf8_decode('Relatório de produtividade - '. $key .'.pdf')));
        }


    }

}
?>
