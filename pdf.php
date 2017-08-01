<?php
	session_start();
	include('pdf/class_pdf.php');
	
	$arrUsu=$_SESSION['comentarios'];

	$border=0;

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->title = 'Comentaarios de '.$arrUsu[0]['nombre'];

    for ($x=0; $x<count($arrUsu); $x++){
        $pdf->Cell(105,10,utf8_decode($arrUsu[$x]['comment']),$border,1,'L');
    }

    $pdf->Output();
?>