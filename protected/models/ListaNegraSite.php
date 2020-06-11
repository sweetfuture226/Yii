<?php

/**
 * This is the model class for table "lista_negra_site".
 *
 * The followings are the available columns in table 'lista_negra_site':
 * @property integer $id
 * @property string $programa
 * @property string $site
 * @property double $porcentagem
 * @property double $tempo_absoluto
 * @property integer $fk_empresa
 */
class ListaNegraSite extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lista_negra_site';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('programa, site, porcentagem, tempo_absoluto, fk_empresa', 'required'),
			array('fk_empresa', 'numerical', 'integerOnly'=>true),
			array('porcentagem, tempo_absoluto', 'numerical'),
			array('programa, site', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, programa, site, porcentagem, tempo_absoluto, fk_empresa', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'programa' => Yii::t("smith", 'Navegador'),
            'porcentagem' => Yii::t("smith", 'Porcentagem'),
            'tempo_absoluto'=> Yii::t("smith", 'Tempo absoluto em horas'),
			'site' => Yii::t("smith", 'Site'),
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('programa',$this->programa,true);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('porcentagem',$this->porcentagem);
		$criteria->compare('tempo_absoluto',$this->tempo_absoluto);
		$criteria->compare('fk_empresa',$this->fk_empresa);
        $idEmpresa = MetodosGerais::getEmpresaId();
        $criteria->addCondition("fk_empresa = $idEmpresa");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array('defaultOrder'=> 'porcentagem DESC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ListaNegraSite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
