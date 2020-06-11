<?php

/**
 * This is the model class for table "executavel".
 *
 * The followings are the available columns in table 'executavel':
 * @property integer $id
 * @property string $versao
 * @property string $data
 * @property integer $fk_usuario
 * @property integer $fk_empresa
 */
class Executavel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'executavel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('versao, data, fk_usuario, fk_empresa', 'required'),
			array('fk_usuario, fk_empresa', 'numerical', 'integerOnly'=>true),
			array('versao', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, versao, data, fk_usuario, fk_empresa', 'safe', 'on'=>'search'),
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
			'versao' => Yii::t("smith", 'Versão'),
			'data' => Yii::t("smith", 'Data'),
			'fk_usuario' => 'Fk Usuario',
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
		$criteria->compare('versao',$this->versao,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('fk_usuario',$this->fk_usuario);
		$criteria->compare('fk_empresa',$this->fk_empresa);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Executavel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
