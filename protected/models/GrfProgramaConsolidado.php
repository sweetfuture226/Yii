<?php

/**
 * This is the model class for table "grf_programa_consolidado".
 *
 * The followings are the available columns in table 'grf_programa_consolidado':
 * @property string $id
 * @property string $categoria
 * @property string $programa
 * @property double $duracao
 * @property string $data
 * @property integer $fk_empresa
 */
class GrfProgramaConsolidado extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'grf_programa_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('categoria, programa, duracao, data, fk_empresa', 'required'),
            array('fk_empresa', 'numerical', 'integerOnly' => true),
            array('duracao', 'numerical'),
            array('id', 'length', 'max' => 11),
            array('categoria, programa', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, categoria, programa, duracao, data, fk_empresa', 'safe', 'on' => 'search'),
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
            'categoria' => Yii::t("smith", 'Categoria'),
            'programa' => Yii::t("smith", 'Programa'),
            'duracao' => Yii::t("smith", 'Duracao'),
            'data' => Yii::t("smith", 'Data'),
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('categoria', $this->categoria, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('duracao', $this->duracao);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('fk_empresa', $this->fk_empresa);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GrfProgramaConsolidado the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function graficoProdutividade($inicio, $fim, $fk_empresa) {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, categoria, programa, data';
        $criteria->condition = 'fk_empresa=:fk_empresa';
        $criteria->params = array(':fk_empresa'=>$fk_empresa);
        $criteria->addBetweenCondition('data', $inicio, $fim);
        $criteria->group = 'programa';
        $criteria->order = 'duracao ASC';
        return $this->findAll($criteria);
    }
}
