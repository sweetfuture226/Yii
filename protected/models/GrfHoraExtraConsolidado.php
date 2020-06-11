<?php

/**
 * This is the model class for table "grf_hora_extra_consolidado".
 *
 * The followings are the available columns in table 'grf_hora_extra_consolidado':
 * @property string $id
 * @property double $duracao
 * @property double $produtividade
 * @property string $data
 * @property string $hora_inicio
 * @property string $hora_fim
 *
 * @property integer $fk_colaborador
 * @property integer $fk_empresa
 *
 * The followings are the available model relations:
 * @property Empresa $fkEmpresa
 * @property Colaborador $fkColaborador
 */
class GrfHoraExtraConsolidado extends CActiveRecord
{
//    public $nomeColaborador;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grf_hora_extra_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('duracao, produtividade, data, hora_inicio, hora_fim, fk_colaborador, fk_empresa', 'required'),
            array('fk_colaborador, fk_empresa', 'numerical', 'integerOnly' => true),
            array('duracao', 'numerical'),
            array('id', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, duracao, produtividade, data, fk_colaborador, fk_empresa', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'fkEmpresa' => array(self::BELONGS_TO, 'Empresa', 'fk_empresa'),
            'fkColaborador' => array(self::BELONGS_TO, 'Colaborador', 'fk_colaborador'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'duracao' => Yii::t("smith", 'Duração'),
            'produtividade' => Yii::t("smith", 'Produtividade'),
            'data' => Yii::t("smith", 'Data'),
            'fk_colaborador' => 'Fk Colaborador',
            'fk_empresa' => 'Fk Empresa',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('duracao', $this->duracao);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('fk_colaborador', $this->fk_colaborador);
        $criteria->compare('fk_empresa', $this->fk_empresa);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GrfHoraExtraConsolidado the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getHorasExtras($dataInicio, $dataFim, $idEmpresa, $opcao, $idOpcao)
    {
        $criteria = new CDbCriteria;

        $criteria->select = 'sum(t.duracao) AS duracao, sum(t.produtividade) AS produtividade, '
            . 't.fk_colaborador, t.fk_empresa';
        $criteria->addCondition("t.fk_empresa = $idEmpresa");
        $criteria->addBetweenCondition('t.data', $dataInicio, $dataFim);

        if ($idOpcao != 'todos') {
            if ($opcao == 'colaborador') {
                $criteria->addCondition("fk_colaborador = $idOpcao");
            } elseif ($opcao == 'equipe') {
                $criteria->join = "JOIN colaborador as pp ON pp.fk_equipe = $idOpcao";
                $criteria->addCondition('pp.id = fk_colaborador');
            }
        }
        $criteria->group = 'fk_colaborador ASC';

        return $this->findAll($criteria);
    }

    public function getHorasExtrasEquipe($dataInicio, $dataFim, $idEmpresa, $fk_colaborador)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'coalesce(sum(duracao),0) AS duracao';
        $criteria->addCondition("fk_colaborador = $fk_colaborador");
        $criteria->addCondition("fk_empresa = $idEmpresa");
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        return $this->find($criteria);
    }

    public function getHoraExtraEmpresa($dataInicio, $dataFim, $fk_empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(t.duracao) AS duracao, sum(t.produtividade) AS produtividade';
        $criteria->addCondition("t.fk_empresa = $fk_empresa");
        $criteria->addBetweenCondition('t.data', $dataInicio, $dataFim);
        return $this->findAll($criteria);
    }


}
