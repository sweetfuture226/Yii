<?php

/**
 * This is the model class for table "revendaHasPoc".
 *
 * The followings are the available columns in table 'revendaHasPoc':
 * @property integer $id
 * @property integer $fk_contato
 * @property integer $fk_empresa
 * @property integer $duracao
 */
class RevendaHasPoc extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'revendaHasPoc';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_contato, fk_empresa, duracao', 'required'),
            array('fk_contato, fk_empresa, duracao', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fk_contato, fk_empresa, duracao', 'safe', 'on' => 'search'),
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
            'contato' => array(self::BELONGS_TO, 'Contato', 'fk_contato'),
            'empresa' => array(self::BELONGS_TO, 'Empresa', 'fk_empresa'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'fk_contato' => 'Fk Revenda',
            'fk_empresa' => 'Fk Empresa',
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
        $criteria->compare('fk_contato', $this->fk_contato);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->compare('duracao', $this->duracao);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RevendaHasPoc the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
