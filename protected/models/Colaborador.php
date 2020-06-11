<?php

/**
 * This is the model class for table "colaborador".
 *
 * The followings are the available columns in table 'colaborador':
 * @property integer $id
 * @property string $nome
 * @property integer $fk_equipe
 * @property string $nascimento
 * @property string $email
 * @property double $salario
 * @property string $horas_semana
 * @property string $ad
 * @property string $valor_hora
 *
 * The followings are the available model relations:
 * @property Equipe $equipe
 */
class Colaborador extends CActiveRecord
{

    public $porcento;
    public $duracao, $hora_total, $equipe;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Colaborador the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'colaborador';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nome, sobrenome, '
                . 'email, salario, '
                . 'horas_semana, ad,fk_equipe', 'required'),
            array('nome, email, horas_semana, ad', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, fk_equipe, nome, nascimento, email, salario, horas_semana, ad', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'equipes' => array(self::BELONGS_TO, 'Equipe', 'fk_equipe'),
            'colaboradoresMetrica' => array(self::HAS_MANY, 'ColaboradorHasMetrica', 'colaborador_id'),
            'hasSalario' => array(self::HAS_MANY, 'ColaboradorHasSalario', 'fk_colaborador'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'nome' => Yii::t('smith', 'Nome'),
            'sobrenome' => Yii::t('smith', 'Sobrenome'),
            'fk_equipe' => Yii::t('smith', 'Equipe'),
            'nascimento' => Yii::t('smith', 'Nascimento'),
            'email' => Yii::t('smith', 'Email'),
            'salario' => Yii::t('smith', 'Salário (incluir impostos e benefícios)'),
            'horas_semana' => Yii::t('smith', 'Carga Horária Semanal'),
            'valor_hora' => Yii::t('smith', 'Valor Hora'),
            'ad' => Yii::t("smith", 'Login na máquina'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        if (isset($user->serial_empresa))
            $serial_empresa = $user->serial_empresa;

        $criteria = new CDbCriteria;

        $nascimento = !empty($this->nascimento) ? date('Y-m-d', CDateTimeParser::parse($this->nascimento, 'dd/MM/yyyy')) : '';

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.nome', $this->nome, true);
        $criteria->compare('t.nascimento', $nascimento, true);
        $criteria->compare('t.fk_equipe', $this->fk_equipe);
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.salario', $this->salario);
        $criteria->compare('t.horas_semana', $this->horas_semana, true);
        $criteria->compare('t.ad', $this->ad, true);
        $criteria->addCondition("t.serial_empresa= '" . $serial_empresa . "'");
        $criteria->with = 'equipes';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.status DESC,t.nome ASC '),
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

    public function behaviors() {
        return array('datetimeI18NBehavior' => array('class' => 'ext.DateTimeI18NBehavior')); // 'ext' is in Yii 1.0.8 version. For early versions, use 'application.extensions' instead.
    }

    public function getAD() {
        return Yii::app()->getDb()->createCommand("Select ad FROM colaborador ORDER BY ad ASC")->queryAll();
    }

    function getNomeCompleto()
    {
        return $this->nome . ' ' . $this->sobrenome;
    }

    public function findColaboradorByNomeCompleto($nome){
        $nome_completo = explode(' ',$nome);
        $sobrenome = $nome_completo[count($nome_completo) - 1];
        $criteria = new CDbCriteria;

        $criteria->addCondition('nome REGEXP "^'.$nome_completo[0].'"');
        $criteria->addCondition('sobrenome REGEXP "' . $sobrenome . '$"');
        $criteria->addCondition('fk_empresa = '.MetodosGerais::getEmpresaId());

        $colaborador = Colaborador::model()->find($criteria);
        return $colaborador;
    }

    public function verificaDataRelatorioIndDia($data_relatorio) {
        $data_relatorio = strtotime(MetodosGerais::dataAmericana($data_relatorio));
        $data_inativacao = strtotime(MetodosGerais::dataAmericana2($this->data_inativacao));

        if ($data_inativacao != NULL && $data_inativacao <= $data_relatorio)
            return FALSE;

        return TRUE;
    }

    public function findColaboradorForGrid($ad, $serial)
    {
        $usuario = Colaborador::model()->findByAttributes(array("ad"=>$ad,"serial_empresa"=>$serial));
        if(!is_null($usuario))
            return $usuario->nomeCompleto;
        else
            return Yii::t('smith', 'Usuário não informado.');
    }

}
