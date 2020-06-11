<?php

/**
 * This is the model class for table "grf_ociosidade_consolidado".
 *
 * The followings are the available columns in table 'grf_ociosidade_consolidado':
 * @property integer $id
 * @property integer $fk_log
 * @property integer $fk_empresa
 * @property integer $fk_colaborador
 * @property string $data
 * @property string $hora_inicial
 * @property string $hora_final
 * @property string $duracao
 */
class GrfOciosidadeConsolidado extends CActiveRecord
{
    public $nome, $sobrenome, $usuario;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grf_ociosidade_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_empresa, fk_colaborador, data, hora_inicial, hora_final, duracao', 'required'),
            array('id, fk_log, fk_empresa, fk_colaborador', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fk_log, fk_empresa, fk_colaborador, data, hora_inicial, hora_final, duracao', 'safe', 'on' => 'search'),
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
            'fk_log' => 'Fk Log',
            'fk_empresa' => 'Fk Empresa',
            'fk_colaborador' => 'Fk Colaborador',
            'data' => 'Data',
            'hora_inicial' => 'Hora Inicial',
            'hora_final' => 'Hora Final',
            'duracao' => 'Duracao',
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
        $criteria->compare('fk_log', $this->fk_log);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->compare('fk_colaborador', $this->fk_colaborador);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('hora_inicial', $this->hora_inicial, true);
        $criteria->compare('hora_final', $this->hora_final, true);
        $criteria->compare('duracao', $this->duracao, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function searchOcioso2Horas($fk_empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "p.nome as nome, p.sobrenome as sobrenome, t.data, p.ad as usuario, fk_colaborador, t.fk_log, duracao, hora_inicial, hora_final";
        $criteria->join = 'INNER JOIN colaborador as p ON p.fk_empresa = t.fk_empresa AND p.id = t.fk_colaborador';
        $criteria->addCondition('t.fk_empresa = ' . $fk_empresa);
        $criteria->addCondition("p.status = 1");
        $criteria->addCondition("MONTH(t.data) = MONTH(NOW())");
        $criteria->addCondition("t.status = 0");
        $criteria->order = 't.data';
        return $this->findAll($criteria);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GrfOciosidadeConsolidado the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
