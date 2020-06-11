<?php

/**
 * This is the model class for table "log_programa_consolidado".
 *
 * The followings are the available columns in table 'log_programa_consolidado':
 * @property integer $id
 * @property string $usuario
 * @property string $programa
 * @property string $descricao
 * @property string $duracao
 * @property string $data
 * @property string $title_completo
 * @property string $nome_host
 * @property string $host_domain
 * @property string $serial_empresa
 */
class LogProgramaConsolidado extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'log_programa_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('serial_empresa', 'required'),
            array('usuario, programa, descricao', 'length', 'max' => 255),
            array('title_completo', 'length', 'max' => 1024),
            array('nome_host', 'length', 'max' => 45),
            array('host_domain', 'length', 'max' => 64),
            array('serial_empresa', 'length', 'max' => 36),
            array('duracao, data', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, usuario, programa, descricao, duracao, data, title_completo, nome_host, host_domain, serial_empresa', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'usuario' => Yii::t("smith", 'Usuário'),
            'programa' => Yii::t("smith", 'Programa'),
            'descricao' => Yii::t("smith", 'Descrição'),
            'duracao' => Yii::t("smith", 'Duração'),
            'data' => Yii::t("smith", 'Data'),
            'title_completo' => Yii::t("smith", 'Titulo Completo'),
            'nome_host' => Yii::t("smith", 'Horário'),
            //'host_domain' => 'Host Domain',
            //'serial_empresa' => 'Serial Empresa',
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
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('descricao', $this->descricao, true);
        $criteria->compare('duracao', $this->duracao, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('title_completo', $this->title_completo, true);
        $criteria->compare('nome_host', $this->nome_host, true);
        $criteria->compare('host_domain', $this->host_domain, true);
        $criteria->compare('serial_empresa', $this->serial_empresa, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchBlackList() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $idUser = Yii::app()->user->id;
        $serial = UserGroupsUser::model()->findByAttributes(array("id" => $idUser));
        $serial = $serial->serial_empresa;

        $criteria = new CDbCriteria;
        $criteria->select = 't.programa , t.usuario, SUM(t.duracao) as duracao, t.serial_empresa';
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->addCondition("t.serial_empresa like '$serial'");
        $criteria->addCondition("t.descricao NOT LIKE 'Ocioso'");
        $criteria->addCondition("t.descricao NOT LIKE ''");
        $criteria->addCondition("t.programa NOT LIKE 'Não Identificado'");
        $criteria->addCondition("TRIM(t.programa) NOT IN (SELECT TRIM(nome) as nome FROM programa_permitido WHERE serial_empresa = '$serial')");
        $criteria->group = 't.programa';
        $criteria->order = 'duracao DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LogProgramaConsolidado the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getEmpresaId() {
        $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);

        if (isset($usuario->fk_empresa) && $usuario->fk_empresa != NULL)
            return $usuario->fk_empresa;

        return 2;
    }

    public function searchUltimosAcessos($qnt) {
        $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);

        $criteria = new CDbCriteria;
        $now = new CDbExpression("NOW()");
        $criteria->addCondition('data < "'.$now.'" ');
        if ($usuario->fk_empresa == 41)
            $criteria->addCondition('serial_empresa = "EY3I-0DA4-Z6KD-BC9M" ');
        else
            $criteria->addCondition('serial_empresa = "'.$usuario->serial_empresa.'" ');

        $criteria->order = 'data DESC';
        $criteria->limit = $qnt;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }
}
