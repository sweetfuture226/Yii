<?php

/**
* This is the model class for table "log_atividade".
*
* The followings are the available columns in table 'log_atividade':
* @property integer $id
* @property string $usuario
* @property string $programa
* @property string $descricao
* @property string $duracao
* @property string $data
* @property string $title_completo
* @property string $hora_host
* @property string $data_hora_servidor
* @property integer $fk_empresa
*/
class LogAtividade extends CActiveRecord {

    public $fk_empresa;
    public $hora_alomoco_inicio = "12:00:00";
    public $hora_alomoco_fim = "14:00:00";
    public $nome;
    public $sobrenome;
    public $fk_colaborador;
    public $hora_final;
    public $duracao;
    /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return LogAtividade the static model class
    */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
    * @return string the associated database table name
    */
    public function tableName() {
        //TROCAR POR 'log_atividade' depois dos testes
        return 'log_atividade';
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('usuario, descricao', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, usuario, programa, descricao, duracao, data, title_completo, hora_host, data_hora_servidor, fk_empresa', 'safe'),
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
        );
    }

    /**
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'usuario' => Yii::t("smith", 'Usuário'),
            'programa' => Yii::t("smith", 'Programa'),
            'descricao' => Yii::t("smith", 'Descrição'),
            'duracao' => Yii::t("smith", 'Duração'),
            'data' => Yii::t("smith", 'Data'),
            'title_completo' => Yii::t("smith", 'Titulo Completo'),
            'hora_host' => Yii::t("smith", 'Horário'),
            'data_hora_servidor' => Yii::t("smith", 'Horário'),
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
        !empty($this->data) ? $data = date('Y-m-d', CDateTimeParser::parse($this->data, 'dd/MM/yyyy')) : $data = '';
        !empty($this->duracao) ? $duracao = explode("AND", $this->duracao) : $duracao = '';
        !empty($this->data_hora_servidor) ? $hora_servidor = explode("AND", $this->data_hora_servidor) : $hora_servidor = '';

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('descricao', $this->descricao, true);
        //$criteria->compare('duracao', $this->duracao, true);
        $criteria->compare('data', $data, true);
        $criteria->compare('title_completo', $this->title_completo, true);
        $criteria->compare('hora_host', $this->hora_host, true);
        $criteria->compare('data_hora_servidor', $this->data_hora_servidor, true);
        !empty($this->duracao) ? $criteria->condition = "duracao BETWEEN '$duracao[0]' AND '$duracao[1]'" : $this->duracao = '';
        $today = date('Y-m-d');
        !empty($this->data_hora_servidor) ? $criteria->condition = "data_hora_servidor BETWEEN '$today $hora_servidor[0]' AND '$today $hora_servidor[1]'" : $this->data_hora_servidor = '';
        //$usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);

        $empresaId = MetodosGerais::getEmpresaId();
        if($empresaId == 20){
            $criteria->addCondition("serial_empresa = '".MetodosGerais::getSerial()."'");
        }
        else{
            if($empresaId == 41)
            $criteria->addCondition("fk_empresa = 22");
            else
            $criteria->addCondition("fk_empresa = $empresaId");
        }
        $criteria->order = 'data_hora_servidor DESC';
        $criteria->limit = 5000;
        $list = LogAtividade::model()->findAll($criteria);

        return new CArrayDataProvider($list, array(
            'sort' => array('defaultOrder' => 'data_hora_servidor DESC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    /**
    * Retrieves a list of models based on the current search/filter conditions.
    * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
    */
    public function searchExtra() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        //$this->fk_empresa = 1;
        !empty($this->data) ? $data = date('Y-m-d', CDateTimeParser::parse($this->data, 'dd/MM/yyyy')) : $data = '';
        !empty($this->duracao) ? $duracao = explode("AND", $this->duracao) : $duracao = '';
        !empty($this->hora_host) ? $hora_host = explode("AND", $this->hora_host) : $hora_host = '';

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('descricao', $this->descricao, true);
        //$criteria->compare('duracao', $this->duracao, true);
        $criteria->compare('data', $data, true);
        $criteria->compare('title_completo', $this->title_completo, true);
        $criteria->compare('hora_host', $this->hora_host, true);
        $criteria->compare('data_hora_servidor', $this->data_hora_servidor, true);
        !empty($this->duracao) ? $criteria->condition = "duracao BETWEEN '$duracao[0]' AND '$duracao[1]'" : $this->duracao = '';
        !empty($this->hora_host) ? $criteria->condition = "hora_host BETWEEN '$hora_host[0]' AND '$hora_host[1]'" : $this->hora_host = '';

