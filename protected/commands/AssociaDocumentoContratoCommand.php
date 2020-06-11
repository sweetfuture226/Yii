<?php

class AssociaDocumentoContratoCommand extends CConsoleCommand
{
    public function run()
    {
        try {
            $contratosHasDocumento = ContratoHasDocumentoOnCreate::model()->findAllByAttributes(array('data' => date('Y-m-d')));
            foreach ($contratosHasDocumento as $obj) {
                $codigo = explode(',', $obj->contrato->codigo);
                $documentos = array();
                foreach ($codigo as $item) {
                    $query = DocumentoSemContrato::model()->findAll(array('condition' => 'fk_empresa =' . $obj->fk_empresa . " AND documento like '%" . trim($item) . "%' "));
                    $documentos = array_merge($documentos, $query);
                }
                foreach ($documentos as $objDoc) {
                    $modelLogContrato = new GrfProjetoConsolidado();
                    $modelLogContrato->documento = $objDoc->documento;
                    $modelLogContrato->duracao = $objDoc->duracao;
                    $modelLogContrato->data = $objDoc->data;
                    $modelLogContrato->fk_empresa = $objDoc->fk_empresa;
                    $modelLogContrato->fk_colaborador = $objDoc->fk_colaborador;
                    $modelLogContrato->fk_obra = $obj->fk_contrato;
                    $modelLogContrato->associado = 1;
                    if ($modelLogContrato->save()) {
                        $objDoc->delete();
                        echo "Documento: {$modelLogContrato->documento} associado ao contrato {$obj->contrato->nome} \n";
                    }

                }
                $obj->associado = 1;
                $obj->save();
            }
        } catch (Exception $e) {
            Logger::sendException($e);
        }
    }
}