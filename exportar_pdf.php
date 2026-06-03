<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '256M');
set_time_limit(120);

session_start();


if(!isset($_SESSION['usuario'])){
    exit();
}

error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
ini_set('display_errors', 0);




include 'config.php';
require_once __DIR__ . '/fpdf/fpdf.php';

class PDF extends FPDF {

    function Header()
    {
        // LOGO
        $this->Image(__DIR__.'/img/logo-final.png',10,10,30);

        // EMPRESA
        $this->SetFont('Arial','B',16);
        $this->Cell(0,8,'EDSON BORGES MECANICA DIESEL',0,1,'C');

        // ENDERECO
        $this->SetFont('Arial','',9);
        $this->Cell(0,5,'R. Mal. Deodoro, 475 - Vendaval, Biguacu - SC, 88160-000',0,1,'C');

        $this->Ln(15);

        // CLIENTE
        $this->SetFont('Arial','B',11);
        $this->Cell(0,8,'Cliente: Transportes Coelho',0,1,'L');

        $this->Ln(8);

        // TITULO
        $this->SetFont('Arial','B',11);
        $this->Cell(0,6,'Relatorio de Ordens de Servico',0,1,'C');

        $this->Ln(4);

        // CABECALHO DA TABELA
        $this->SetFont('Arial','B',10);

        $this->Cell(20,8,'Data',1);
        $this->Cell(25,8,'Placa',1);
        $this->Cell(115,8,'Descricao',1);
        $this->Cell(30,8,'Valor',1);

        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

$where = "";

if(!empty($dataInicio) && !empty($dataFim)){
    $where = " WHERE data_servico BETWEEN '$dataInicio' AND '$dataFim'";
}

$sql = "
SELECT *
FROM ordens_servico
$where
ORDER BY data_servico DESC
";

$result = $conn->query($sql);

if(!$result){
    die("Erro SQL: " . $conn->error);
}

if($result->num_rows == 0){
    $pdf->Cell(0,10,'Nenhuma ordem de servico encontrada.',1,1,'C');
    $pdf->Output();
    exit;
}

$total = 0;
$pdf->SetFont('Arial','',9);

$zebra = false;

/* LOOP PRINCIPAL */
while($os = $result->fetch_assoc()){

    $total += $os['valor'];

    $data = date('d/m/Y', strtotime($os['data_servico']));
    $placa = $os['placa'];
    $descricao = $os['descricao'];
    $valor = 'R$ '.number_format($os['valor'],2,',','.');

    $wData = 20;
    $wPlaca = 25;
    $wDesc = 115;
    $wValor = 30;

    $lineHeight = 5;

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // altura da linha baseada na descrição
    $nbLines = ceil($pdf->GetStringWidth($descricao) / $wDesc * 2.2);
    $h = max(10, $nbLines * $lineHeight);

    // ZEBRA
    if($zebra){
        $pdf->SetFillColor(240,240,240);
    } else {
        $pdf->SetFillColor(255,255,255);
    }

    // FUNDO DA LINHA
    $pdf->Rect($x, $y, $wData + $wPlaca + $wDesc + $wValor, $h, 'F');

    // BORDA EXTERNA
    $pdf->Rect($x, $y, $wData + $wPlaca + $wDesc + $wValor, $h);

    // LINHAS VERTICAIS
    $pdf->Line($x + $wData, $y, $x + $wData, $y + $h);
    $pdf->Line($x + $wData + $wPlaca, $y, $x + $wData + $wPlaca, $y + $h);
    $pdf->Line($x + $wData + $wPlaca + $wDesc, $y, $x + $wData + $wPlaca + $wDesc, $y + $h);

    // DATA
    $pdf->SetXY($x, $y);
    $pdf->Cell($wData, $h, $data, 0, 0, 'L');

    // PLACA
    $pdf->Cell($wPlaca, $h, $placa, 0, 0, 'L');

    // DESCRICAO
    $xDesc = $pdf->GetX();
    $yDesc = $pdf->GetY();

    $pdf->MultiCell($wDesc, $lineHeight, $descricao, 0, 'L');

    $pdf->SetXY($xDesc + $wDesc, $yDesc);

    // VALOR
    $pdf->Cell($wValor, $h, $valor, 0, 0, 'L');

    $pdf->Ln($h);

    $zebra = !$zebra;
}

/* TOTAL */
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(
    0,
    10,
    'Total faturado: R$ '.number_format($total,2,',','.'),
    0,
    1
);

/* ASSINATURA */
$pdf->Ln(15);

$pdf->SetFont('Arial','',10);
$pdf->Cell(60,10,'',0,0);
$pdf->Cell(70,10,'_____________________________',0,1,'C');

$pdf->Cell(0,5,'Assinatura do Responsavel',0,1,'C');

$pdf->Output('D','Relatorio_Ordens_Servico.pdf');