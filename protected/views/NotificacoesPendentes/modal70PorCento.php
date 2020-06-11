<h4> <?php echo Yii::t('smith', 'Informativo - Documentos com 70% de tempo previsto consumido') ?></h4>
<p style="text-align: justify"><?php echo Yii::t('smith', 'Os documentos abaixo já possuem 70% de tempo previsto consumido. Neste caso, sugerimos verificar o status de desenvolvimento, a fim de confirmar que o progresso se encontra, de fato, em 70%.') ?></p>
<table class="table table-bordered datatable dataTable tableNotificacoes">
    <thead>
    <tr>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Contrato') ?></strong></th>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Documento') ?></strong></th>
        <th style="background-color: #eee !important;">
            <strong><?php echo Yii::t('smith', 'Último usuário a abrir') ?></strong></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($value as $obj) {

        echo '<tr><td>' . $obj->contrato->nome . '</td><td>' . $obj->nome . '</td><td>' . NotificacoesPendencias::getUltimoUsuarioAbrirDocumento($obj->nome) . '</td></tr>';


    } ?>
    </tbody>
</table>

