<?php

/**
 * This is the model class for table "colaborador_has_metrica".
 *
 * The followings are the available columns in table 'colaborador_has_metrica':
 * @property integer $id
 * @property integer $fk_metrica
 * @property integer $fk_colaborador
 * @property string $data
 */
class ColaboradorHasMetrica extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'colaborador_has_metrica';
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_metrica, fk_colaborador, data', 'required'),
            array('fk_metrica, fk_colaborador', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fk_metrica, fk_colaborador, data', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'metrica' => array(self::BELONGS_TO, 'Metrica', 'fk_metrica'),
            'colaborador' => array(self::BELONGS_TO, 'Colaborador', 'fk_colaborador', 'order' => 'nome ASC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'fk_metrica' => 'Metrica',
            'fk_colaborador' => 'Colaborador',
            'data' => 'Data',
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
        $criteria->compare('fk_metrica', $this->fk_metrica);
        $criteria->compare('fk_colaborador', $this->fk_colaborador);
        $criteria->compare('data', $this->data, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function has_relations() {
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
                return true;
        }
        return false;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ColaboradorHasMetrica the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
