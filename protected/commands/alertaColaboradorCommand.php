<?php

/**
 * Class AlertaColaboradorCommand
 *
 * CRON utilizado para envio de email informando as ausências de captura de produtividade dos colaboradores
 * separados por empresa.
 */
class AlertaColaboradorCommand extends CConsoleCommand {


    public function run() {


        $sql = "SELECT c.nome as colaborador , c.data , emp.nome as empresa FROM colaborador_sem_produtividade as c
                INNER JOIN empresa as emp ON emp.id = c.fk_empresa  
                WHERE c.nome IS NOT NULL AND emp.id != 2 ORDER BY c.data
                ";


        $command = Yii::app()->getDb()->createCommand($sql);
        $colaboradores = $command->queryAll();

        $improdutivos = array();
        foreach ($colaboradores as $value){
            $mes = explode('-', $value['data']);
            $dia = $mes[2];
            $mes = MetodosGerais::mesString($mes[1]);
            $data = MetodosGerais::dataBrasileira($value['data']);
            $improdutivos[$value['empresa']][$value['colaborador']][$mes][] = $dia;
        }


        $html = "<div><table border='1px'>";
        foreach ($improdutivos as $key=>$value){
            $html .= "<thead><th>Empresa - [ $key ]</th><th>Data</th></thead>";
            foreach ($value as $chave=>$colaborador){
               $conjuntoDatas = "";
               $html .= "<tr><td>{$chave}</td>";
               foreach ($colaborador as $month=>$days){
                   $conjuntoDatas .= $month ."(";
                   $dias = "";
                   foreach ($days as $day)
                       $dias .= $day ."," ;
                   $conjuntoDatas .= $dias . ") , ";

               }
               $html .= "<td>{$conjuntoDatas}</td></tr>";
            }
        }
        $html .= "</table></div>";
        $body = $html;
        $body.= "<div><p>Atenciosamente Equipe Viva Inovação</p></div>";
        // $message = new YiiMailMessage;
        // $message->setBody($body, 'text/html');
        // $message->subject = "Colaboradores sem produtividade";
        // $message->addTo("smith@vivainovacao.com");
        // $message->from = "lucascardoso@vivainovacao.com";
        // Yii::app()->mail->send($message);

        // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
        SendMail::send('lucascardoso@vivainovacao.com', array('smith@vivainovacao.com'), 'Colaboradores sem produtividade', $body);
    }
}
?>
