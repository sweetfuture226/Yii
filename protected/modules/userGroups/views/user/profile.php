<?php
$this->breadcrumbs = array(
    'Perfil',
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user_groups_user-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'mainForm valid', 'enctype' => "multipart/form-data"),
));
?>

<!--	<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p> -->
<?php echo $form->errorSummary($model); ?>
<div class="modal fade" id="modal_logo" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog"
     style="display: none;">
    <div class="modal-dialog" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" type="button" class="close">×</button>
                <h4 class="modal-title"><?= Yii::t('smith', 'Nova logo'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div id="image-list"></div>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="modal-footer">
                <button id="close_modal" data-dismiss="modal" class="btn btn-default"
                        type="button"><?= Yii::t('smith', 'Fechar'); ?></button>
                <?php echo CHtml::button('Confirmar', array('class' => 'btn btn-info', 'id' => 'att_logo')); ?>
            </div>
        </div>
    </div>
</div>

<div class="form-group col-lg-4">
    <?php echo CHtml::label(Yii::t('smith', 'Logo da empresa'), 'company_image'); ?>
    <?php echo CHtml::fileField('company_image', ''); ?>
</div>

<!--COORDENADADAS DO CORTE PARA SEREM ENVIADAS-->
<input type="hidden" id="x" name="company_image[x]">
<input type="hidden" id="y" name="company_image[y]">
<input type="hidden" id="x2" name="company_image[x2]">
<input type="hidden" id="y2" name="company_image[y2]">
<input type="hidden" id="w" name="company_image[w]">
<input type="hidden" id="h" name="company_image[h]">

<div id="preview-pane" class="form-group col-lg-4">
    <div class="preview-container">
        <img id="logo_empresa"
             src="<?php echo Yii::app()->baseUrl . '/' . Empresa::model()->findByPk(MetodosGerais::getEmpresaId())->logo ?>">
        </div>
</div>
    <div style="clear: both"></div>

<div class="form-group col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t('smith', 'Login'), 'UserGroupsUser_username'); ?>
        <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 255, 'disabled' => 'disabled', 'class' => 'form-control ')); ?>
    </p>
</div>

<div class="form-group col-lg-4">
    <p>
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control ')); ?>
    </p>
</div>
<div style="clear: both"></div>

    <h4><?= Yii::t('smith', 'Redifinição de Senha') ?>:</h4>
<div class="form-group col-lg-4">
    <p>
        <label class="required" for="current_password"><?= Yii::t('smith', 'Senha atual') ?><span
                class="required"> *</span></label>
        <?php echo CHtml::passwordField('current_password', '', array('class' => 'form-control validate[required]')); ?>
    </p>
</div>

<div class="form-group col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t('smith', 'Nova senha'), 'UserGroupsUser_password'); ?>
        <?php echo CHtml::passwordField('UserGroupsUser[password]', '', array('class' => 'form-control ')); ?>
    </p>
</div>

<div class="form-group col-lg-4">
    <p>
        <?php echo CHtml::label(Yii::t('smith', 'Repita a nova senha'), 'UserGroupsUser_password_again'); ?>
        <?php echo CHtml::passwordField('UserGroupsUser[password_again]', '', array('class' => 'form-control validate[equals[UserGroupsUser_password]]')); ?>
    </p>
</div>

<div class="buttons">
    <div style="float: right; ">
        <?php echo CHtml::submitButton(Yii::t('smith', 'Atualizar'), array('class' => 'btn btn-info submitForm')); ?>
        </div>
</div>
<?php
$this->endWidget();
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/protected/vendors/Jcrop/js/jquery.Jcrop.min.js', CClientScript::POS_END);
?>

<script>
    jQuery(function ($) {
        var old_logo = $('#logo_empresa').attr('src');

        var jcrop_api,
            boundx,
            boundy,

        // Grab some information about the preview pane
            $preview = $('#preview-pane'),
            $pcnt = $('#preview-pane .preview-container'),
            $pimg = $('#preview-pane .preview-container img'),

            xsize = $pcnt.width(),
            ysize = $pcnt.height();

        $(document).on('change', '#company_image', function (event) {
            var fd = new FormData();
            fd.append('file', this.files[0]);

            file = this.files[0];

            reader = new FileReader();
            reader.onloadend = function (e) {
                showUploadedItem(e.target.result);
            };
            reader.readAsDataURL(file);
        });

        function showUploadedItem(source) {
            var list = $("#image-list"),
                img = document.createElement("img");

            img.src = source;
            img.id = 'preview';

            list.empty();
            list.append(img);

            $('#preview').Jcrop({
                boxWidth: $(window).width() * 0.7,
                boxHeight: $(window).height() * 0.7,
                onChange: updateCoords,
                onSelect: updateCoords,
                aspectRatio: 30 / 8
            }, function () {
                // Use the API to get the real image size
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                // Store the API in the jcrop_api variable
                jcrop_api = this;

                // Move the preview into the jcrop container for css positioning
                // $preview.appendTo(jcrop_api.ui.holder);
            });
            $(document).on('click', '.jcrop-tracker', function () {
                $('.jcrop-keymgr').attr('style', 'opacity: 0');
            });

            $('.jcrop-keymgr').attr('style', 'opacity: 0');
            $('#modal_logo').modal('show');
            $('#logo_empresa').attr('src', source);

            $(document).on('click', '#close_modal, .close', function () {
                $('#logo_empresa').attr('src', old_logo);
                $('#logo_empresa').attr('style', 'width: auto; height: 57px; margin-left: 0; margin-top: 0');
            });

            $(document).on('click', '#att_logo', function () {
                $('#logo_empresa').attr('height', '57');
                $('#modal_logo').modal('hide');
            });
        }

        function updateCoords(coords) {
            $('#x').val(coords.x);
            $('#y').val(coords.y);
            $('#x2').val(coords.x2);
            $('#y2').val(coords.y2);
            $('#w').val(coords.w);
            $('#h').val(coords.h);

            if (parseInt(coords.w) > 0) {
                var rx = xsize / coords.w;
                var ry = ysize / coords.h;

                $pimg.css({
                    width: Math.round(rx * boundx) + 'px',
                    height: Math.round(ry * boundy) + 'px',
                    marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                    marginTop: '-' + Math.round(ry * coords.y) + 'px'
                });
            }
        };
    });
</script>