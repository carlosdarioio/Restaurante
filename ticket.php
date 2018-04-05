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
//$pdf->Cell(5,11,$head1);
$pdf->setX(2);
//$pdf->Cell(5,17,$head2);
//Prueba

$pdf->setY(3);$pdf->setX(3);
$pdf->Cell(0,17,"Factura Numero: 000-001-01-04221441");
$pdf->setY(6);$pdf->setX(3);
$pdf->Cell(0,17,"Fecha de pago : 08/10/2016 10:52:35");
$pdf->setY(9);$pdf->setX(3);
$pdf->Cell(2,17,"Restaurante Yu Baiwan");
$pdf->setY(12);$pdf->setX(3);
$pdf->Cell(3,17,"Tel: 2540-1234");
$pdf->setY(15);$pdf->setX(3);
$pdf->Cell(4,17,"RTN: 0801900222641403");
$pdf->setY(18);$pdf->setX(3);
$pdf->Cell(5,17,"Domicilio Fiscal: Puerto Lempira");
$pdf->setY(21);$pdf->setX(3);
$pdf->Cell(6,17,"Direccion: Puerto Lempira 1c 1,2 av");
$pdf->setY(24);$pdf->setX(3);
$pdf->Cell(7,17,"CAI: 88X4B-B4B2258-C44FB8-E52A7C-4625GTY-Z3");
$pdf->setY(27);$pdf->setX(3);
$pdf->Cell(8,17,"Rango Autorizado: 000-001-01-030001 a 0000001-01-06800000");
$pdf->setY(30);$pdf->setX(3);
$pdf->Cell(9,17,"Fecha limite de emision: 07/03/2017");
//Fin Prueba
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
$pdf->Cell(5,$off+6,"SUBTOTAL:  " );
$pdf->setX(65);
$pdf->Cell(5,$off+6,"$ ".number_format($total,2,".",","),0,0,"R");
$pdf->setX(2);
$pdf->Cell(5,$off+12,"DESCUENTO: " );
$pdf->setX(65);
$pdf->Cell(5,$off+12,"$ ".number_format($total*$sell->discount/100,2,".",","),0,0,"R");


$pdf->setX(2);
$pdf->Cell(5,$off+18,"TOTAL: " );
$pdf->setX(65);
$pdf->Cell(5,$off+18,"$ ".number_format($total - ($total*$sell->discount/100),2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+24,"EFECTIVO: " );
$pdf->setX(65);
$pdf->Cell(5,$off+24,"$ ".number_format($sell->cash,2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+30,"CAMBIO: " );
$pdf->setX(65);
$pdf->Cell(5,$off+30,"$ ".number_format($sell->cash-($total - ($total*$sell->discount/100)),2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+36,'----------------------------------------------------------------------------------');
$pdf->setX(2);
//id de folio podria ser el # de cai yupi!!!!
$pdf->Cell(5,$off+42,"FOLIO: ".$sell->id.' - FECHA: '.$sell->created_at);
$pdf->setX(2);
$pdf->Cell(5,$off+48,'ATENDIDO POR '.strtoupper($user->name." ".$user->lastname));
$pdf->setX(2);
$pdf->Cell(5,$off+54,'GRACIAS POR TU COMPRA, lo esperamos ');


$pdf->output();
