<?php

/**
* This is the model class for table "empresa_has_parametro".
*
* The followings are the available columns in table 'empresa_has_parametro':
* @property integer $id
* @property string $almoco_inicio
* @property string $almoco_fim
* @property integer $tempo_ocio
* @property string $porcentagem
* @property integer $fk_empresa
*/
class EmpresaHasParametro extends CActiveRecord
{
	/**
	* Returns the static model of the specified AR class.
	* @param string $className active record class name.
	* @return EmpresaHasParametro the static model class
	*
	*/
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	* @return string the associated database table name
	*/
	public function tableName()
	{
		return 'empresa_has_parametro';
	}

	/**
	* @return array validation rules for model attributes.
	*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('fk_empresa, permissao_contrato', 'numerical', 'integerOnly' => true),
			array('almoco_inicio, almoco_fim', 'length', 'max'=>255),
			array('porcentagem', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, almoco_inicio, almoco_fim, tempo_ocio, moeda', 'safe', 'on'=>'search'),
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
			'almoco_inicio' => Yii::t("smith",'Horário de início do almoço'),
			'almoco_fim' => Yii::t("smith",'Horário de encerramento do almoço'),
			'tempo_ocio' => Yii::t("smith", 'Tempo Ócio'),
			'porcentagem' => Yii::t("smith", 'Porcentagem dos Documentos'),
			'fk_empresa' => Yii::t("smith", 'Empresa'),
			'horario_entrada'=>Yii::t("smith",'Horário de início do expediente'),
			'horario_saida'=>Yii::t("smith",'Horário de encerramento do expediente'),
			'moeda'=>Yii::t("smith",'Moeda'),
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('almoco_inicio',$this->almoco_inicio,true);
		$criteria->compare('almoco_fim',$this->almoco_fim,true);
		$criteria->compare('tempo_ocio',$this->tempo_ocio,true);
		$criteria->compare('porcentagem',$this->porcentagem,true);
		$criteria->compare('fk_empresa', $this->fk_empresa);
		$criteria->compare('moeda',$this->moeda);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function has_relations(){
		foreach ($this->relations() as $relation => $attributes) {
			if ($this->getRelated($relation))
			return true;
		}
		return false;
	}

	public function getDescricao(){
		$sql = "SELECT DISTINCT  `descricao` , programa, serial_empresa, data
		FROM  `log_atividade`
		WHERE  `descricao` LIKE  '%Adobe Photoshop CS3 Extended%'
		AND programa LIKE  'Não Identificado'
		AND serial_empresa LIKE  'IX4E-A3BW-YD71-7CDM'

		";
		$command = Yii::app()->getDb()->createCommand($sql);
		return $command->queryAll();
	}

	public function exec($descricao,$serial){
		$sql = "CALL programas_nao_identificados(
		CONVERT( 'Não Identificado' USING utf8 ) COLLATE utf8_unicode_ci,
		CONVERT( '$descricao' USING utf8 ) COLLATE utf8_unicode_ci,
		CONVERT( '$serial' USING utf8 ) COLLATE utf8_unicode_ci
		)
		";
		$command = Yii::app()->getDb()->createCommand($sql);
		$command->execute();
	}
}