        $idUser = Yii::app()->user->id;
        $serial = UserGroupsUser::model()->findByAttributes(array("id" => $idUser));
        $serial = $serial->serial_empresa;

        if ($criteria->condition == null) {
            $fk_empresa = $this->getEmpresaId();
            $criteria->condition = " t.serial_empresa like '$serial' AND t.descricao NOT LIKE '' AND t.atividade_extra = 1 ";
        } else {
            $fk_empresa = $this->getEmpresaId();
            $criteria->condition .= " AND t.serial_empresa like '$serial' AND t.descricao NOT LIKE '' AND t.atividade_extra = 1 ";
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'data_hora_servidor DESC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function searchAltasMedicoes()
    {

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('descricao', $this->descricao, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('title_completo', $this->title_completo, true);
        $criteria->compare('hora_host', $this->hora_host, true);
        $criteria->compare('data_hora_servidor', $this->data_hora_servidor, true);
        $criteria->addCondition('fk_empresa IS NOT NULL');
        $criteria->addCondition("descricao != 'Ocioso'");
        $criteria->addCondition("duracao > '01:00:00'");
        $criteria->addCondition("programa != ''");
        $criteria->order = 'duracao DESC,data DESC';
        $criteria->with = 'empresa';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }


    public function searchOcioAposExpediente()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'e.nome as fk_empresa,usuario,data,hora_host,title_completo';
        $criteria->join = 'INNER JOIN empresa AS e ON e.id = t.fk_empresa';
        $criteria->join .= ' INNER JOIN empresa_has_parametro ep ON t.fk_empresa = ep.fk_empresa';
        $criteria->addCondition('t.hora_host > TIME(ep.horario_saida)');
        $criteria->addCondition('t.descricao = "Ocioso"');
        $criteria->addCondition('t.duracao > "02:00:00"');
        $criteria->compare('t.fk_empresa', $this->fk_empresa, true);
        $criteria->compare('t.usuario', $this->usuario, true);
        $criteria->compare('t.title_completo', $this->title_completo, true);
        $criteria->compare('t.hora_host', $this->hora_host, true);
        $criteria->compare('t.data', $this->data, true);
        $criteria->order = 'data DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function getAltaOciosidadeByDuracao($fk_empresa, $data, $duracao)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "t.data, p.id as fk_colaborador, t.id, t.duracao as duracao, t.hora_host as hora_final";
        $criteria->join = 'INNER JOIN colaborador as p ON p.fk_empresa = t.fk_empresa AND p.ad = t.usuario';
        $criteria->addCondition('t.fk_empresa = ' . $fk_empresa);
        $criteria->addCondition("t.atividade_extra = 0");
        $criteria->addCondition("t.data = '$data'");
        $criteria->addCondition("descricao = 'Ocioso'");
        $criteria->addCondition("duracao > '$duracao'");
        $criteria->order = 'data DESC';
        return $this->findAll($criteria);
    }

    public function has_relations() {
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
            return true;
        }
        return false;
    }


    public function getRegistrosExcl($usuario, $dados, $import = false)
    {
        $criteria = new CDbCriteria();
        $data = MetodosGerais::dataAmericana($dados['data']);
        $criteria->addCondition('fk_empresa='.MetodosGerais::getEmpresaId());
        $criteria->addCondition("data = '$data'");
        if ($import)
            $criteria->addBetweenCondition('data_hora_servidor', $data . ' ' . MetodosGerais::setHoraServidor($dados['hora_saida']), $data . ' ' . MetodosGerais::setHoraServidor($dados['hora_host']));
        else
            $criteria->addBetweenCondition('data_hora_servidor', $data . ' ' . MetodosGerais::setHoraServidor($dados['hora_saida'] . ":00"), $data . ' ' . MetodosGerais::setHoraServidor($dados['hora_host'] . ":00"));
        $criteria->addCondition('usuario= "'.$usuario.'"');
        $criteria->addCondition('atividade_extra=0');

        return $this->findAll($criteria);
    }

