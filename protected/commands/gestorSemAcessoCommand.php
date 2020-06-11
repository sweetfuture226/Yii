<?php

/**
 * Class gestorSemAcessoCommand
 *
 * CRON utilizado para verificar se o gestor de projetos está a mais de 15 dias sem acessar o sistema e enviar email
 * solicitando que ele acesse com mais frequência.
 */
class gestorSemAcessoCommand extends CConsoleCommand {

    
    public function run() {
        
        $sql = "SELECT nome , last_login , email FROM  `usergroups_user` "
                . "WHERE username NOT IN (SELECT username  FROM `usergroups_user` WHERE `email` LIKE '%admin%')"
                . " AND group_id = 3 ORDER BY last_login DESC";
        
        $command = Yii::app()->getDb()->createCommand($sql);
        $ultimoLogin = $command->queryAll();
        
        
        
        foreach ($ultimoLogin as $value){
            $today = date("d/m/Y");
            $last_login = explode(" ", $value['last_login']);
            $data = MetodosGerais::dataBrasileira($last_login[0]);
            $time_inicial = MetodosGerais::geraTimestamp($data);
            $time_final = MetodosGerais::geraTimestamp($today);
            $diferenca = $time_final - $time_inicial; 
            $dias = (int)floor( $diferenca / (60 * 60 * 24));
            
            if ($dias == 15){
                $corpo = "Bom dia, você possui $dias dias sem acessar o painel de controle do Viva Smith.";
                
                $corpo.= "<div><p>Atenciosamente Equipe Viva Inovação</p></div>";
                // $message = new YiiMailMessage;
                // $message->setBody($corpo, 'text/html');
                // $message->subject = "Ausência de Acesso Viva Smith";
                // $message->addTo("{$value['email']}");
                // $message->from = "notificacao@vivainovacao.com";
                // Yii::app()->mail->send($message);

                // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
                SendMail::send('notificacao@vivainovacao.com', array("{$value['email']}"), 'Ausência de Acesso Viva Smith', $corpo);
            }
            
            
        
        }
    }
}