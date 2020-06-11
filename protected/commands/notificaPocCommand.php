<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 13/05/2016
 * Time: 13:39
 */
class notificaPocCommand extends CConsoleCommand
{
    public function run()
    {
        $pocs = Empresa::model()->searchPoc()->getData();
        $arrayTH = array('Status', 'Cliente', 'Quantidade máquinas', 'Início captura', 'Última captura', 'Duração prevista POC', 'Duração real POC', 'Responsável', 'Email responsável', 'Vendedor', 'Email vendedor');
        $arrayRow = array();
        foreach ($pocs as $poc) {
            $utlitmaCaptura = LogAtividade::model()->findByAttributes(array('fk_empresa' => $poc->id), array('order' => 'data DESC'));
            $utlitmaCaptura = (!empty($utlitmaCaptura)) ? MetodosGerais::dataBrasileira($utlitmaCaptura->data) : 'Ainda não houve captura';
            $dataInicioPoc = LogAtividade::model()->findByAttributes(array('fk_empresa' => $poc->id));
            $dataInicioPoc = (!empty($dataInicioPoc)) ? MetodosGerais::dataBrasileira($dataInicioPoc->data) : 'Ainda não houve captura';
            $dataInicio = ($dataInicioPoc == 'Ainda não houve captura') ? date('Y-m-d') : MetodosGerais::dataAmericana($dataInicioPoc);
            $duracaoReal = MetodosGerais::DataDiff($dataInicio, date('Y-m-d')) . ' dias';
            $status = ($poc->ativo) ? 'Em andamento' : 'Cancelada';
            array_push($arrayRow, array($status, $poc->nome, $poc->colaboradores_previstos, $dataInicioPoc, $utlitmaCaptura, $poc->duracao . ' dias', $duracaoReal, $poc->responsavel, $poc->email, $poc->nomeContato, $poc->emailContato));
        }
        $html = MetodosGerais::formatTable($arrayTH, $arrayRow);
        $mensagem = "<p>Acompanhamento das POC's:</p> <br>";

        SendMail::send('notificacao@vivainovacao.com', array('lucascardoso@vivainovacao.com', 'albertmoreira@vivainovacao.com', 'henriqueabreu@vivainovacao.com', 'robsonferreira@vivainovacao.com'), 'Acompanhamento POC', $mensagem . $html);
    }
}