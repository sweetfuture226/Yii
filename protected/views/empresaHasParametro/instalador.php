<?php 
$this->breadcrumbs=array(
	Yii::t("smith", "Instalador"),
);

?>


<div class="form-group  col-lg-8">
	<span><?=Yii::t("smith", 'Faça o download do instalador para novas máquinas a serem monitoradas pelo Viva Smith. Para qualquer dúvida,')?> <a  data-toggle="modal" href="#reportarErro"> <?=Yii::t("smith", 'clique aqui')?></a></span>
	<br><span><?= Yii::t('smith', 'O serial requerido para a instalação nas máquinas é:'); ?> <b><?=MetodosGerais::getSerial();?></b></span>
	<p>
		<a href="../public/instalador/Smith-2.04.exe" class="btn btn-success"
		   style="text-align: center; margin-top: 30px;"><?= Yii::t('smith', 'BAIXAR INSTALADOR'); ?></a>
	</p>
</div>