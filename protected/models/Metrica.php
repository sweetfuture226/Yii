<?php

/**
 * This is the model class for table "metrica".
 *
 * The followings are the available columns in table 'metrica':
 * @property integer $id
 * @property string $titulo
 * @property string $atuacao
 * @property string $descricao
 * @property string $programa
 * @property string $criterio
 * @property string $sufixo
 * @property integer $meta
 * @property integer $meta_tempo
 * @property integer $min_t
 * @property integer $max_t
 * @property integer $fk_empresa
 * @property string $serial_empresa
 * @property integer $favorito
 * @property integer $min_e
 * @property integer $max_e
 * @property integer $meta_entrada
 */
class Metrica extends CActiveRecord {

    public $entradas, $total, $media, $data, $colaborador;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'metrica';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('titulo, atuacao, programa, fk_empresa, serial_empresa', 'required'),
            array('meta, meta_tempo, min_t, max_t, fk_empresa, favorito, min_e, max_e, meta_entrada,alerta', 'numerical', 'integerOnly' => true),
            array('atuacao, descricao, programa, criterio, sufixo', 'length', 'max' => 255),
            array('titulo', 'length', 'max' => 40),
            array('serial_empresa', 'length', 'max' => 36),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, titulo, atuacao, descricao, programa, criterio, sufixo, meta, meta_tempo, min_t, max_t, fk_empresa, serial_empresa, favorito, min_e, max_e, meta_entrada, total, media', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'colaboradores' => array(self::HAS_MANY, 'ColaboradorHasMetrica', 'fk_metrica'),
            'logs' => array(self::HAS_MANY, 'MetricaConsolidada', 'fk_metrica'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */

    public function attributeLabels() {
        return array(
            'titulo' => Yii::t('smith','Título'),
            'atuacao' => Yii::t('smith','Área de atuação'),
            'programa' => Yii::t('smith', 'Aplicação'),
            'descricao' => Yii::t('smith','Descrição'),
            'criterio' => Yii::t('smith','Critério'),
            'sufixo' => Yii::t('smith','Considerar prefixos e sufixos'),
            'entradas' => Yii::t('smith','Entradas por dia'),
            'total' => Yii::t('smith','Tempo total'),
            'media' => Yii::t('smith','Média tempo / entrada'),
            'min_t' => Yii::t('smith','Tempo mínimo esperado'),
            'max_t' => Yii::t('smith','Tempo máximo esperado'),
            'min_e' => Yii::t('smith','Mínimo de entradas'),
            'max_e' => Yii::t('smith','Máximo de entradas'),
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
        $fkEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->compare('titulo', $this->titulo, true);
        $criteria->compare('atuacao', $this->atuacao, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('min_t', $this->min_t);
        $criteria->compare('max_t', $this->max_t);
        $criteria->compare('min_e', $this->min_e);
        $criteria->compare('max_e', $this->max_e);
        $criteria->compare('criterio', $this->criterio, true);
        $criteria->compare('sufixo', $this->sufixo, true);
        $criteria->addCondition("t.fk_empresa like $fkEmpresa");

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function searchDetalheMetrica($fkMetrica) {
        $fkEmpresa = MetodosGerais::getEmpresaId();
        $criteria=new CDbCriteria;
        $this->colaborador = (isset($_GET['Metrica']['colaborador']))?$_GET['Metrica']['colaborador']:'';
        $this->entradas = (isset($_GET['Metrica']['entradas']))?$_GET['Metrica']['entradas']:'';
        $this->total = (isset($_GET['Metrica']['total']))?$_GET['Metrica']['total']:'';
        $this->media = (isset($_GET['Metrica']['media']))?$_GET['Metrica']['media']:'';
        $this->data = (!empty($_GET['Metrica']['data']))?date('Y-m-d', CDateTimeParser::parse($_GET['Metrica']['data'], 'dd/MM/yyyy')):'';
        $criteria->select = "t.id, t.titulo, t.atuacao,t.criterio, m.entradas, m.total, m.media,m.data, m.fk_colaborador as colaborador ";
        $criteria->join = "INNER JOIN metrica_consolidada as m ON m.fk_metrica = t.id";
        $criteria->addCondition("m.fk_metrica = $fkMetrica");
        $criteria->addCondition("m.fk_empresa = $fkEmpresa");
        $criteria->order = 'm.data DESC';
        $criteria->compare('titulo',$this->titulo,true);
        $criteria->compare('atuacao',$this->atuacao,true);
        $criteria->compare('m.fk_colaborador',$this->colaborador);
        $criteria->addCondition($this->filtroTempo($this->total, "m.total"));
        $criteria->addCondition($this->filtroEntradasPorDia($this->entradas));
        $criteria->addCondition($this->filtroTempoMedio($this->media));
        $criteria->compare('m.data',$this->data);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    //Recebe um valor de 0 a 6, de acordo a opção selecionada no filtro da grid, e retorna a string pronta para o criteria
    private function filtroEntradasPorDia($entrada){
        switch($entrada){
            case '0':
                $criteria = 'm.entradas >= 0 AND m.entradas <= 5';
                break;
            case '1':
                $criteria = 'm.entradas >= 5 AND m.entradas <= 10';
                break;
            case '2':
                $criteria = 'm.entradas >= 10 AND m.entradas <= 20';
                break;
            case '3':
                $criteria = 'm.entradas >= 20 AND m.entradas <= 30';
                break;
            case '4':
                $criteria = 'm.entradas >= 30 AND m.entradas <= 40';
                break;
            case '5':
                $criteria = 'm.entradas >= 40 AND m.entradas <= 50';
                break;
            case '6':
                $criteria = 'm.entradas >= 50';
                break;
            default:
                $criteria = 'm.entradas >= 0';
                break;
        }

        return $criteria;
    }

    private function filtroTempo($entrada, $campo){
        switch($entrada){
            case '0':
                $criteria = "$campo BETWEEN '00:00:00' AND '00:10:00'";
                break;
            case '1':
                $criteria = "$campo BETWEEN '00:10:00' AND '00:30:00'";
                break;
            case '2':
                $criteria = "$campo BETWEEN '00:30:00' AND '01:00:00'";
                break;
            case '3':
                $criteria = "$campo BETWEEN '01:00:00' AND '02:00:00'";
                break;
            case '4':
                $criteria = "$campo BETWEEN '02:00:00' AND '03:00:00'";
                break;
            case '5':
                $criteria = "$campo BETWEEN '03:00:00' AND '04:00:00'";
                break;
            case '6':
                $criteria = "$campo >= '04:00:00'";
                break;
            default:
                $criteria = "$campo >= '00:00:00'";
                break;
        }
        return $criteria;
    }

    private function filtroTempoMedio($entrada){

        switch($entrada){
            case '0':
                $criteria = "m.media BETWEEN '00:00:00' AND '00:05:00'";
                break;
            case '1':
                $criteria = "m.media BETWEEN '00:05:00' AND '00:10:00'";
                break;
            case '2':
                $criteria = "m.media BETWEEN '00:10:00' AND '00:20:00'";
                break;
            case '3':
                $criteria = "m.media BETWEEN '00:20:00' AND '00:30:00'";
                break;
            case '4':
                $criteria = "m.media BETWEEN '00:30:00' AND '00:40:00'";
                break;
            case '5':
                $criteria = "m.media BETWEEN '00:40:00' AND '00:50:00'";
                break;
            case '6':
                $criteria = "m.media BETWEEN '00:50:00' AND '01:00:00'";
                break;
            case '7':
                $criteria = "m.media >= '01:00:00'";
                break;
            default:
                $criteria = "m.media >= '00:00:00'";
                break;
        }
        return $criteria;
    }

    public function has_relations() {
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
                return true;
        }
        return false;
    }

    public function afterFind() {
        $this->min_t = MetodosGerais::formataTempo($this->min_t);
        $this->max_t = MetodosGerais::formataTempo($this->max_t);
        return parent::afterFind();
    }

    public function alertaMetrica($dataInicio,$dataFim){
        $criteria = new CDbCriteria();
        $criteria->select = 't.fk_empresa, t.id, t.titulo, l.total, l.entradas, l.data , t.min_t, t.max_t, t.min_e, t.max_e, l.fk_colaborador as colaborador, t.meta_tempo,t.meta_entrada';
        $criteria->join = 'inner join metrica_consolidada as l on l.fk_metrica = t.id';
        $criteria->addCondition('t.alerta = 1');
        $criteria->addBetweenCondition('data',$dataInicio,$dataFim);
        $criteria->order = 't.titulo ASC';
        return $this->findAll($criteria);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Metrica the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
