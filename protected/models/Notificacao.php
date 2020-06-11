<?php

/**
 * This is the model class for table "notificacao".
 *
 * The followings are the available columns in table 'notificacao':
 * @property integer $id
 * @property string $notificacao
 * @property string $fk_usuario
 * @property integer $tipo
 * @property string $action
 * @property integer $fk_empresa
 * @property string $data_notificacao
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property UsergroupsUser $fkUsuario
 *
 * Os tipos de notificacao
 * $TP_MUDAR_SENHA
 */
class Notificacao extends CActiveRecord {

    //Tipo de Notificações
    public static $TP_MUDAR_SENHA = 1;
    public static $TP_PREENCHER_COLABORADOR = 2;
    public static $TP_CHANGELOG = 3;
    public static $TP_PRIMEIRO_ACESSO = 4;
    public static $TP_IMPLANTATION_AFTER_DAYS = 5;


    public static $TP_FALTA_COLABORADOR = 6;
    public static $TP_METRICA_COLABORADOR = 7;
    public static $TP_COLABORADOR_SEM_METRICA = 8;
    public static $TP_PREENCHER_COLABORADOR_ATIVO = 9;
    const STATUS_LIDA = 1;
    const STATUS_NOVA = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'notificacao';
    }

    public function beforeSave() {
        $this->data_notificacao = date('Y-m-d H:i:s');
        $antigas = Notificacao::model()->findAll(array('condition' => 'tipo = ' . $this->tipo . ' AND action = "' . $this->action . '"'));
        if (!empty($antigas))
            foreach ($antigas as $antiga)
                $antiga->delete();
        return parent::beforeSave();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('notificacao, fk_usuario, tipo', 'required'),
            array('tipo, status', 'numerical', 'integerOnly' => true),
            array('notificacao', 'length', 'max' => 255),
            array('fk_usuario', 'length', 'max' => 11),
            array('data_notificacao', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, notificacao, fk_usuario, tipo, data_notificacao, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'fkUsuario' => array(self::BELONGS_TO, 'UsergroupsUser', 'fk_usuario'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'action' => 'Action',
            'fk_empresa' => 'Empresa',
            'data_notificacao' => 'Data Notificacao',
            'status' => 'Status',
            'notificacao' => Yii::t("smith", 'Notificação'),
            'fk_usuario' => Yii::t("smith", 'Usuário'),
            'tipo' => Yii::t("smith", 'Tipo'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('notificacao', $this->notificacao, true);
        $criteria->compare('fk_usuario', $this->fk_usuario, true);
        $criteria->compare('tipo', $this->tipo);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->compare('data_notificacao', $this->data_notificacao, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Notificacao the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getNotificacoes($id_usuario, $tipo = NULL) {
        if ($tipo == NULL)
            return $this->findAll(
                array("condition" => "(fk_usuario = :fk_usuario OR fk_empresa = :fk_empresa) AND (tipo <> :tipo AND tipo <> :tipoSenha AND tipo <> :tipoColaborador) AND (status IS NULL OR status IS FALSE)",
                    "params" => array(":fk_usuario" => $id_usuario, ":fk_empresa" => MetodosGerais::getEmpresaId(), ":tipo" => self::$TP_CHANGELOG,':tipoSenha'=>self::$TP_PRIMEIRO_ACESSO, ":tipoColaborador" => self::$TP_PREENCHER_COLABORADOR_ATIVO)));
        else
            return $this->findAll(
                array(
                    "condition" => "fk_usuario = :id_usuario AND tipo = :tipo",
                    "params" => array(":id_usuario" => $id_usuario, ":tipo" => $tipo)));
    }

    public function existeTipo($id_usuario, $tipo) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'fk_usuario=:fk_usuario AND tipo=:tipo';
        $criteria->params = array(':fk_usuario' => $id_usuario, ':tipo' => $tipo);
        $notificao = Notificacao::model()->find($criteria);
        return ($notificao != NULL) ? true : false;
    }

    public function mudarSenha($id_usuario, $msg) {
        if (!$this->existeTipo($id_usuario, self::$TP_MUDAR_SENHA)) {
            $notificacao = new Notificacao();
            $notificacao->fk_usuario = $id_usuario;
            $notificacao->tipo = self::$TP_MUDAR_SENHA;
            $notificacao->notificacao = $msg;
            $notificacao->action = 'userGroups/user/profile/id/' . $id_usuario;
            $notificacao->save();
        }
    }

    public function removerMudarSenha($id_usuario) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'fk_usuario=:fk_usuario AND tipo=:tipo';
        $criteria->params = array(':fk_usuario' => $id_usuario, ':tipo' => self::$TP_MUDAR_SENHA);
        $notificao = Notificacao::model()->find($criteria);
        if ($notificao != NULL) {
            $notificao->delete();
        }
    }

    public function preencherColaborador($id_usuario, $msg) {
        if (!$this->existeTipo($id_usuario, self::$TP_PREENCHER_COLABORADOR)) {
            $notificacao = new Notificacao();
            $notificacao->fk_usuario = $id_usuario;
            $notificacao->tipo = self::$TP_PREENCHER_COLABORADOR;
            $notificacao->notificacao = $msg;
            $notificacao->action = 'Colaborador/index';
            $notificacao->save();
        }
    }

    public function removerPreencherColaborador($id_usuario) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'fk_usuario=:fk_usuario AND tipo=:tipo';
        $criteria->params = array(':fk_usuario' => $id_usuario, ':tipo' => self::$TP_PREENCHER_COLABORADOR);
        $notificao = Notificacao::model()->find($criteria);
        if ($notificao != NULL) {
            $notificao->delete();
        }
    }

    public function getChangeLog() {
        $changeLogs = $this->verificaChangeLog();
        $changes = array();
        $logsTexto = "";
        if (!empty($changeLogs)) {
            foreach ($changeLogs as $id => $logs) {
                $changes['id'][] = $id;
                $logsTexto .= file_get_contents(Yii::app()->getBaseUrl(true) . '/' . $logs);
            }
            $changes['texto'] = $logsTexto;
            return $changes;
        }
        return NULL;
    }

    public function verificaChangeLog() {
        $changeLog = Changelog::model()->findAll(array("order" => 'id DESC'));
        $caminhosChange = array();
        foreach ($changeLog as $change) {
            $changelogNotificao = Notificacao::model()->findByAttributes(array("tipo" => self::$TP_CHANGELOG, "action" => $change->id, 'fk_usuario' => Yii::app()->user->id));
            if (!isset($changelogNotificao)) {
                $caminhosChange[$change->id] = $change->caminho;
            }
        }
        return $caminhosChange;
    }

    public function alterarSenha() {
        $notificacao = Notificacao::model()->findByAttributes(array("tipo" => self::$TP_PRIMEIRO_ACESSO, 'fk_usuario' => Yii::app()->user->id));
        if (!isset($notificacao))
            return true;
        else
            return false;
    }

    public function colaboradorHasFalta($idUsuario,$msg){
        if(!$this->existeTipo($idUsuario, self::$TP_FALTA_COLABORADOR)){
            $notificacao = new Notificacao();
            $notificacao->fk_usuario = $idUsuario;
            $notificacao->fk_empresa = MetodosGerais::getEmpresaId();
            $notificacao->tipo = self::$TP_FALTA_COLABORADOR;
            $notificacao->notificacao = $msg;
            $notificacao->action = 'ColaboradorHasFalta/index';
            $notificacao->save();
        }
    }

    public function removerColaboradorHasFalta($idUsuario){
        $criteria = new CDbCriteria;
        $criteria->condition = 'fk_usuario=:fk_usuario AND tipo=:tipo';
        $criteria->params = array(':fk_usuario'=>$idUsuario, ':tipo'=>self::$TP_FALTA_COLABORADOR);
        $notificao = Notificacao::model()->find($criteria);
        if ($notificao != NULL){
            $notificao->delete();
        }
    }

    public function colaboradorSemMetrica($idUsuario,$msg){
        if(!$this->existeTipo($idUsuario, self::$TP_COLABORADOR_SEM_METRICA)){
            $notificacao = new Notificacao();
            $notificacao->fk_usuario = $idUsuario;
            $notificacao->fk_empresa = MetodosGerais::getEmpresaId();
            $notificacao->tipo = self::$TP_COLABORADOR_SEM_METRICA;
            $notificacao->notificacao = $msg;
            $notificacao->action = 'ColaboradorSemMetrica/index';
            $notificacao->save();
        }
    }

    public function removerColaboradorSemMetrica($idUsuario){
        $criteria = new CDbCriteria;
        $criteria->condition = 'fk_usuario=:fk_usuario AND tipo=:tipo';
        $criteria->params = array(':fk_usuario'=>$idUsuario, ':tipo'=>self::$TP_COLABORADOR_SEM_METRICA);
        $notificao = Notificacao::model()->find($criteria);
        if ($notificao != NULL){
            $notificao->delete();
        }
    }

    /**
     * Notifica o usuário sobre alguma informação
     * @param int $user_id
     * @param int $type
     */
    public function notifyUser($user_id, $type) {
        $model = new Notificacao;
        $model->fk_usuario = $user_id;
        $model->action = '';
        $model->tipo = $type;

        if ($type == self::$TP_IMPLANTATION_AFTER_DAYS) {
            $model->notificacao = "Não deixe de visualizar o setor de contratos";
            $model->action = "Contrato/index";
        }
        return $model->save();
    }
}
