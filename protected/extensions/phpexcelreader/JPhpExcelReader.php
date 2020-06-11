<?php

/**
 * JPhpExcelReader class file.
 *
 * @author jerry2801 <jerry2801@gmail.com>
 * @version alpha1
 *
 * A typical usage of JPhpExcelReader is as follows:
 * <pre>
 * Yii::import('application.extensions.phpexcelreader.JPhpExcelReader');
 * $data = new JPhpExcelReader('example.xls');
 * echo $data->dump(true,true);
 * </pre>
 */

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'excel_reader2.php';

class JPhpExcelReader extends Spreadsheet_Excel_Reader
{
}