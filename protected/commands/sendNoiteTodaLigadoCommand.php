<?php

/**
 * Class AlertaColaboradorCommand
 *
 * CRON utilizado para envio de email informando as ausências de captura de produtividade dos colaboradores
 * separados por empresa.
 */
class sendNoiteTodaLigadoCommand extends CConsoleCommand
{


    public function run()
    {
        foreach (Empresa::model()->findAll() as $empresa) {

        }
        SendMail::send('lucascardoso@vivainovacao.com', array('smith@vivainovacao.com'), 'Colaboradores sem produtividade', $body);
    }
}

?>
