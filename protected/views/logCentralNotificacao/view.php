<?php
$this->breadcrumbs = array(
    'Log Central Notificacaos' => array('index'),
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'fk_acao',
        'fk_documento_sem_contrato',
        'descricao',
        'tipo',
        'fk_empresa',
    ),
)); ?>
