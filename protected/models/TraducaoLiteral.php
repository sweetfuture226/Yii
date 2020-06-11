<?php

/**
 * This is the model class for table "traducao_literal".
 *
 * The followings are the available columns in table 'traducao_literal':
 * @property integer $id
 * @property string $category
 * @property string $message
 */
class TraducaoLiteral extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'traducao_literal';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category', 'required'),
            array('category', 'length', 'max' => 32),
            array('message', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, category, message', 'safe', 'on' => 'search'),
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
            'traducao' => array(self::HAS_MANY, 'Traducao', 'id', 'joinType' => 'inner join'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category' => 'Category',
            'message' => Yii::t('smith', 'Literal'),
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
        $criteria->compare('category', $this->category, true);
        $criteria->compare('message', $this->message, true);
        if(empty($this->message)){
            $criteria->addCondition('category = "smith"', "OR");
            $criteria->addCondition('category = "wizard"', "OR");
            $criteria->addCondition('category = "help"', "OR");
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TraducaoLiteral the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
