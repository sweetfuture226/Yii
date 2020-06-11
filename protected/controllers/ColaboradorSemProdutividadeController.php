<?php

class ColaboradorSemProdutividadeController extends Controller
{
    public $title_action = "";
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'userGroupsAccessControl',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'gerarPDF', 'SetJustificativa', 'SetJustificativaByData'),
				'groups' => array('coordenador', 'empresa', 'root', 'demo'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $start = MetodosGerais::inicioContagem();
        $this->title_action=Yii::t("smith",'Colaboradores sem produtividade');
        $model=new ColaboradorSemProdutividade('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ColaboradorSemProdutividade']))
			$model->attributes=$_GET['ColaboradorSemProdutividade'];
        LogAcesso::model()->saveAcesso('Produtividade', 'Relatório de dias sem produtividade', 'Colaboradores sem produtividade', MetodosGerais::tempoResposta($start));
		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionGerarPDF() {
		if ($_POST['data'] != '') {
			$data = explode('/', $_POST['data']);
			$data = $data[2] . '-' . $data[1] . '-' . $data[0];
		} else {
			$data = '';
		};
		if ($_POST['equipe'] != '') {
			$equipe = $_POST['equipe'];
		} else {
			$equipe = '';
		};
		if ($_POST['nome'] != '') {
			$nome = $_POST['nome'];
		} else {
			$nome = '';
		};

		$criteria=new CDbCriteria;
		$criteria->select = "t.nome, t.data, eq.nome as equipe";
		$criteria->compare('t.data',$data);
		$criteria->compare('t.nome',$_POST['nome'],true);
		$criteria->compare('eq.nome',$_POST['equipe'],true);
		$criteria->join = "INNER JOIN colaborador as p ON t.fk_colaborador = p.id ";
		$criteria->join .= "INNER JOIN equipe as eq ON eq.id = p.fk_equipe";
		$criteria->addCondition("p.fk_empresa = " . MetodosGerais::getEmpresaId());
		$criteria->addCondition("t.fk_empresa = " . MetodosGerais::getEmpresaId());

		$resultados = ColaboradorSemProdutividade::model()->findAll($criteria);

		$empresaId = MetodosGerais::getEmpresaId();
		$imagem = Empresa::model()->findByPK($empresaId)->logo;
		$style = MetodosGerais::getStyleTable();
        $rodape = MetodosGerais::getRodapeTable();

		$header = '<page orientation="portrait" backtop="15mm" backbottom="20mm" format="A4" >
            <page_header>
                <div class="header_page">
                    <img class="header_logo_page" src="' . $imagem . '">
                    <div class="header_title">
                        <span>'.Yii::t("smith", 'RELATÓRIO DE COLABORADORES SEM PRODUTIVIDADE').'</span><br>
                    </div>
                    <div class="header_date">
                        <p>'.Yii::t("smith", 'Data').':  ' . date("d/m/Y").'
                            <br>'.Yii::t('smith', 'Pág.').' ([[page_cu]]/[[page_nb]]) </p>
                        </div>
                </div>
            </page_header>
        </page>';
        $html = $header;
        $corpo = "";
        $html .= $rodape;
        $html .=  '<table class="table_custom" border="1px">
            <tr style="background-color: #CCC; text-decoration: bold;">
                <th>'.Yii::t("smith", 'Equipe').'</th>
                <th>'.Yii::t("smith", 'Nome').'</th>
                <th>'.Yii::t("smith", 'Data').'</th>
            </tr>';
        foreach ($resultados as $chave=>$resultado){
        	$resultadoData = explode('-', $resultado['data']);
        	$resultadoData = $resultadoData[2] . '/' . $resultadoData[1] . '/' . $resultadoData[0];
            $html .= '<tr> '
                . '<td style="text-align: center; width: 232.5px;">'.$resultado['equipe'].'</td>'
                . '<td style="text-align: center; width: 232.5px;">'.$resultado['nome'].'</td>'
                . '<td style="text-align: center; width: 232.5px;">'.$resultadoData.'</td></tr>';
       	}
       	$html .= "</table><br>";

       	$arquivo = '';
       	if ($_POST['equipe'] != '') $arquivo = '_' . $arquivo . ucwords(strtolower($resultado['equipe']));
       	if ($_POST['nome'] != '') {
       		$arqNome = MetodosGerais::reduzirNome($resultado['nome']);
       		$arqNome = explode(' ', $arqNome);
       		$arqNome = $arqNome[0] . $arqNome[1];
       		$arquivo = $arquivo . '_' . $arqNome;
       	}
       	if ($_POST['data'] != '') {
       		$arqData = str_replace('/', '-', $_POST['data']);
       		$arquivo = $arquivo . '_' . $arqData;
       	}

        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($html . $style);
        $html2pdf->Output(Yii::t('smith', 'relatorioColaboradoresSemProdutividade') . $arquivo . '.pdf');

	}

    public function actionSetJustificativa($fk_colaborador, $data_inicio, $data_fim, $descricao)
    {
        $data_inicio = MetodosGerais::dataAmericana($data_inicio);
        $data_fim = MetodosGerais::dataAmericana($data_fim);
        $model = ColaboradorSemProdutividade::model()->findAll(array('condition' => "fk_colaborador = $fk_colaborador AND data >= '$data_inicio' AND data <= '$data_fim'"));
        foreach ($model as $key => $value) {
            $value->justificativa = $descricao;
            $value->save(false);
        }
        echo "success";
    }

}
