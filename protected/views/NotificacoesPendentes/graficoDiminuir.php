<h4> <?php echo Yii::t('smith', 'Informativo - Redução de produtividade') ?></h4>
<p style="text-align: justify"><?php echo Yii::t('smith', 'Conforme comparativo dos últimos 7 dias com o mesmo período anterior, os usuários abaixo apresentaram, respectivamente, uma redução de produtividade. É hora de analisar os dados com cautela e, se detectados pontos de aperfeiçoamento, alinhar com o colaborador as ações de melhoria.') ?></p>
<table class="table table-bordered datatable dataTable tableNotificacoes">
    <thead>
    <tr>
        <th style="background-color: #eee !important;"><strong><?php echo Yii::t('smith', 'Colaborador'); ?></strong>
        </th>
        <th style="background-color: #eee !important;"><strong>%</strong></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($value as $obj) {
        echo '<tr>
                    <td style="width: 90%">' . $obj['Colaborador'] . '</td>' .
            '<td style="width: 10%"> <i class="fa fa-level-down" aria-hidden="true" style="color : red;"></i> ' . $obj['Aumento'] . '% </td>
                  </tr>';
    } ?>
    </tbody>
</table>