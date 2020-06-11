<?php

/**
 * This is the model class for table "log_atividade_teste".
 *
 * The followings are the available columns in table 'log_atividade_teste':
 * @property integer $id
 * @property string $usuario
 * @property string $programa
 * @property string $descricao
 * @property string $duracao
 * @property string $data
 * @property string $hora_host
 * @property string $serial_empresa
 * @property integer $fk_empresa
 */
class LogAtividadeHistorico extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log_atividade_historico';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serial_empresa', 'required'),
			array('fk_empresa', 'numerical', 'integerOnly'=>true),
			array('usuario, programa, descricao', 'length', 'max'=>255),
			array('serial_empresa', 'length', 'max'=>36),
			array('duracao, data, hora_host', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, usuario, programa, descricao, duracao, data, hora_host, serial_empresa, fk_empresa', 'safe', 'on'=>'search'),
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
			'usuario' => Yii::t("smith", 'Usuário'),
			'programa' => Yii::t("smith", 'Programa'),
			'descricao' => Yii::t("smith", 'Descrição'),
			'duracao' => Yii::t("smith", 'Duração'),
			'data' => Yii::t("smith", 'Data'),
			'hora_host' => Yii::t("smith", 'Hora Host'),
			'serial_empresa' => Yii::t("smith", 'Serial Empresa'),
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
		$criteria->compare('usuario',$this->usuario,true);
		$criteria->compare('programa',$this->programa,true);
		$criteria->compare('descricao',$this->descricao,true);
		$criteria->compare('duracao',$this->duracao,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('hora_host',$this->hora_host,true);
		$criteria->compare('serial_empresa',$this->serial_empresa,true);
		$criteria->compare('fk_empresa',$this->fk_empresa);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LogAtividadeHistorico the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
