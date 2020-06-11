<?php

/**
 * Class enviaEmailProdutivoCommand
 *
 * CRON utilizado para enviar um relatório PDF por email para o gestor, informando a produtividade dos colaboradores durante a semana.
 */
class enviaEmailProdutivoCommand extends CConsoleCommand {

    public function run() {
        $dataAtual = date('Y-m-d');
        $pdf = array();
        $dir = opendir(Yii::app()->baseUrl . "/../public/produtivo");
        while (false !== ($filename = readdir($dir))) {
            if ($filename !== '.' AND $filename !== '..') {
                $empresa = explode('-', $filename);
                $empresa = explode('.', $empresa[1]);
                $pdf[$empresa[0]][] = $filename;
            }
        }

        foreach ($pdf as $value) {
            $body = "Segue em anexo os relatório com a produtividade dos colaboradores em " . MetodosGerais::dataBrasileira($dataAtual);
            $body.= "<div><p>Atenciosamente Equipe Viva Inovação</p></div>";
            // $message = new YiiMailMessage;
            // $message->setBody($body, 'text/html');
            // $message->subject = "[SMITH] Produtividade em " . MetodosGerais::dataBrasileira($dataAtual);
            // $message->addTo("lucascardoso@vivainovacao.com");
            // foreach ($value as $file) {
            //     $swiftAttachment = Swift_Attachment::fromPath("/opt/lampp/htdocs/vivasmith.com/public/produtivo/" . $file); // create a Swift Attachment
            //     $message->attach($swiftAttachment);
            // }
            // $message->from = "lucascardoso@vivainovacao.com";
            // Yii::app()->mail->send($message);

            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo, array de nomes dos arquivos);
            SendMail::send('lucascardoso@vivainovacao.com', array('lucascardoso@vivainovacao.com'), '[SMITH] Produtividade em ' . MetodosGerais::dataBrasileira($dataAtual), $body, null, $value);
        }
    }

}
