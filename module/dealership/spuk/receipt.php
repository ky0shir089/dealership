<?php
require('../../../plugins/fpdf181/fpdf.php');
include '../../../conn2.php';
session_start();

$spuk_id = $_REQUEST['spuk_id'];
$spuk_jml_unit = $_REQUEST['spuk_jml_unit'];
$spuk_subtotal = $_REQUEST['spuk_subtotal'];
$scheme_amount = $_REQUEST['scheme_amount'];
$supl_name = $_REQUEST['supl_name'];

$query = "select
			pv_paid_date
		from fin_trn_payment
		where pv_proses_id='$spuk_id'";
$result = mysqli_query($con,$query);
$data = mysqli_fetch_array($result);
$pv_paid_date = $data['pv_paid_date'];

$query2 = "select
			margin_amount
		from mst_margin
		where margin_id='M2'";
$result2 = mysqli_query($con,$query2);
$data2 = mysqli_fetch_array($result2);
$margin = $scheme_amount-$data2['margin_amount'];
$biaya_administrasi = $spuk_jml_unit*$margin;
$pajak = $biaya_administrasi/1.1;
$ppn = $pajak*0.1;

class PDF extends FPDF
{
	// Page header
	function Header()
	{
		// Logo
		$this->Image('../../../images/logo.png',10,6,25,25);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(30,10,'E-RECEIPT',0,0,'C');
		// Line break
		$this->Ln();
		
		$this->SetFont('Arial','B',13);
		$this->Cell(80);
		$this->Cell(30,3,'PEMBELIAN MOTOR BEKAS DI RAHARJA MOTOR',0,0,'C');
		$this->Ln(20);
	}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,'Tanggal : '.$pv_paid_date);
$pdf->Ln();
$pdf->Cell(0,5,'Terimakasih telah melakukan transaksi pembelian atas :');
$pdf->Ln();
$pdf->Cell(0,5,'Jumlah  : '.$spuk_jml_unit.' Unit');
$pdf->Ln();
$pdf->Cell(0,5,'Nominal  : '.format_rupiah($spuk_subtotal));
$pdf->Ln();
$pdf->Cell(0,5,'Biaya administrasi : '.format_rupiah($biaya_administrasi));
$pdf->Ln();
$pdf->Cell(0,5,'Pajak : '.format_rupiah($ppn));
$pdf->Ln(15);
$pdf->Cell(0,5,'Diterbitkan oleh : Raharja Motor Cabang '.$_SESSION['out_name']);
$pdf->Ln();
$pdf->Cell(0,5,'Diterbitkan untuk : '.$supl_name);
$pdf->Ln();
$pdf->Output();
?>