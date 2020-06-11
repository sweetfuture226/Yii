<?php

/**
 * This is the model class for table "site_permitido".
 *
 * The followings are the available columns in table 'site_permitido':
 * @property integer $id
 * @property string $nome
 * @property integer $fk_empresa
 * @property integer $fk_equipe
 */
class SitePermitido extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SitePermitido the static model class
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
        return 'site_permitido';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_empresa', 'required'),
            array('fk_empresa', 'numerical', 'integerOnly' => true),
            array('fk_equipe', 'numerical', 'allowEmpty' => true),
            array('nome', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, nome, fk_empresa', 'safe', 'on' => 'search'),
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
            'nome' => Yii::t("smith", 'Nome'),
            'fk_empresa' => Yii::t("smith", 'Empresa'),
            'fk_equipe' => Yii::t("smith", 'Equipe'),
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
        $this->fk_empresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('nome', $this->nome, true);
        $criteria->compare('fk_empresa', $this->fk_empresa);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'nome ASC'),
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function afterFind()
    {
        $this->nome = trim($this->nome);
    }
}
