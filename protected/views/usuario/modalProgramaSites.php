<form id="programa-form">
<div class="modal fade" id="solicitacaoP" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <div style="clear: both"></div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?=Yii::t('wizard','Solicitação de validação de novos programas')?></h4>
            </div>
            <div class="modal-body">

                <div class="form-group  col-lg-6"> 
                    <p>
                        <label><?=Yii::t('wizard','Programa')?></label>
                        <input type="text"  class="form-control" name="programa" id="programa"  /></p>
                </div>
                <div class="form-group  col-lg-6"> 
                    <p>
                        <label><?=Yii::t('wizard','Site do fabricante')?></label>
                        <input type="text"  class="form-control" name="site" id="site"  /></p>
                </div>
                


            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <!--Notification Start -->
                <div class="notifications" style="float: left;">
                    <div  id="notification" class="alert alert-success fade " style="margin-bottom: 0px !important">
                    
                    <strong><i class="icon-ok-sign"></i><?=Yii::t('wizard','Pedido solicitado!')?></strong>
                 </div>
                </div>
                <!--Notification End -->
                <button data-dismiss="modal" class="btn btn-default" type="button"><?=Yii::t('wizard','Fechar')?></button>
                <input class="btn btn-info submitForm" type="button" name="enviarEmailP" id="enviarEmailP" onclick="enviarEmailPrograma();" value="Enviar">                                          </div>
        </div>
    </div>
</div>
</form>

<form id="site-form">
<div class="modal fade" id="solicitacaoS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?=Yii::t('wizard','Solicitação de validação de novos sites')?></h4>
            </div>
            <div class="modal-body">

                <div class="form-group  col-lg-12"> 
                    <p>
                        <label>Site</label>
                        <input type="text" title="<?=Yii::t('wizard','Para inserir mais de um site na lista, separe-os por vírgulas')?>" class="form-control" name="site" id="site"  /></p>
                </div>
                
                <div style="clear: both"></div>




            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <div class="notifications" style="float: left;">
                    <div  id="notification2" class="alert alert-success fade " style="margin-bottom: 0px !important">
                    
                    <strong><i class="icon-ok-sign"></i><?=Yii::t('wizard','Pedido solicitado!')?> </strong>
                 </div>
                </div>
                <button data-dismiss="modal" class="btn btn-default" type="button"><?=Yii::t('wizard','Fechar')?></button>
                <input class="btn btn-info submitForm" type="button" name="enviarEmailS" id="enviarEmailS" onclick="enviarEmailSite();" value="Enviar">                                             </div>
        </div>
    </div>
</div>
</form>