<?php

//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

$img = $_POST['img'];
$imgTotal = $_POST['imgTotal'];
$locationTable = $_POST['locationPDF'];
$departmentTable = $_POST['departmentPDF'];
$topicTable = $_POST['topicPDF'];
$agentTable = $_POST['agentPDF'];

$img = str_replace('data:image/png;base64,', '', $img);
$imgTotal = str_replace('data:image/png;base64,', '', $imgTotal);
$img = str_replace(' ', '+', $img);
$imgTotal = str_replace(' ', '+', $imgTotal);
$data = base64_decode($img);
$dataTotal = base64_decode($imgTotal);
$file = 'assets/pdf/img'.date("YmdHis").'.png';
$fileTotal = 'assets/pdf/imgTotal'.date("YmdHis").'.png';

if (file_put_contents($file, $data)) {

} else {
    echo "<p>The Date Chart PDF part couldn't be created</p>";
}
if (file_put_contents($fileTotal, $dataTotal)) {

} else {
    echo "<p>The Total Chart PDF part couldn't be created</p>";
}

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('assets/TCPDF/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print

$img = file_get_contents($file);
$imgTotal = file_get_contents($fileTotal);

$locTable =  <<<EOF

    $locationTable
    <style>
        table{
            font-size: 10px;
        }
        td{
            height: 25px;
            text-align: center;
        }
        th{
            height: 25px;
            text-align: center;

        }
    </style>

EOF;

$depTable =  <<<EOF

    $departmentTable
    <style>
        table{
            font-size: 10px;
        }
        td{
            height: 25px;
            text-align: center;
        }
        th{
            height: 25px;
            text-align: center;

        }
    </style>

EOF;

$topTable =  <<<EOF

    $topicTable
    <style>
        table{
            font-size: 10px;
        }
        td{
            height: 25px;
            text-align: center;
        }
        th{
            height: 25px;
            text-align: center;

        }
    </style>

EOF;

$ageTable =  <<<EOF

    $agentTable
    <style>
        table{
            font-size: 10px;
        }
        td{
            height: 25px;
            text-align: center;
        }
        th{
            height: 25px;
            text-align: center;

        }
    </style>

EOF;

$pdf->Image('@' . $img, 10,30,190,100);
$pdf->Image('@' . $imgTotal, 10,140,190,100);

$pdf->setCellPaddings($left = '', $top = '5px', $right = '', $botton = '');

$pdf->AddPage();
$pdf->writeHTML($locTable, true, false, false, false, '');
$pdf->writeHTML($depTable, true, false, false, false, '');
$pdf->writeHTML($ageTable, true, false, false, false, '');
$pdf->AddPage();
$pdf->writeHTML($topTable, true, false, false, false, '');

/*$html =<<<EOD

<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);*/

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

unlink($file);
unlink($fileTotal);

//============================================================+
// END OF FILE
//============================================================+