<?php
ob_start('ob_gzhandler');
ob_start();
set_time_limit(0);
error_reporting(E_ALL | E_STRICT);

include "inc.connection.php";
require __DIR__ . '/vendor/autoload.php';

// ini_set('zlib.output_compression_level', 1);
ini_set('memory_limit', '-1');
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
ini_set('post_max_size', '64M');
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);


$files_zip = glob('temp_pdf/*');
foreach ($files_zip as $file_zip) {
    if (is_file($file_zip)) {
        unlink($file_zip);
    }
}

use Knp\Snappy\Pdf;

$snappy = new Pdf;
$snappy->setBinary(__DIR__.'/vendor/wkhtmltopdf/bin/wkhtmltopdf.exe');
// $snappy = new Pdf;
// $snappy->setBinary('C://"Program Files"/wkhtmltopdf/bin/wkhtmltopdf.exe');

// header('Content-Type: application/pdf');
// echo $snappy->getOutput('http://192.168.35.160/pma_dev/portal/redpinereport/library/export/byDepo/export/export_promo_tpr_automaticaly_pdf.php', array(
//     'no-stop-slow-scripts' => true, 
//     'lowquality' => true,
//     'page-height' => 420,
//     ));
// die();

$time_start = microtime(true); 
$reg     = '3';
$periode = '10';
$tahun   = '2018';

$query_findgfac = "SELECT a.gfac_id, c.NM_REG, c.KD_REG
                    FROM rgfac a
                    LEFT JOIN rdepo b ON a.id_sap = b.kd_sap2
                    LEFT JOIN rmstreg c ON b.KD_REG = c.KD_REG
                    WHERE b.KD_REG = '$reg'
                    GROUP BY a.gfac_id";

$exe_query_findgfac = mysqli_query($conn, $query_findgfac);
while($datagfacbyreg = mysqli_fetch_array($exe_query_findgfac)){
    $gfacbyreg          = $datagfacbyreg['gfac_id'];
    $nmreg              = $datagfacbyreg['NM_REG'];
    for ($i = 10; $i <= 10; $i++) {
        $zip = new ZipArchive();
        $date = $tahun . $periode;
        if ($zip->open("zip_pdf/DETAIL_CLAIM_".$periode."_".$tahun."_".$nmreg."_PDF.zip", ZIPARCHIVE::CREATE)) {
            $mergePerThn = $tahun."-".$periode;
            mysqli_query($conn, "TRUNCATE TABLE temp_tprdetail_list_gfac");
            $depoOfGfac = mysqli_query($conn, " INSERT INTO temp_tprdetail_list_gfac
                                            SELECT a.tprkode, a.depo, b.NM_DEPO, c.gfac_id
                                            FROM (
                                                SELECT tprkode, depo
                                                FROM rpromo_tpr{$tahun}{$periode}
                                                WHERE depo in (SELECT KD_DEPO FROM rdepo WHERE KD_REG = '$reg')
                                                GROUP BY tprkode, depo 
                                                )a
                                            INNER JOIN rdepo b ON a.depo = b.KD_DEPO
                                            INNER JOIN rgfac c ON c.id_sap = b.kd_sap2
                                            WHERE b.KD_REG = '$reg'
                                            GROUP BY a.tprkode, a.depo");
            
            $gfac          = $gfacbyreg;
            $qrgfac        = "SELECT * FROM temp_tprdetail_list_gfac WHERE gfac_id = '$gfac' GROUP BY tprkode,gfac_id";
            $getAllTprData = mysqli_query($conn, $qrgfac);
            while ($rowtpr = mysqli_fetch_array($getAllTprData)) {
                $rawTpr1            = $rowtpr['tprkode'];
                $changeStripePromo1 = str_replace("/", '_', $rawTpr1);
                $newStringPromo     = str_replace("-", '_', $changeStripePromo1);
                if ($newStringPromo == '*') {
                    $newStringPromo = 'x';
                }
                $promoTpr = $rowtpr['tprkode'];
                $zip->addEmptyDir($newStringPromo);
                $getEveryTprData = mysqli_query($conn, "SELECT tprkode, depo
                                                FROM temp_tprdetail_list_gfac
                                                WHERE gfac_id = '$gfac' AND tprkode ='$promoTpr'");

                while($rowtprbydepo = mysqli_fetch_array($getEveryTprData)){
                    $promo = $rowtprbydepo['tprkode'];
                    $depo  = $rowtprbydepo['depo'];
                    header('Content-Type: application/pdf');
                    // $output = $snappy->getOutput("http://localhost/test/content.php?depo=".$depo."&promo=".$promo."&tgldari=".$mergePerThn."&timeStart=".$time_start, array(
                    //     'lowquality' => true,
                    //     'page-height' => 420,
                    //     'page-width'  => 300,
                    //     'page-size' => 'A4',
                    //     ));
                    $output = $snappy->getOutput("http://192.168.35.160/pma_dev/portal/redpinereport/library/export/byDepo/export/export_promo_tpr_automaticaly_pdf.php?depo=".$depo."&promo=".$promo."&tgldari=".$mergePerThn."&timeStart=".$time_start, array(
                        'lowquality' => true,
                        'page-height' => 420,
                        'page-width'  => 300,
                        'page-size' => 'A4',
                        ));
                    $promoDetail = "temp_pdf/". "/" . $nmreg . "_" . $depo . "_" . $newStringPromo."_PDF.pdf";
                    file_put_contents($promoDetail, $output);
                    $zip->addFile($promoDetail, $newStringPromo . "/" . $nmreg . "_" . $depo . "_" . $newStringPromo."_PDF.pdf");
                }
            }
        }
        $zip->close();
    }
}

$files = glob('temp_pdf/*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

$zipName = "zip_pdf/DETAIL_CLAIM_".$periode."_".$tahun."_".$nmreg."_PDF.zip";
header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header("Content-Disposition: attachment; filename=\"" . basename($zipName) . "\";");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($zipName));
set_time_limit(0);
// large files
$file = @fopen($zipName, "rb");
while(!feof($file)) {
  print(@fread($file, 1024*8));
  ob_flush();
  flush();
}
$files_zip = glob('zip_pdf/*');
foreach ($files_zip as $file_zip) { // iterate files
    if (is_file($file_zip)) {
        unlink($file_zip);
    }
}
exit;