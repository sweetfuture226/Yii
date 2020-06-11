<?php

/**
 * This is the model class for table "view_colaborador_metrica".
 *
 * The followings are the available columns in table 'view_colaborador_metrica':
 * @property integer $metrica_id
 * @property integer $colaborador_id
 * @property integer $fk_equipe
 * @property string $nome
 * @property string $sobrenome
 * @property string $email
 * @property double $salario
 * @property integer $id
 * @property string $titulo
 * @property string $atuacao
 * @property string $descricao
 * @property string $programa
 * @property string $criterio
 * @property string $sufixo
 * @property integer $meta
 * @property string $tempo
 * @property integer $fk_empresa
 * @property string $serial_empresa
 */
class ViewColaboradorMetrica extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'view_colaborador_metrica';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('metrica_id, colaborador_id, sobrenome, titulo, atuacao, programa, criterio, fk_empresa, serial_empresa', 'required'),
			array('metrica_id, colaborador_id, fk_equipe, id, meta, fk_empresa', 'numerical', 'integerOnly'=>true),
			array('salario', 'numerical'),
			array('nome, email, titulo, atuacao, descricao, programa, criterio, sufixo', 'length', 'max'=>255),
			array('sobrenome', 'length', 'max'=>45),
			array('serial_empresa', 'length', 'max'=>36),
			array('tempo', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('metrica_id, colaborador_id, fk_equipe, nome, sobrenome, email, salario, id, titulo, atuacao, descricao, programa, criterio, sufixo, meta, tempo, fk_empresa, serial_empresa', 'safe', 'on'=>'search'),
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
			'metrica_id' => 'Metrica',
			'colaborador_id' => 'Colaborador',
			'fk_equipe' => 'Pro Equipe',
			'nome' => 'Nome',
			'sobrenome' => 'Sobrenome',
			'email' => 'Email',
			'salario' => 'Salario',
			'id' => 'ID',
			'titulo' => 'Titulo',
			'atuacao' => 'Atuacao',
			'descricao' => 'Descricao',
			'programa' => 'Programa',
			'criterio' => 'Criterio',
			'sufixo' => 'Sufixo',
			'meta' => 'Meta',
			'tempo' => 'Tempo',
			'fk_empresa' => 'Fk Empresa',
			'serial_empresa' => 'Serial Empresa',
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

		$criteria->compare('metrica_id',$this->metrica_id);
		$criteria->compare('colaborador_id',$this->colaborador_id);
		$criteria->compare('fk_equipe',$this->fk_equipe);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('sobrenome',$this->sobrenome,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('salario',$this->salario);
		$criteria->compare('id',$this->id);
		$criteria->compare('titulo',$this->titulo,true);
		$criteria->compare('atuacao',$this->atuacao,true);
		$criteria->compare('descricao',$this->descricao,true);
		$criteria->compare('programa',$this->programa,true);
		$criteria->compare('criterio',$this->criterio,true);
		$criteria->compare('sufixo',$this->sufixo,true);
		$criteria->compare('meta',$this->meta);
		$criteria->compare('tempo',$this->tempo,true);
		$criteria->compare('fk_empresa',$this->fk_empresa);
		$criteria->compare('serial_empresa',$this->serial_empresa,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewColaboradorMetrica the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
