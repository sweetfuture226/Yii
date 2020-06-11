<?php

/**
 * This is the model class for table "empresa".
 *
 * The followings are the available columns in table 'empresa':
 * @property integer $id
 * @property string $nome
 * @property string $logo
 * @property string $serial
 * @property string $email
 * @property integer $wizard
 * @property integer $passo_wizard
 * @property integer $colaboradores_previstos
 * @property integer $dias_colaboradores_a_mais
 * @property integer $ativo
 * @property string $responsavel
 * @property string $telefone
 *
 * The followings are the available model relations:
 */
class Empresa extends CActiveRecord
{

	public $totalContratos, $contratosLDP, $contratosSemLDP, $contratosProdutivos, $emailContato, $nomeContato, $duracao;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Empresa the static model class
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
		return 'empresa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nome,email,colaboradores_previstos,telefone,responsavel', 'required'),
			array('nome, logo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nome, logo, serial, email, wizard, passo_wizard, colaboradores_previstos, dias_colaboradores_a_mais, ativo,telefone,responsavel', 'safe', 'on' => 'search'),
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
            'logs' => array(self::HAS_MANY, 'LogAtividade', 'fk_empresa'),
			'revendaHasPoc' => array(self::HAS_MANY, 'RevendaHasPoc', 'fk_empresa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nome' => Yii::t("smith", 'Cliente'),
			'logo' => Yii::t("smith", 'Logo'),
			'colaboradores_previstos' => Yii::t("smith", 'Colaboradores previstos'),
			'responsavel' => Yii::t('smith', 'Responsável'),
			'telefone' => Yii::t('smith', 'Telefone'),
			'nomeContato' => Yii::t('smith', 'Responsável revenda'),
			'emailContato' => Yii::t('smith', 'Email revenda'),
			'duracao' => Yii::t('smith', 'Duração POC'),
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
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('logo',$this->logo);
		$criteria->compare('serial',$this->serial);
		$criteria->compare('email',$this->email);
		$criteria->compare('wizard',$this->wizard);
		$criteria->compare('passo_wizard',$this->passo_wizard);
		$criteria->compare('colaboradores_previstos',$this->colaboradores_previstos);
		$criteria->compare('dias_colaboradores_a_mais',$this->dias_colaboradores_a_mais);
		$criteria->compare('ativo',$this->ativo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination' => array(
                    'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
                ),
		));
	}

	public function searchPoc()
	{

		$criteria = new CDbCriteria;

		$criteria->select = 't.ativo, t.id, t.nome, t.email, t.logo, c.email as emailContato,c.nome as nomeContato, rhp.duracao as duracao, t.colaboradores_previstos,t.responsavel';
		$criteria->compare('id', $this->id);
		$criteria->compare('nome', $this->nome, true);
		$criteria->compare('serial', $this->serial);
		$criteria->compare('email', $this->email);
		$criteria->compare('colaboradores_previstos', $this->colaboradores_previstos);
		$criteria->compare('dias_colaboradores_a_mais', $this->dias_colaboradores_a_mais);
		$criteria->compare('ativo', $this->ativo);
		$criteria->join = 'INNER JOIN revendaHasPoc as rhp ON t.id = rhp.fk_empresa';
		$criteria->join .= ' INNER JOIN contato as c ON c.id = rhp.fk_contato';
		$criteria->order = 't.ativo DESC ,t.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),

		));
	}

    public function has_relations(){
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
                return true;
        }
        return false;
    }


    public function getEmpresasPovoar($id=""){
        $criteria = new CDbCriteria();
        $criteria->select ='id,serial';
        if(!empty($id))
            $criteria->addCondition("id = $id");
        return $this->findAll($criteria);

    }
}
