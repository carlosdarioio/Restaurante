<?php

include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/modules/index/model/UserData.php";
include "core/modules/index/model/SellData.php";
include "core/modules/index/model/OperationData.php";
include "core/modules/index/model/ProductData.php";
include "core/modules/index/model/ConfigurationData.php";
include "fpdf/fpdf.php";

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();

$title  = ConfigurationData::getByPreffix("ticket_title")->val;
$head1  = ConfigurationData::getByPreffix("ticket_head1")->val;
$head2  = ConfigurationData::getByPreffix("ticket_head2")->val;

$pdf = new FPDF($orientation='P',$unit='mm', array(80,250));
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->setXY(5,0);
$pdf->setY(2);
$pdf->setX(2);
$pdf->Cell(5,5,$title);
$pdf->SetFont('Arial','B',7);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setX(2);
$pdf->Cell(5,11,$head1);
$pdf->setX(2);
$pdf->Cell(5,17,$head2);
$pdf->setX(2);
$pdf->Cell(5,23,'----------------------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,29,'CANT.          ARTICULO                  PRECIO              TOTAL');

$total =0;
$off = 35;
foreach($operations as $op){
$product = $op->getProduct();
$pdf->setX(2);
$pdf->Cell(5,$off,"$op->q");
$pdf->setX(7);
$pdf->Cell(35,$off,  strtoupper(substr($product->name, 0,12)) );
$pdf->setX(40);
$pdf->Cell(11,$off,         "$ ".number_format($product->price_out,2,".",",") ,0,0,"R");
$pdf->setX(59);
$pdf->Cell(11,$off,                      "$ ".number_format($op->q*$product->price_out,2,".",",") ,0,0,"R");

//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->price_out;
$off+=6;
}


$pdf->setX(2);
$pdf->Cell(5,$off+18,"TOTAL: " );
$pdf->setX(65);
$pdf->Cell(5,$off+18,"$ ".number_format($total - ($total*$sell->discount/100),2,".",","),0,0,"R");


$pdf->setX(2);
$pdf->Cell(5,$off+36,'----------------------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,$off+42,"FOLIO: ".$sell->id.' - FECHA: '.$sell->created_at);
$pdf->setX(2);
$pdf->Cell(5,$off+48,'ATENDIDO POR '.strtoupper($user->name." ".$user->lastname));
$pdf->setX(2);
$pdf->Cell(5,$off+54,'GRACIAS POR TU COMPRA ');


$pdf->output();
