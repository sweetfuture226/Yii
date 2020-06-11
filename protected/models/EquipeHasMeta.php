<?php

/**
 * This is the model class for table "equipe_has_meta".
 *
 * The followings are the available columns in table 'equipe_has_meta':
 * @property integer $fk_empresa
 * @property integer $fk_equipe
 * @property string $data
 * @property string $meta
 */
class EquipeHasMeta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'equipe_has_meta';
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_empresa, fk_equipe, data, meta', 'required'),
            array('fk_empresa, fk_equipe', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('fk_empresa, fk_equipe, data, meta', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'empresa' => array(self::BELONGS_TO, 'Empresa', 'fk_empresa'),
            'equipe' => array(self::BELONGS_TO, 'Equipe', 'fk_equipe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'fk_empresa' => 'Empresa',
            'fk_equipe' => 'Equipe',
            'data' => 'Data',
            'meta' => 'Meta'
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
    public function search($empresa_id, $equipe_id)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.fk_empresa', $empresa_id);
        if ($equipe_id != null)
            $criteria->compare('t.fk_equipe', $equipe_id);
        else
            $criteria->compare('fk_equipe', $this->fk_equipe);
        $criteria->compare('data', $this->data);
        $criteria->compare('meta', $this->meta);
        $criteria->join = 'INNER JOIN equipe AS e ON e.id = t.fk_equipe';

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
