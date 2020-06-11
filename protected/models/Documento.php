<?php

/**
 * This is the model class for table "documento".
 *
 * The followings are the available columns in table 'documento':
 * @property integer $id
 * @property integer $fk_disciplina
 * @property string $nome
 * @property string $previsto
 * @property integer $finalizado
 * @property integer $porcentagem_concluida
 * The followings are the available model relations:
 * @property Disciplina $disciplina
 */
class Documento extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Documento the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'documento';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nome, finalizado', 'required'),
            array('fk_disciplina', 'numerical', 'integerOnly' => true),
            array('nome', 'length', 'max' => 256),
            array('previsto', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, fk_disciplina, nome, previsto', 'safe', 'on' => 'search'),
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
            'disciplina' => array(self::BELONGS_TO, 'Disciplina', 'fk_disciplina'),
            'contrato' => array(self::BELONGS_TO, 'Contrato', 'fk_contrato'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'fk_disciplina' => Yii::t("smith", 'Disciplina'),
            'nome' => Yii::t("smith", 'Nome'),
            'previsto' => Yii::t("smith", 'Tempo Previsto'),
            'finalizado' => Yii::t("smith", 'Status'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $fk_empresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('fk_disciplina', $this->fk_disciplina);
        $criteria->compare('nome', $this->nome, true);
        $criteria->compare('previsto', $this->previsto, true);
        $criteria->compare('finalizado', $this->finalizado);
        if ($criteria->condition == null) {
            $criteria->condition = " t.fk_empresa= '" . $fk_empresa . "' ";
        } else {
            $criteria->condition .= " AND t.fk_empresa= '" . $fk_empresa . "' ";
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function has_relations()
    {
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
                return true;
        }
        return false;
    }
}
