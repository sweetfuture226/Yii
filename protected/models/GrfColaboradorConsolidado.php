<?php

/**
 * This is the model class for table "grf_colaborador_consolidado".
 *
 * The followings are the available columns in table 'grf_colaborador_consolidado':
 * @property string $id
 * @property string $nome
 * @property string $data
 * @property string $hora_entrada
 * @property integer $fk_empresa
 * @property integer $fk_colaborador
 * @property string $hora_saida
 *
 * The followings are the available model relations:
 * @property Colaborador $fkColaborador
 * @property Empresa $fkEmpresa
 */
class GrfColaboradorConsolidado extends CActiveRecord {

    public $nomeEquipe, $duracao;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'grf_colaborador_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('data, hora_entrada, fk_empresa, fk_colaborador, hora_saida', 'required'),
            array('fk_empresa, fk_colaborador', 'numerical', 'integerOnly' => true),
            array('id', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, data, hora_entrada, fk_empresa, fk_colaborador, hora_saida', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'fkColaborador' => array(self::BELONGS_TO, 'Colaborador', 'fk_colaborador'),
            'fkEmpresa' => array(self::BELONGS_TO, 'Empresa', 'fk_empresa'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'data' => Yii::t("smith", 'Data'),
            'hora_entrada' => Yii::t("smith", 'Hora Entrada'),
            'fk_empresa' => 'Fk Empresa',
            'fk_colaborador' => 'Fk Colaborador',
            'hora_saida' => Yii::t("smith", 'Hora Saida'),
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('hora_entrada', $this->hora_entrada, true);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->compare('fk_colaborador', $this->fk_colaborador);
        $criteria->compare('hora_saida', $this->hora_saida, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GrfColaboradorConsolidado the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getPontos($inicio, $fim, $idEmpresa, $idColaborador) {
        $criteria = new CDbCriteria;
        $criteria->select = 't.*, pe.nome as nomeEquipe';
        $criteria->join = 'JOIN colaborador pp ON pp.id = t.fk_colaborador
                           JOIN equipe pe ON pe.id = pp.fk_equipe';
        $criteria->condition = 't.fk_empresa=:fk_empresa';
        $criteria->params = array(':fk_empresa'=>$idEmpresa);
        if($idColaborador != null & !empty($idColaborador)){
            $criteria->addCondition('t.fk_colaborador=' . $idColaborador);
        }
        $criteria->addBetweenCondition('t.data', $inicio, $fim);
        $criteria->order = 't.data, nomeEquipe, t.nome';
        return $this->findAll($criteria);
    }
    
    
    public function getEntradaSaida($data,$fk_colaborador){
        $criteria = new CDbCriteria;
        $criteria->select = 'hora_entrada,hora_saida,fk_colaborador';
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition("fk_colaborador = $fk_colaborador");
        return $this->find($criteria);
    }

    public function getSumHorasTrabalhadas($dataInicio, $dataFim, $fkEmpresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = '
                            (SUM(time_to_sec( CASE c.horas_semana
                                WHEN \'40:00:00\' THEN TIME(TIMEDIFF(hora_saida, hora_entrada) - TIMEDIFF(ehp.almoco_fim, ehp.almoco_inicio))
                                WHEN \'30:00:00\' THEN TIME(TIMEDIFF(hora_saida, hora_entrada))
                                WHEN \'20:00:00\' THEN TIME(TIMEDIFF(hora_saida, hora_entrada))
                            END))) AS duracao';
        $criteria->join = 'INNER JOIN empresa_has_parametro AS ehp ON ehp.fk_empresa = t.fk_empresa INNER JOIN colaborador AS c ON c.id = t.fk_colaborador';
        $criteria->addCondition('t.fk_empresa = ' . $fkEmpresa);
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        return $this->find($criteria);

    }

    public function getQuantidadeColaboradoresHorasTrabalhadas($dataInicio, $dataFim, $fkEmpresa)
    {
        $sql = "SELECT
                    count(distinct(fk_colaborador)) as qtd
                    FROM
                    smith.grf_colaborador_consolidado
                WHERE
                    fk_empresa = $fkEmpresa
                        AND data BETWEEN '$dataInicio' and '$dataFim'";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

}
