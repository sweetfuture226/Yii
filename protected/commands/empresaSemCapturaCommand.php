<?php

/**
 * Class EmpresaSemCapturaCommand
 *
 * CRON utilizado para verificar se há empresa sem captura de informações por mais de 48h.
 */
class EmpresaSemCapturaCommand extends CConsoleCommand
{
    public function run()
    {
        $empresas = Empresa::model()->findAll(array('condition' => 'ativo = 1'));
        $table = '';
        foreach ($empresas as $empresa) {
            $ultimoColSemProd = GrfColaboradorConsolidado::model()->find(array('condition' => 'fk_empresa = ' . $empresa->id, 'order' => 'data DESC'));
            if (isset($ultimoColSemProd) && $this->dias_uteis(strtotime($ultimoColSemProd->data), strtotime(date('Y-m-d'))) > 2) {
                $ultimoRegistro = EmpresaSemCaptura::model()->find(array('condition' => 'fk_empresa = ' . $empresa->id, 'order' => 'ultima_captura DESC'));

                if (!isset($ultimoRegistro->id)) {
                    $semCaptura = new EmpresaSemCaptura;
                    $semCaptura->fk_empresa = $empresa->id;
                    $semCaptura->ultima_captura = $ultimoColSemProd->data;
                    $semCaptura->save();

                    $table .= '<tr style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;">
                        <td>' . $empresa->nome . '</td>
                        <td>' . MetodosGerais::dataBrasileira($ultimoColSemProd->data) . '</td>
                    </tr>';
                }
            }
        }

        if ($table != '') {
            $html = '<div><table border="1px" class="table_custom" style="font-family: arial,sans-serif; border-spacing: 0;border: 0;border-collapse:collapse;">
                    <tr><th colspan="3" style="font-size: 14px;padding: 10px;text-align:center;border: 1px solid #CCC;text-align: center">Empresas com mais de 48h sem captura</th></tr>
                    <tr style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;">
                      <th>Empresa</th>
                      <th>Última captura</th>
                    </tr>';
            $html .= $table;
            $html .= '</table></div>';

            // SendMail::send('Empresas sem captura por mais de 48h', 'lucascardoso@vivainovacao.com', '', $html);
            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), 'Empresas sem captura por mais de 48h', $html);
        }
    }

    public function dias_uteis($diaIni, $diaFim)
    {
        $countUteis = 0;
        while ($diaIni <= $diaFim) {
            $dS = date("w", $diaIni);
            if ($dS != "0" && $dS != "6") {
                $countUteis++;
            }
            $diaIni += 86400;
        }
        return $countUteis;
    }
}
