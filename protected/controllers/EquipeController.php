<?php

class EquipeController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $title_action = "";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'view' action
                'actions' => array('index', 'update', 'create', 'delete', 'carregarEquipesAjax', 'createAjax', 'createAjaxEquipe', 'getEquipes', 'getCoordenadorByEquipe'),
                'groups' => array('empresa', 'root', 'demo'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->title_action = Yii::t("smith", Yii::t('smith', "Criar Equipe"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Criar Equipe"));
        $model = new Equipe;
        $meta = new EquipeHasMeta;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Equipe'])) {
            $start = MetodosGerais::inicioContagem();

            $model->attributes = $_POST['Equipe'];
            $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
            if (isset($user->fk_empresa))
                $model->fk_empresa = $user->fk_empresa;

            if ($model->save()) {
                $meta->fk_empresa = $model->fk_empresa;
                $meta->fk_equipe = $model->id;
                $meta->data = date('Y-m-d H:i:s');
                $meta->meta = $model->meta;
                $meta->save();
                if (isset($_POST['Membros'])) {
                    foreach ($_POST['Membros'] as $pessoa) {
                        $pessoa_model = Colaborador::model()->findByPk($pessoa);
                        $pessoa_model->fk_equipe = $model->id;
                        if ($pessoa_model->save()) {
                            ColaboradorHasEquipe::model()->saveRelation($pessoa, $model->id);
                        }
                    }
                }
                LogAcesso::model()->saveAcesso('Configurações', 'Criar Equipe', 'Criar', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Equipe inserida com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Equipe não pôde ser inserida.'));
            }
        }
        $model_pessoa = new Colaborador;
        $this->render('create', array(
            'model' => $model, 'model_pessoa' => $model_pessoa, 'historico' => $meta
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->title_action = Yii::t("smith", Yii::t('smith', "Atualizar Equipe"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Atualizar Equipe"));
        $model = $this->loadModel($id);
        $meta = new EquipeHasMeta;
        $hasmeta = EquipeHasMeta::model()->findByAttributes(array('fk_equipe' => $model->id), array('order' => 'data DESC'));
        (isset($hasmeta)) ? $model->meta = $hasmeta->meta : $model->meta = 60;
        $meta = new EquipeHasMeta;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Equipe'])) {
            $start = MetodosGerais::inicioContagem();
            if (isset($_POST['Membros'])) {
                //Retirando os antigos membros
                $membros_antigos = $model->proPessoas;
                foreach ($membros_antigos as $antigo) {
                    $antigo->fk_equipe = NULL;
                    $antigo->save(false);
                }
                //Adicionando novos membros
                foreach ($_POST['Membros'] as $pessoa) {
                    $pessoa_model = Colaborador::model()->findByPk($pessoa);
                    $pessoa_model->fk_equipe = $id;
                    if ($pessoa_model->save(false)) {
                        ColaboradorHasEquipe::model()->saveRelation($pessoa, $id);
                    }
                }
            }
            $oldMeta = $_POST['Equipe']['meta'];
            $newMeta = $model->meta;
            $model->attributes = $_POST['Equipe'];
            if ($model->save()) {
                if ($oldMeta != $newMeta) {
                    $meta->fk_empresa = $model->fk_empresa;
                    $meta->fk_equipe = $model->id;
                    $meta->data = date('Y-m-d H:i:s');
                    $meta->meta = $model->meta;
                    $meta->save();
                }
                LogAcesso::model()->saveAcesso('Configurações', 'Atualizar Equipe', 'Atualizar', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Equipe atualizada com sucesso.'));
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Equipe não pôde ser atualizada.'));
            }
        }

        $model_pessoa = new Colaborador;

        $this->render('update', array(
            'model' => $model, 'model_pessoa' => $model_pessoa, 'historico' => $meta
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $start = MetodosGerais::inicioContagem();

        $model = $this->loadModel($id);
        $membros_antigos = $model->proPessoas;
        foreach ($membros_antigos as $antigo) {
            $antigo->fk_equipe = NULL;
            $antigo->save(false);
        }
        $model->ativo = 0;
        $model->save(false);
        LogAcesso::model()->saveAcesso('Configurações', 'Deletar Equipe', 'Deletar', MetodosGerais::tempoResposta($start));

    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $start = MetodosGerais::inicioContagem();
        $this->title_action = Yii::t("smith", Yii::t('smith', "Equipes"));
        $this->pageTitle = Yii::t("smith", Yii::t('smith', "Equipes"));
        $model = new Equipe('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Equipe']))
            $model->attributes = $_GET['Equipe'];
        LogAcesso::model()->saveAcesso('Configurações', 'Equipes', 'Equipes', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Equipe::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pro-equipe-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCreateAjax()
    {
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $fk_empresa = $user->fk_empresa;
        $flag = false;
        if (isset($_POST['Equipe'])) {
            $equipeExists = Equipe::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
            if (isset($equipeExists))
                Equipe::model()->deleteAllByAttributes(array("fk_empresa" => $fk_empresa));
            foreach ($_POST['Equipe'] as $nome) {
                $flag = false;
                $model = new Equipe;
                $model->nome = $nome;
                $model->fk_empresa = $fk_empresa;
                if ($model->save())
                    $flag = true;
            }
            if ($flag) {
                $modelEmpresa = Empresa::model()->find(array("condition" => "id=$fk_empresa"));
                if ($modelEmpresa->passo_wizard <= 4) {
                    $modelEmpresa->passo_wizard = 4;
                    $modelEmpresa->save();

                }
                echo "Sucesso";
            }


        } else
            echo "Erro";

    }


    /**
     * Método auxiliar utilizado no wizard para carregar os fildset de colaboradores
     *
     */
    public function actionCarregarEquipesAjax()
    {
        $idUser = Yii::app()->user->id;
        $serial = UserGroupsUser::model()->findByAttributes(array("id" => $idUser));
        $serial = $serial->serial_empresa;
        $model = Colaborador::model()->findAllByAttributes(array("ativo" => 0, "serial_empresa" => $serial));
        $i = 0;
        foreach ($model as $colaborador) {
            echo '<div class="panel panel-default" style="margin: 0 10px 5px 10px">';
            echo '                 <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#colapse' . $colaborador->id . '">';

            echo CHtml::label($colaborador->ad, 'ad');
            echo '</a>
                                  </h4>
                              </div>';
            echo '<div id="colapse' . $colaborador->id . '" class="panel-collapse collapse" style="height: 0px;">';

            echo '<div class="panel-body">';
            echo '<div class="form-group  col-lg-4"><p>';
            echo CHtml::hiddenField('Colaborador[' . $i . '][id]', $colaborador->id);

            echo CHtml::label(Yii::t('smith', 'Nome'), 'nome');
            echo CHtml::textField('Colaborador[' . $i . '][nome]', $colaborador->nome, array('size' => 60, 'maxlength' => 255, 'class' => 'form-control firstname', 'onkeyup' => 'maiuscula(".firstname")'));
            echo '</p>
        </div>';
            echo '<div class="form-group  col-lg-4"><p>';
            echo CHtml::label(Yii::t('smith', 'Sobrenome'), 'nome');
            echo CHtml::textField('Colaborador[' . $i . '][sobrenome]', $colaborador->sobrenome, array('size' => 60, 'maxlength' => 255, 'class' => 'form-control lastname', 'onkeyup' => 'maiuscula(".lastname")'));
            echo '</p>
        </div>';
            echo '<div class="form-group  col-lg-4">';
            echo '<p>';
            echo CHtml::label(Yii::t('smith', 'Equipe'), 'fk_equipe');
            echo CHtml::dropdownlist('Colaborador[' . $i . '][fk_equipe]', $colaborador->fk_equipe, CHtml::listData(Equipe::model()->findAll(array('order' => 'nome', 'condition' => 'fk_empresa =' . UserGroupsUser::model()->findByPk(Yii::app()->user->id)->fk_empresa)), 'id', 'nome'), array("class" => "chzn-select", 'empty' => Yii::t('smith', "Selecione uma equipe"), "style" => "width:100%;"));
            echo '</p>';
            echo '</div>';


            echo '<div class="form-group  col-lg-4">
            <p>';
            echo CHtml::label(Yii::t('smith', 'Email'), 'email');
            echo CHtml::textField('Colaborador[' . $i . '][email]', $colaborador->email, array('size' => 60, 'maxlength' => 255, 'class' => 'form-control'));
            echo '</p>
        </div>
        <div class="form-group  col-lg-4">
            <p>';
            echo CHtml::label(Yii::t('smith', 'Salário'), 'salario');
            echo CHtml::textField('Colaborador[' . $i . '][salario]', $colaborador->salario, array('class' => 'form-control valor_' . $i . ''));
            echo '</p>
        </div>


        <div class="form-group  col-lg-4">
            <p>';
            echo CHtml::label(Yii::t('smith', 'Carga Horária Semanal'), 'horas_semana');
            echo CHtml::textField('Colaborador[' . $i . '][horas_semana]', $colaborador->horas_semana, array('size' => 60, 'maxlength' => 255, 'class' => 'form-control previstoHM'));
            echo ' </p>
        </div>';

            echo '</div></div></div>';
            echo '<script>
                        $(".chzn-select").chosen({no_results_text: "N&atilde;o encontrado"});
$(".date").datepicker({
            autoSize: true,
            changeMonth: true,
            changeYear: true,
            yearRange: "1940:2028",
            dateFormat: "dd/mm/yy"
        });
        $(".previstoHM").mask("99:99",{placeholder:" "});

        $(".valor_' . $i . '").maskMoney({
            symbol:"R$",
            showSymbol:true,
            thousands:".",
            decimal:",",
            symbolStay: false
        });

function maiuscula(id) {
    //palavras para ser ignoradas
    var wordsToIgnore = ["DOS", "DAS", "de", "do", "Dos", "Das"],
    minLength = 3;
    var str = $(id).val();
    var getWords = function(str) {
    return str.match(/\S+\s*/g);
    }
    $(id).each(function () {
    var words = getWords(this.value);
    $.each(words, function (i, word) {
    // somente continua se a palavra nao estiver na lista de ignorados
    if (wordsToIgnore.indexOf($.trim(word)) == -1 && $.trim(word).length > minLength) {
    words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
    } else{
    words[i] = words[i].toLowerCase();}
    });
    this.value = words.join("");
    });
    };
</script>';
            $i++;
        }
    }


    public function actionCreateAjaxEquipe()
    {
        if (!empty($_POST['equipe'])) {
            $model = new Equipe;
            $model->nome = $_POST['equipe'];
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            if ($model->save()) {
                $equipes = Equipe::model()->findAllByAttributes(array("fk_empresa" => $model->fk_empresa));
                echo "<option value=''>" . Yii::t('smith', 'Selecione') . "</option>";
                foreach ($equipes as $value) {
                    echo "<option value='{$value->id}'>{$value->nome}</option>";
                }
            }
        }
    }

    /**
     * @param $data
     * @param $row
     * @return string
     *
     * Método auxiliar utilizado na index que retorna uma string com a relação dos colaboradores
     * por equipe.
     */
    public function getColaboradores($data, $row)
    {
        $aux = "";
        $colaboradores = Colaborador::model()->findAllByAttributes(array("fk_equipe" => $data->id));
        $last = count($colaboradores);
        $i = 0;
        foreach ($colaboradores as $value) {
            if ($i == $last - 1)
                $aux .= $value->nomeCompleto . ".";
            else
                $aux .= $value->nomeCompleto . ", ";
            $i++;
        }
        return $aux;
    }


    /**
     * @param $data
     * @param $row
     * @return string
     *
     * Método auxiliar utilizado na index que retorna uma string do coordenador da equipe
     */
    public function getCoordenadorByEquipe($data, $row)
    {
        $coordenador = UserGroupsUser::model()->findByAttributes(array("fk_equipe" => $data->id));
        if ($coordenador != NULL)
            return $coordenador->nome;
        else
            return Yii::t('smith', 'Equipe sem coordenador associado');
    }

    /**
     * Método auxiliar utilizado para preencher o filtro de equipes das telas de pesquisas
     * dos relatórios via ajax.
     */
    public function actionGetEquipes()
    {
        $fk_empresa = MetodosGerais::getEmpresaId();
        $condicao = "fk_empresa=$fk_empresa AND id = " . MetodosGerais::getEquipe();

        if (Yii::app()->user->groupName != 'coordenador') {
            $condicao = ' fk_empresa = ' . $fk_empresa;
            echo CHtml::tag('option', array('value' => 'todas_equipes'), CHtml::encode(Yii::t("smith", "Todas")), true);
        }
        $equipes = Equipe::model()->findAll(array('condition' => $condicao), array("order" => "nome ASC"));
        foreach ($equipes as $d) {
            echo CHtml::tag('option', array('value' => $d->id), CHtml::encode($d->nome), true);
        }

    }

}
