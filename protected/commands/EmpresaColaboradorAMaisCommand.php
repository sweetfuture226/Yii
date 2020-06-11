<?php

/**
 * Class EmpresaColaboradorAMaisCommand
 *
 * CRON utilizado para verificar se há captura de produtividade de usuários acima da quantidade do contratado.
 */
class EmpresaColaboradorAMaisCommand extends CConsoleCommand {


    public function run() {
        $table = "";
        foreach (Empresa::model()->findAll() as $empresa) {
            $sobra = 0;
            $colaboradores = Colaborador::model()->findAll(array('condition' => 'ativo = 1 AND fk_empresa = ' . $empresa->id));
            if ($empresa->colaboradores_previstos > 0 && count($colaboradores) > $empresa->colaboradores_previstos) {
                foreach ($colaboradores as $colaborador) {
                    $dias = count(GrfColaboradorConsolidado::model()->findAll(array('condition' => 'fk_colaborador = ' . $colaborador->id)));
                    if ($dias >= 3) $sobra++;
                }

                $sub = $sobra - $empresa->colaboradores_previstos;
                if ($sub > 0) {
                    $table .= '<tr style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;">
                        <td>' . $empresa->nome . '</td>
                        <td>' . $empresa->colaboradores_previstos . '</td>
                        <td>' . $sobra . '</td>
                        <td>' . $sub . '</td>
                    </tr>';
                }
            }
        }

        if ($table != "") {
            $html = '<div><table border="1px" class="table_custom" style="font-family: arial,sans-serif; border-spacing: 0;border: 0;border-collapse:collapse;">
                    <tr>
                        <th colspan="4" style="font-size: 14px;padding: 10px;text-align:center;border: 1px solid #CCC;text-align: center">Empresas com colaboradore ativos a mais que o previsto</th>
                    </tr>
		            <tr style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;">
                        <th>Empresa</th>
                        <th>Colaboradores previstos</th>
                        <th>Colaboradores ativos</th>
                        <th>Colaboradores a mais</th>
    	            </tr>';
            $html .= $table;
            $html .= '</table></div>';

            // SendMail::send('Empresas com colaboradores a mais','smith@vivainovacao.com','',$html);
            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send('smith@vivainovacao.com', array('smith@vivainovacao.com'), 'Empresas com colaboradores a mais', $html);
        }
    }
}
