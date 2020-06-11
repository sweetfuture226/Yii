<?php

/**
 * This is the model class for table "equipe".
 *
 * The followings are the available columns in table 'equipe':
 * @property integer $id
 * @property string $nome
 * @property string $descricao
 * @property integer $fk_empresa
 * @property float meta
 *
 * The followings are the available model relations:
 * @property Empresa $empresa
 * @property Colaborador[] $proPessoas
 */
class Equipe extends CActiveRecord {

    public $membros;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Equipe the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'equipe';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nome, fk_empresa', 'required'),
            array('fk_empresa', 'numerical', 'integerOnly' => true),
            array('nome', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, nome, descricao, fk_empresa, meta', 'safe'),
            array('id, nome, descricao, fk_empresa', 'safe', 'on' => 'search'),
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
            'proPessoas' => array(self::HAS_MANY, 'Colaborador', 'fk_equipe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'nome' => Yii::t("smith", 'Nome da Equipe'),
            'descricao' => Yii::t("smith", 'Descrição'),
            'fk_empresa' => Yii::t("smith", 'Empresa'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('nome', $this->nome, true);
        $criteria->compare('descricao', $this->descricao, true);
        $criteria->addCondition('fk_empresa =' . MetodosGerais::getEmpresaId());
        $criteria->addCondition('ativo = 1');


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'nome ASC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function has_relations() {
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
                return true;
        }
        return false;
    }

    public function getNome($usuario, $serial) {
        $criteria = new CDbCriteria;
        $criteria->select = 't.nome';
        $criteria->join = 'JOIN colaborador p ON p.fk_equipe = t.id';
        $criteria->addCondition("p.ad = '" . $usuario . "'", 'AND');
        $criteria->addCondition("p.serial_empresa = '" . $serial . "'", 'AND');
        $criteria->together = true;
        $retorno = $this->find($criteria);
        return $retorno['nome'];
    }

}
