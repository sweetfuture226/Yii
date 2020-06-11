<?php

/**
 * Class ColaboradorCommand
 *
 * CRON utilizado para alimentar a tabela ColaboradorSemProdutividade, verificando quais colaboradores não tiverem produtividade
 * capturadas no dia.
 *
 */
class ColaboradorCommand extends CConsoleCommand {


    public function run() {
        $dataAtual = gmdate('Y-m-d', time()-(3600*27));
        $sql = "SELECT CONCAT(pe.nome,' ',pe.sobrenome) as nome , pe.fk_empresa, pe.id
                FROM  colaborador AS pe
                WHERE pe.id not in (SELECT fk_colaborador FROM grf_colaborador_consolidado WHERE data = '$dataAtual')
                AND ativo = 1
                AND status = 1
                ORDER BY fk_empresa";
        $command = Yii::app()->getDb()->createCommand($sql);
        $colaboradores = $command->queryAll();
        for($i=0;$i<count($colaboradores);$i++){
            $model = new ColaboradorSemProdutividade();
            $model->nome = $colaboradores[$i]['nome'];
            $model->data = $dataAtual;
            $model->fk_empresa = $colaboradores[$i]['fk_empresa'];
            $model->fk_colaborador = $colaboradores[$i]['id'];
            $model->save();
        }

    }

}
?>