    //adicionado - 25/02/2014
    public function getTempoPorContratoByAtt($contrato, $date_from, $date_to, $programa = "", $colaborador = "") {

        $date_from = MetodosGerais::dataAmericana($date_from);
        $date_to = MetodosGerais::dataAmericana($date_to);

        $this->fk_empresa = $this->getEmpresaId();

        $sql = "SELECT pe.nome, pe.sobrenome, at.descricao, pe.fk_equipe as equipe,
        SUM(TIME_TO_SEC(at.`duracao`)) as duracao, (pe.salario/220) as valor_hora
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
        WHERE pe.fk_empresa = '{$this->fk_empresa}'
        AND at.descricao LIKE '%{$contrato}%' ";

        if ($date_from != '' && $date_to != '')
        $sql .= " AND at.data BETWEEN '{$date_from}' AND '{$date_to}'";
        if ($colaborador != '')
        $sql .= " AND pe.ad = '{$colaborador}' ";
        if ($programa != '')
        $sql .= " AND at.programa LIKE '{$programa}' ";
        $sql .= " GROUP BY pe.nome"
        . " ORDER BY duracao DESC";

        $command = Yii::app()->getDb()->createCommand($sql);

        return $command->queryAll();
    }

    public function getProgramaSubtela($descricao) {
        $this->fk_empresa = $this->getEmpresaId();
        $sql = "SELECT DISTINCT p.nome
        FROM programa_permitido AS p
        INNER JOIN log_atividade AS log ON log.descricao LIKE CONCAT( '%', p.nome, '%' )
        WHERE p.fk_empresa = $this->fk_empresa
        AND log.descricao LIKE '%$descricao%'";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getProgramaSb($descricao) {
        $s = $this->getProgramaSubtela($descricao);

        if (!empty($s)) {
            return $s[0]['nome'];
        } else
            return 'Não Identificado';
    }

    //modificado
    public function getProdutividadeDiaria($colaborador, $data) {
        $fkEmpresa = MetodosGerais::getEmpresaId();
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT DISTINCT (HOUR( at.hora_host )+1) AS hora, SUM( TIME_TO_SEC( at.duracao ) ) AS duracao, p.nome
        FROM log_atividade AS at
        INNER JOIN colaborador AS p ON p.ad = at.usuario
        WHERE at.data LIKE '$data' AND
        (at.`programa` IN
            (SELECT nome FROM programa_permitido WHERE (fk_empresa = $fkEmpresa AND fk_equipe = p.fk_equipe)
            OR (fk_empresa = $fkEmpresa AND fk_equipe IS NULL)))
            AND at.descricao NOT LIKE ''
            AND at.fk_empresa = $fkEmpresa
            AND at.usuario = '$colaborador'
            GROUP BY hora";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getMediaProdEquipeDia($equipe, $data)
    {
        $fkEmpresa = MetodosGerais::getEmpresaId();
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT DISTINCT (HOUR( at.hora_host )+1) AS hora , SUM(TIME_TO_SEC(at.duracao))/count(distinct(at.usuario)) AS duracao
        FROM log_atividade AS at
        INNER JOIN colaborador AS p ON p.ad = at.usuario
        WHERE at.data LIKE '$data' AND
        (at.`programa` IN
            (SELECT nome FROM programa_permitido WHERE (fk_empresa = $fkEmpresa AND fk_equipe = p.fk_equipe)
            OR (fk_empresa = $fkEmpresa AND fk_equipe IS NULL)))
            AND at.descricao NOT LIKE ''
            AND at.fk_empresa = $fkEmpresa
            AND p.fk_equipe = $equipe
            GROUP BY hora;";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getSalarioEquipe() {
        $this->fk_empresa = $this->getEmpresaId();
        $sqlBase = "SELECT SUM(p.salario) as salario_equipe,
        (TIME_TO_SEC(p.`horas_semana`)*4)/3600 as hora_total,
        eq.nome
        FROM `colaborador` p
        INNER JOIN equipe as eq ON p.fk_equipe = eq.id
        AND eq.fk_empresa = '" . $this->fk_empresa . "'
        GROUP BY p.fk_equipe
        ORDER BY eq.id asc";

        $command = Yii::app()->getDb()->createCommand($sqlBase);
        return $command->queryAll();
    }

    public function getCoeficienteEquipe() {
        $this->fk_empresa = $this->getEmpresaId();
        $sqlBase = "SELECT FORMAT((SUM(TIME_TO_SEC(at.`duracao`)))/3600, 2) as horas_trabalhadas,
        eq.nome
        FROM  `log_atividade` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
        INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
        WHERE YEAR( CURDATE( ) ) = YEAR( at.data )
        AND MONTH( CURDATE( ) ) = MONTH( at.data )
        AND WEEKOFYEAR(at.data) BETWEEN WEEKOFYEAR(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE()),'-01')) AND WEEKOFYEAR(CURDATE( ))
        AND WEEKDAY(at.data) < 5
        AND  (at.`programa` in ( SELECT nome FROM programa_permitido WHERE fk_empresa = $this->fk_empresa))
        AND eq.fk_empresa = '" . $this->fk_empresa . "'
        GROUP BY eq.nome
        ORDER BY eq.id asc";

