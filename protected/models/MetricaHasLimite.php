<?php

/**
 * This is the model class for table "metrica_has_limite".
 *
 * The followings are the available columns in table 'metrica_has_limite':
 * @property integer $id
 * @property integer $min_t
 * @property integer $max_t
 * @property integer $fk_metrica
 * @property integer $fk_empresa
 * @property string $data
 * @property integer $min_e
 * @property integer $max_e
 * @property integer $meta_entrada
 * @property integer $meta_tempo
 */
class MetricaHasLimite extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'metrica_has_limite';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('min_t, max_t, fk_metrica, fk_empresa, data', 'required'),
            array('min_t, max_t, fk_metrica, fk_empresa, min_e, max_e, meta_entrada, meta_tempo', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, min_t, max_t, fk_metrica, fk_empresa, data, min_e, max_e, meta_entrada, meta_tempo', 'safe', 'on'=>'search'),
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
            'min_t' => 'Min T',
            'max_t' => 'Max T',
            'fk_metrica' => 'Fk Metrica',
            'fk_empresa' => 'Fk Empresa',
            'data' => 'Data',
            'min_e' => 'Min E',
            'max_e' => 'Max E',
            'meta_entrada' => 'Meta Entrada',
            'meta_tempo' => 'Meta Tempo',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('min_t',$this->min_t);
        $criteria->compare('max_t',$this->max_t);
        $criteria->compare('fk_metrica',$this->fk_metrica);
        $criteria->compare('fk_empresa',$this->fk_empresa);
        $criteria->compare('data',$this->data,true);
        $criteria->compare('min_e',$this->min_e);
        $criteria->compare('max_e',$this->max_e);
        $criteria->compare('meta_entrada',$this->meta_entrada);
        $criteria->compare('meta_tempo',$this->meta_tempo);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MetricaHasLimite the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
