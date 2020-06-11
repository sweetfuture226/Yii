<?php

/**
 * Class UltimoLoginCommand
 *
 * CRON utilizado para verificar a data dos ultimos acessos dos clientes ao sistema.
 */
class UltimoLoginCommand extends CConsoleCommand {


    public function run() {
        $empresas = Empresa::model()->findAll();
        $ultimoLogin = $detalhesEmpresa = array();
        foreach ($empresas as $value){
            $sql = "SELECT user.last_login , user.email, sum(pe.status) as  ativos, emp.nome as empresa, emp.serial as serialEmpresa,emp.id as fk_empresa FROM  `usergroups_user` as user
                INNER JOIN colaborador as pe ON pe.fk_empresa = user.fk_empresa
                INNER JOIN empresa as emp ON emp.id = user.fk_empresa
                WHERE user.username NOT IN (SELECT username  FROM `usergroups_user` WHERE `email` LIKE '%admin%')
                AND user.fk_empresa = $value->id
                AND pe.status = 1 and pe.ativo = 1
                GROUP BY user.id
                ORDER BY user.last_login DESC
                LIMIT 1
                ";
            $command = Yii::app()->getDb()->createCommand($sql);
            $resultado = $command->queryAll();

            if(isset($resultado[0]))
                array_push($ultimoLogin,$resultado[0]);
        }

        $alertaEmpresa = array();

        $html = "<div><table border='1px'>";
        $html .= "<tr><th  colspan='5' >Últimos logins das empresas no Viva Smith</th></tr>
		            <tr style='background-color: #CCC;text-decoration: bold;'>
		              <th>Data</th>
		              <th>Horário</th>
		              <th>Empresa</th>
		              <th>Usuários Ativos</th>
		              <th>Observações</th>
		            </tr>";
        $totalAtivos = 0;
        foreach ($ultimoLogin as $value){
            $observacoes = "";
                if(isset($value['last_login'])){
                    $totalAtivos += $value['ativos'];
                    $last_login = explode(" ", $value['last_login']);
                    $data = MetodosGerais::dataBrasileira($last_login[0]);
                    $horario = $last_login[1];
                    $h2 = strtotime($horario);
                    $h1 = strtotime('03:00:00');
                    $horario = mktime(date('H', $h2) - date('H', $h1), date('i', $h2) - date('i', $h1), date('s', $h2) - date('s', $h1));
                    $horario = date('H:i:s',$horario);
                    $html .= "<tr><td>$data</td>";
                    $html .= "<td>".$horario."</td>";
                    $html .= "<td>".$value['empresa']."</td>";
                    $html .= "<td>".$value['ativos']."</td>";

                    $colInativos = Colaborador::model()->findAllByAttributes(array('ativo' => 0, 'serial_empresa' => $value['serialEmpresa']));
                    $sites = SitePermitido::model()->findAllByAttributes(array('fk_empresa' => $value['fk_empresa']));
                    $programas = ProgramaPermitido::model()->findAllByAttributes(array('fk_empresa' => $value['fk_empresa']));


                    if(count($colInativos)>0)
                        $observacoes .= 'Há '.count($colInativos).' colaboradores com perfil incompleto, ';
                    if(count($sites)==0)
                        $observacoes .= 'Não há sites cadastrados, ';
                    if(count($programas)==0)
                        $observacoes .= 'Não há programas cadastrados,';
                    $html .= "<td>".$observacoes."</td></tr>";
                    array_push($alertaEmpresa, array("empresa"=>$value['empresa'],"email"=>$value['email'],"data"=>$data));
                }
        }
        $html .= "<tr>
                    <td colspan='4'>Total usuários ativos</td>
                    <td>$totalAtivos</td>
                </tr>";
        $html .= "</table></div>";
        // SendMail::send('Ultimos logins','smith@vivainovacao.com','',$html);
        // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
        SendMail::send('smith@vivainovacao.com', array('smith@vivainovacao.com'), 'Últimos logins', $html);
    }
}

?>