        $command = Yii::app()->getDb()->createCommand($sqlBase);

        return $command->queryAll();
    }

    public function getEquipes() {
        $this->fk_empresa = $this->getEmpresaId();

        $sql = "SELECT * FROM `equipe` WHERE fk_empresa = '" . $this->fk_empresa . "'  ORDER BY id asc";
        $cmd = Yii::app()->getDb()->createCommand($sql)->queryAll();

        $res = array();
        foreach ($cmd as $value) {
            $res[] = $value['nome'];
        }
        return $res;
    }

    //modificado
    public function getTempoProduzidoColaboradorPorAtributos($date_from, $date_to, $colaborador = '') {
        $this->fk_empresa = $this->getEmpresaId();

        $sql = " SELECT  (pe.salario) /22 AS salario_colaborador, ((TIME_TO_SEC( pe.`horas_semana` )) /3600) /5 AS hora_total, CONCAT(pe.nome,' ',pe.sobrenome) as nome,
        SUM( TIME_TO_SEC( at.`duracao` ) ) AS Duracao_colaborador, pe.id as fk_colaborador
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
        WHERE at.data BETWEEN  '" . $date_from . "' AND  '" . $date_to . "'
        AND  (at.`programa` IN
        (SELECT nome FROM programa_permitido WHERE (fk_empresa = $this->fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $this->fk_empresa AND fk_equipe IS NULL)))
        AND at.descricao NOT LIKE ''
        AND pe.fk_empresa = $this->fk_empresa";
        if ($colaborador != 'todos_colaboradores')
        $sql .= ' AND pe.id = ' . $colaborador . ' ';
        $sql .= ' GROUP BY pe.nome
        ORDER BY pe.id';
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getSalarioTempoEquipe($dias,$fk_equipe) {
        $this->fk_empresa = $this->getEmpresaId();
        $sql = "SELECT  (SUM( p.salario ) * $dias)/30 AS salario_equipe,
        (SUM(TIME_TO_SEC( p.`horas_semana` )) /3600) /5 AS hora_total, eq.nome
        FROM  `colaborador` p
        INNER JOIN equipe AS eq ON p.fk_equipe = eq.id
        AND eq.id = $fk_equipe
        GROUP BY p.fk_equipe
        ORDER BY eq.id ASC ";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getTempoTotalEquipe($opcao, $tipo) {
        $serial = $this->getSerial();
        $sql = "SELECT nome , SUM(TIME_TO_SEC(horas_semana)/5) as tempo_total FROM colaborador
        WHERE serial_empresa like '$serial' AND ativo = 1";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND fk_equipe = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND id = {$opcao}";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getTempoProduzidoByAtributos($date_from, $date_to, $opcao, $tipo) {
        if ($date_from != '') {
            $date_from = explode('/', $date_from);
            $date_from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[0];
        }
        if ($date_to != '') {
            $date_to = explode('/', $date_to);
            $date_to = $date_to[2] . '-' . $date_to[1] . '-' . $date_to[0];
        }
        $this->fk_empresa = $this->getEmpresaId();
        $serial = $this->getSerial();
        $sql = "SELECT eq.nome, at.`programa`, SUM(TIME_TO_SEC(at.`duracao`)) as duracao
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario
        INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
        WHERE
        at.fk_empresa = {$this->fk_empresa} AND
        at.programa NOT LIKE 'Atividade Externa' AND
        (at.`programa` IN
            (SELECT nome FROM programa_permitido WHERE (fk_empresa = $this->fk_empresa AND fk_equipe = pe.fk_equipe)
            OR (fk_empresa = $this->fk_empresa AND fk_equipe IS NULL)))
            AND eq.fk_empresa = '" . $this->fk_empresa . "' "
            . "AND at.data BETWEEN '{$date_from}' AND '{$date_to}' AND at.descricao NOT LIKE '' ";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND pe.id = {$opcao} ";
        $sql .= " GROUP BY at.programa";
        $sql .= " ORDER BY duracao desc";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getTempoProduzidoSitesByAtributos($date_from, $date_to, $opcao, $tipo) {
        if ($date_from != '') {
            $date_from = explode('/', $date_from);
            $date_from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[0];
        }
        if ($date_to != '') {
            $date_to = explode('/', $date_to);
            $date_to = $date_to[2] . '-' . $date_to[1] . '-' . $date_to[0];
        }
        $this->fk_empresa = $this->getEmpresaId();
        $sql = "SELECT DISTINCT p.nome as programa , log.descricao , sum(log.duracao) as duracao "
        . "FROM site_permitido AS p INNER JOIN log_atividade_consolidado AS log ON log.descricao LIKE CONCAT( '%', p.nome, '%' )"
        . " INNER JOIN colaborador AS pe ON pe.ad = log.usuario "
        . "INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id "
        . "WHERE pe.fk_empresa = $this->fk_empresa "
        . "AND p.fk_empresa = $this->fk_empresa "
        . "AND log.data BETWEEN '$date_from' AND '$date_to' ";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND pe.id = {$opcao}";
        $sql .= " GROUP BY p.nome order by duracao desc";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getTempoProgramaNaoIdentificado($date_from, $date_to, $opcao, $tipo) {
        if ($date_from != '') {
            $date_from = explode('/', $date_from);
            $date_from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[0];
        }
        if ($date_to != '') {
            $date_to = explode('/', $date_to);
            $date_to = $date_to[2] . '-' . $date_to[1] . '-' . $date_to[0];
        }
        $this->fk_empresa = $this->getEmpresaId();
        $sql = "SELECT
        at.`programa`, at.descricao,
        SUM(TIME_TO_SEC(at.`duracao`)) as duracao
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario AND pe.fk_empresa = at.fk_empresa
        INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
        WHERE pe.fk_empresa = $this->fk_empresa AND at.data BETWEEN '{$date_from}' AND '{$date_to}' AND at.programa not in ('Google Chrome','Internet Explorer','Firefox','Mozilla Firefox')
        AND (TRIM(at.`programa`) NOT IN
        (SELECT TRIM(nome) FROM programa_permitido WHERE (fk_empresa = $this->fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $this->fk_empresa AND fk_equipe IS NULL)))
        AND at.descricao not like '' AND at.descricao not like 'Ocioso'
        ";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND pe.id = {$opcao}";
        $sql .= " GROUP BY at.descricao, at.programa ORDER BY duracao desc ";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getTempoSiteNaoIdentificado($date_from, $date_to, $opcao, $tipo) {
        if ($date_from != '') {
            $date_from = explode('/', $date_from);
            $date_from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[0];
        }
        if ($date_to != '') {
            $date_to = explode('/', $date_to);
            $date_to = $date_to[2] . '-' . $date_to[1] . '-' . $date_to[0];
        }
        $this->fk_empresa = $this->getEmpresaId();
        $sql = "SELECT
        at.`programa`, at.descricao,
        SUM(TIME_TO_SEC(at.`duracao`)) as duracao
        FROM  `log_atividade_consolidado` AS at
        INNER JOIN colaborador AS pe ON pe.ad = at.usuario AND pe.fk_empresa = at.fk_empresa
        INNER JOIN equipe AS eq ON pe.fk_equipe = eq.id
        WHERE pe.fk_empresa = $this->fk_empresa AND at.data BETWEEN '{$date_from}' AND '{$date_to}' AND at.programa in ('Google Chrome','Opera','Safari','Internet Explorer','Mozilla Firefox')";
        
        $sitesPermitidos = SitePermitido::model()->findAllByAttributes(array('fk_empresa' => $this->fk_empresa));
        foreach ($sitesPermitidos as $site) {
            $sql .= " AND TRIM(at.descricao) NOT LIKE '%$site->nome%'";
        }

        $sql .= " AND at.descricao NOT LIKE '' AND at.descricao NOT LIKE 'Ocioso'";

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $sql .= " AND eq.id = {$opcao}";
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $sql .= " AND pe.id = {$opcao}";
        $sql .= " GROUP BY at.descricao, at.programa ORDER BY duracao desc ";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getEmpresaId() {
        return MetodosGerais::getEmpresaId();
    }

    public function getSerial() {
        $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        return $usuario->serial_empresa;
    }

    public function getTotalPorcentagem($serial){
        $sql = "SELECT SUM(time_to_sec(duracao)) AS total FROM `log_atividade_consolidado` WHERE  serial_empresa like '{$serial}'";

        $command = Yii::app()->getDb()->createCommand($sql);
        $total = $command->queryAll();
        return $total;
    }

    public function getPorcentagemModoPromiscuo($fk_empresa, $colaborador, $date_from, $date_to)
    {
        $date_from = MetodosGerais::dataAmericana($date_from);
        $date_to = MetodosGerais::dataAmericana($date_to);
        $serial = $this->getSerial();
        $sql = "SELECT SUM(time_to_sec(duracao)) AS total FROM `log_atividade_consolidado` WHERE data BETWEEN '$date_from' AND '$date_to' AND serial_empresa like '{$serial}' ";
        if ($colaborador != '')
        $sql .= " AND usuario = '$colaborador'  ";

        $command = Yii::app()->getDb()->createCommand($sql);
        $total = $command->queryAll();

        $sql2 = "SELECT SUM(time_to_sec(duracao)) AS promiscuo FROM `log_atividade_consolidado`
        WHERE TRIM(programa) not in (SELECT TRIM(nome) as nome FROM programa_permitido WHERE serial_empresa = '$serial')"
        . "AND descricao not like '' AND descricao not like 'Ocioso' AND data BETWEEN '$date_from' AND '$date_to' AND serial_empresa like '{$serial}'";

        if ($colaborador != '')
            $sql2 .= " AND usuario = '$colaborador' ";

        $command = Yii::app()->getDb()->createCommand($sql2);
        $modo_promiscuo = $command->queryAll();

        $sql3 = "SELECT descricao,SUM(time_to_sec(duracao)) AS duracao FROM log_atividade_consolidado
        WHERE TRIM(programa) not in (SELECT TRIM(nome) as nome FROM programa_permitido WHERE serial_empresa = '$serial')"
        . "AND descricao not like '' AND descricao not like 'Ocioso' AND data BETWEEN '$date_from' AND '$date_to' "
        . "AND serial_empresa like '{$serial}'";

        if ($colaborador != '')
            $sql3 .= " AND usuario = '$colaborador' ";

        $sql3 .= "GROUP BY descricao ORDER BY duracao DESC LIMIT 10";

        $command = Yii::app()->getDb()->createCommand($sql3);
        $lista = $command->queryAll();

        return array($total[0], $modo_promiscuo[0], $lista);
    }

    public function getProgramasProdutivosDia($colaborador, $data) {
        //filtro para ambiente demo
        if(MetodosGerais::getEmpresaId() !=41){
            $this->fk_empresa = $this->getEmpresaId();
            $serial = $this->getSerial();
        }else{
            $this->fk_empresa = 22;
            $serial = 'EY3I-0DA4-Z6KD-BC9M';
        }
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT at.programa , at.descricao, SUM(TIME_TO_SEC (at.duracao)) as duracao FROM log_atividade_consolidado as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        WHERE (TRIM(at.`programa`) IN
        (SELECT (nome) FROM programa_permitido WHERE (fk_empresa = $this->fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $this->fk_empresa AND fk_equipe IS NULL)))
        AND data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial' AND descricao not like 'CAcDynInputWndControl'"
        . " GROUP BY descricao ORDER BY descricao ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getTempoAlmoco($usuario,$data,$horario){
        $data = MetodosGerais::dataAmericana($data);
        $fkEmpresa = (MetodosGerais::getEmpresaId()==41)? '22' : MetodosGerais::getEmpresaId(); //filtro ambiente demo
        $hora_servidor = "$data $horario";
        $usuario = Colaborador::model()->findByPk($usuario)->ad;
        $criteria = new CDbCriteria();
        $criteria->addCondition("fk_empresa = $fkEmpresa");
        $criteria->addCondition("usuario = '$usuario'");
        $criteria->addCondition("data_hora_servidor > '$hora_servidor'");
        return $this->findAll($criteria);
    }

    public function getProgramasProdutivosAlmoco($colaborador, $data,$almocoInicio,$almocoFim) {
        $this->fk_empresa = $this->getEmpresaId();
        $serial = $this->getSerial();
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT at.programa , at.descricao, SUM(TIME_TO_SEC (at.duracao)) as duracao FROM log_atividade as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        INNER JOIN contrato obra ON at.descricao LIKE CONCAT('%', obra.codigo,  '%' )
        WHERE obra.fk_empresa = $this->fk_empresa AND (TRIM(at.`programa`) IN
        (SELECT (nome) FROM programa_permitido WHERE (fk_empresa = $this->fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $this->fk_empresa AND fk_equipe IS NULL)))
        AND data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial'
        and data_hora_servidor BETWEEN '$data $almocoInicio'  AND '$data $almocoFim'"
        . " GROUP BY descricao ORDER BY descricao ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getProgramasNaoProdutivosDia($colaborador, $data) {
        //filtro para ambiente demo
        if(MetodosGerais::getEmpresaId() !=41){
            $this->fk_empresa = $this->getEmpresaId();
            $serial = $this->getSerial();
        }else{
            $this->fk_empresa = 22;
            $serial = 'EY3I-0DA4-Z6KD-BC9M';
        }
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT at.programa , at.descricao, TIME_TO_SEC (at.duracao) as duracao FROM log_atividade_consolidado as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        WHERE (TRIM(at.`programa`) NOT IN
        (SELECT TRIM(nome) FROM programa_permitido WHERE (fk_empresa = $this->fk_empresa AND fk_equipe = pe.fk_equipe)
        OR (fk_empresa = $this->fk_empresa AND fk_equipe IS NULL)))
        AND data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial' AND descricao not like '' AND descricao not like 'Ocioso' "
        . "AND programa NOT LIKE '%Google Chrome%' AND programa NOT LIKE '%Internet Explorer%' AND programa NOT LIKE '%Mozilla%' "
        . "ORDER BY descricao ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getSitesProdutivos($colaborador, $data, $site) {
        //filtro ambiente demo
        if(MetodosGerais::getEmpresaId() !=41){
            $this->fk_empresa = $this->getEmpresaId();
            $serial = $this->getSerial();
        }else{
            $this->fk_empresa = 22;
            $serial = 'EY3I-0DA4-Z6KD-BC9M';
        }
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT lc.programa , lc.descricao, TIME_TO_SEC (lc.duracao) as duracao
        FROM log_atividade_consolidado as lc
        INNER JOIN colaborador as pe ON pe.ad = lc.usuario
        WHERE data like '$data' AND pe.id = $colaborador
        AND lc.serial_empresa like '$serial'
        AND lc.descricao  like '%$site%'
        AND (programa LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' )
        ORDER BY descricao ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getSitesImprodutivos($colaborador, $data, $sites) {
        $serial = $this->getSerial();
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT programa , descricao, TIME_TO_SEC (duracao) as duracao FROM log_atividade_consolidado as lc
        INNER JOIN colaborador as pe ON pe.ad = lc.usuario
        WHERE data like '$data' AND pe.id = $colaborador AND lc.serial_empresa like '$serial'"
        . " AND descricao not like '' AND descricao not like 'Ocioso' "
        . "AND (programa LIKE '%Google Chrome%' OR programa LIKE '%Internet Explorer%' OR programa LIKE '%Firefox%' ) "
        ;
        if ($sites != "")
        $sql .= "AND descricao not in ($sites)  ";
        $sql .= "ORDER BY descricao ASC";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getOciosoDia($colaborador, $data) {
        //filtro ambiente demo
        if(MetodosGerais::getEmpresaId() !=41){
            $this->fk_empresa = $this->getEmpresaId();
            $serial = $this->getSerial();
        }else{
            $this->fk_empresa = 22;
            $serial = 'EY3I-0DA4-Z6KD-BC9M';
        }
        $parametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $this->getEmpresaId()));
        $this->hora_alomoco_inicio = MetodosGerais::setHoraServidor($parametros->almoco_inicio);
        $this->hora_alomoco_fim = MetodosGerais::setHoraServidor($parametros->almoco_fim);
        $data = MetodosGerais::dataAmericana($data);
        $sql = "SELECT at.programa , at.descricao, SUM(TIME_TO_SEC (at.duracao)) as duracao FROM log_atividade as at
        INNER JOIN colaborador as pe ON pe.ad = at.usuario
        WHERE at.duracao <= '04:00:00' AND at.data like '$data' AND pe.id = $colaborador AND at.serial_empresa like '$serial' AND at.descricao like 'Ocioso'  "
        . "AND at.data_hora_servidor NOT  BETWEEN '$data $this->hora_alomoco_inicio' AND '$data $this->hora_alomoco_fim' AND at.serial_empresa like '$serial'"
        . " ORDER BY duracao DESC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getHorarioEntrada($colaborador, $data) {
        $data = MetodosGerais::dataAmericana($data);
        //filtro ambiente demo
        if(MetodosGerais::getEmpresaId() !=41){
            $this->fk_empresa = $this->getEmpresaId();
            $serial = $this->getSerial();
        }else{
            $this->fk_empresa = 22;
            $serial = 'EY3I-0DA4-Z6KD-BC9M';
        }
        $sql = "SELECT hora_host FROM log_atividade as lc INNER JOIN colaborador as pe ON pe.ad = lc.usuario"
        . " WHERE pe.id = $colaborador AND lc.serial_empresa like '$serial'
        AND data like '$data' ORDER BY lc.id ASC";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getColaboradorInicio($date_from, $date_to,$colaborador="") {
        $date_from = MetodosGerais::dataAmericana($date_from);
        $date_to = MetodosGerais::dataAmericana($date_to);
        $sql = "SELECT log.data as data , log.data_hora_servidor as hora_inicio , CONCAT(p.nome,' ',p.sobrenome) as nome , log.usuario
        FROM log_atividade as log INNER JOIN colaborador as p on log.usuario = p.ad
        WHERE  log.data BETWEEN '$date_from' AND '$date_to' AND log.serial_empresa like '{$this->getSerial()}' AND p.serial_empresa like '{$this->getSerial()}'";
        if(!empty($colaborador)){
            $colaborador = Colaborador::model()->findByPk($colaborador)->ad;
            $sql .= " AND log.usuario like '$colaborador'";
        }
        $sql .= " GROUP BY p.nome,log.data ORDER BY log.id ASC";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getColaboradorFim($date, $colaborador) {
        $sql = "SELECT  log.data_hora_servidor as hora_final
        FROM log_atividade as log INNER JOIN colaborador as p on log.usuario = p.ad
        WHERE p.ad like '$colaborador' AND log.data like '$date' AND log.serial_empresa like '{$this->getSerial()}' AND p.serial_empresa like '{$this->getSerial()}'"
        . " ORDER BY log.id DESC LIMIT 1";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }
}
