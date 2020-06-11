<?php

/**
 * This is the model class for table "log_central_notificacao".
 *
 * The followings are the available columns in table 'log_central_notificacao':
 * @property integer $id
 * @property integer $fk_acao
 * @property integer $fk_documento_sem_contrato
 * @property string $descricao
 * @property integer $tipo
 */
class LogCentralNotificacao extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'log_central_notificacao';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_acao, fk_documento_sem_contrato, descricao, tipo, fk_empresa', 'required'),
            array('fk_acao, fk_documento_sem_contrato, tipo, fk_empresa', 'numerical', 'integerOnly' => true),
            array('descricao', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fk_acao, fk_documento_sem_contrato, descricao, tipo, fk_empresa', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'fk_acao' => 'tipo 1 - fk_log
tipo 2 - fk_documento',
            'fk_documento_sem_contrato' => 'Fk Documento Sem Contrato',
            'descricao' => 'tipo 1 - descricao log
tipo 2 - descricao documento',
            'tipo' => '1 -  documento existente
2 -  novo documento',
            'fk_empresa' => 'Empresa'
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('fk_acao', $this->fk_acao);
        $criteria->compare('fk_documento_sem_contrato', $this->fk_documento_sem_contrato);
        $criteria->compare('descricao', $this->descricao, true);
        $criteria->compare('tipo', $this->tipo);
        $criteria->compare('fk_empresa', MetodosGerais::getEmpresaId());

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LogCentralNotificacao the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
