<?php

class ColaboradorHasFeriasController extends Controller
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
            'userGroupsAccessControl',
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
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('feriasAjax', 'delete', 'CreateFerias'),
                'groups' => array('coordenador', 'empresa', 'root', 'demo'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */

    public function actionDelete($id)
    {
        $ferias = $this->loadModel($id);
        $ferias->delete();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CActiveRecord ColaboradorHasFerias the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ColaboradorHasFerias::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Método auxiliar utilizado para salvar um novo período de férias do colaborador, a partir da grid de
     * ColaboradorHasFalta e grid de Atualizar Colaborador.
     */
    public function actionFeriasAjax()
    {
        if (!empty($_POST['feriasInicio']) && !empty($_POST['feriasFim'])) {
            $modelFerias = new ColaboradorHasFerias();
            $modelFerias->data_inicio = MetodosGerais::dataAmericana($_POST['feriasInicio']);
            $modelFerias->data_fim = MetodosGerais::dataAmericana($_POST['feriasFim']);
            $modelFerias->fk_colaborador = $_POST['pessoa'];
            $modelFerias->fk_empresa = MetodosGerais::getEmpresaId();
            $modelFerias->descricao = isset($_POST['descricao']) ? $_POST['descricao'] : 'não informado';
            if ($modelFerias->save()) {
                ColaboradorHasFalta::model()->deleteAll(array('condition' => "fk_colaborador =" . $modelFerias->fk_colaborador . " and data between '" . $modelFerias->data_inicio
                    . "'and '" . $modelFerias->data_fim . "'"));
                return true;
            }
        } else
            return false;
    }

    public function actionCreateFerias($fk_colaborador, $descricao, $data_inicio, $data_fim)
    {

        $data_inicio = MetodosGerais::dataAmericana($data_inicio);
        $data_fim = MetodosGerais::dataAmericana($data_fim);
        $model = new ColaboradorHasFerias();
        $model->fk_colaborador = $fk_colaborador;
        $model->fk_empresa = MetodosGerais::getEmpresaId();
        $model->data_inicio = $data_inicio;
        $model->data_fim = $data_fim;
        $model->descricao = $descricao;
        if ($model->save()) {
            ColaboradorHasFalta::model()->deleteAll(array('condition' => "fk_colaborador =" . $fk_colaborador . " and data between '" . $data_inicio . "'and '" . $data_fim . "'"));
            ColaboradorSemProdutividade::model()->deleteAll(array('condition' => "fk_colaborador = " . $fk_colaborador . " AND data >= '$data_inicio' AND data <= '$data_fim'"));
            echo "success";
        } else {
            echo "<pre>";
            print_r($model->getErrors());
            echo "</pre>";
        }
    }
}
