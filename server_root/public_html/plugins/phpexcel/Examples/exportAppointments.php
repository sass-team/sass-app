<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Athens');

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
//require_once '../Build/PHPExcel.phar';
require_once dirname(__FILE__) . '/../../../../app/plugins/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';


// Create new PHPExcel object
echo date('H:i:s'), " Create new PHPExcel object", EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s'), " Set document properties", EOL;
$objPHPExcel->getProperties()->setCreator("SASS App | Automatic System")
	->setLastModifiedBy("SASS App | Automatic System")
	->setTitle("SASS Appointments - Semester x")
	->setSubject("Appointments")
	->setDescription("Document containing appointments for SASS.")
	->setKeywords("office sass appointments php")
	->setCategory("Appointments");


// Add some data
echo date('H:i:s'), " Add some data", EOL;
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', 'Day')
	->setCellValue('B1', 'Date')
	->setCellValue('C1', 'Month')
	->setCellValue('D1', 'Year')
	->setCellValue('E1', 'Time')
	->setCellValue('F1', 'LF Lname')
	->setCellValue('G1', 'Stud Lname')
	->setCellValue('H1', 'Stud Fname')
	->setCellValue('I1', 'Stud ID')
	->setCellValue('J1', 'Major')
	->setCellValue('K1', 'Mobile')
	->setCellValue('L1', 'e-mail')
	->setCellValue('M1', 'Course Code')
	->setCellValue('N1', 'Course Instructor')
	->setCellValue('O1', '# of Students')
	->setCellValue('P1', 'Outcome')
	->setCellValue('Q1', 'Actual Duration')
	->setCellValue('R1', 'Purpose');


$styleArray = array(
	'font' => array(
		'bold' => true,
		'name' => 'Times New Roman',
		'size' => 12
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
	),
	'borders' => array(
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THICK,
		),
		'right' => array(
			'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
		)
	),
	'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
		'rotation' => 90,
		'startcolor' => array(
			'argb' => 'FFA0A0A0',
		),
		'endcolor' => array(
			'argb' => 'FFFFFFFF',
		),
	),
);

$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(24);


// Rename worksheet
echo date('H:i:s'), " Rename worksheet", EOL;
$objPHPExcel->getActiveSheet()->setTitle('21-25 Oct');

$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1)
	->setCellValue('A1', 'Day')
	->setCellValue('B1', 'Date')
	->setCellValue('C1', 'Month')
	->setCellValue('D1', 'Year')
	->setCellValue('E1', 'Time')
	->setCellValue('F1', 'LF Lname')
	->setCellValue('G1', 'Stud Lname')
	->setCellValue('H1', 'Stud Fname')
	->setCellValue('I1', 'Stud ID')
	->setCellValue('J1', 'Major')
	->setCellValue('K1', 'Mobile')
	->setCellValue('L1', 'e-mail')
	->setCellValue('M1', 'Course Code')
	->setCellValue('N1', 'Course Instructor')
	->setCellValue('O1', '# of Students')
	->setCellValue('P1', 'Outcome')
	->setCellValue('Q1', 'Actual Duration')
	->setCellValue('R1', 'Purpose');


// Rename worksheet
echo date('H:i:s'), " Rename worksheet", EOL;
$objPHPExcel->getActiveSheet()->setTitle('28 Oct-1 Nov');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
echo date('H:i:s'), " Write to Excel2007 format", EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s'), " File written to ", str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)), EOL;
echo 'Call time to write Workbook was ', sprintf('%.4f', $callTime), " seconds", EOL;
// Echo memory usage
echo date('H:i:s'), ' Current memory usage: ', (memory_get_usage(true) / 1024 / 1024), " MB", EOL;


// Save Excel5 file
//echo date('H:i:s'), " Write to Excel5 format", EOL;
//$callStartTime = microtime(true);
//
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
//$callEndTime = microtime(true);
//$callTime = $callEndTime - $callStartTime;
//
//echo date('H:i:s'), " File written to ", str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)), EOL;
//echo 'Call time to write Workbook was ', sprintf('%.4f', $callTime), " seconds", EOL;
//// Echo memory usage
//echo date('H:i:s'), ' Current memory usage: ', (memory_get_usage(true) / 1024 / 1024), " MB", EOL;
//

// Echo memory peak usage
echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;

// Echo done
echo date('H:i:s'), " Done writing files", EOL;
echo 'Files have been created in ', getcwd(), EOL;
