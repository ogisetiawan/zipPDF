<?php
include "inc.connection.php";
ob_start('ob_gzhandler');
ob_start();
set_time_limit(0);
ini_set('memory_limit', '-1');
    @$tgldari      = $_GET['tgldari'];
    @$tglsampai    = $_GET['tgldari'];
    @$promo        = $_GET['promo'];
    @$depo         = $_GET['depo'];
    @$time         = $_GET['timeStart'];
    @$time         = microtime(true);
    @$maxpromo     = count($promo);
    @$maxdepo      = count($depo);

    @$listpromo    = "";
    @$listdepo     = "";
    @$tpr_total    = 0;
    @$amount_total = 0;
    @$crt_total    = 0;
    @$box_total    = 0;
    @$pcs_total    = 0;
    
    $listpromo = $promo;
    $listdepo  = $depo;
    
    $qrytglklaima = ""; 
    $qrytglklaimb = ""; 
    $qrypromo     = "";
    $qrydepo      = "";
    if ($tgldari != "") {
        $qrytglklaima = " AND date_format(a.invdate, '%Y%m') >= '".date("Ym",strtotime($tgldari))."'";
    }
    if ($tglsampai != "") {
        // $qrytglklaimb = " AND date_format(a.invdate, '%Y/%m/%d') <= '".date("Y/m/d",strtotime($tglsampai))."'";
         $qrytglklaimb = " AND date_format(a.invdate, '%Y/%m') <= '".date("Y/m",strtotime($tglsampai))."'";
    }
    if($listpromo != ""){
        $qrypromo = " AND a.tprkode IN ('$listpromo') ";
    }
    if($listdepo != ""){
        // $qrydepo = " AND a.depo IN (SELECT KD_DEPO FROM rdepo WHERE KD_REG = '".$reg."')";
        $qrydepo = " AND a.depo IN ('$listdepo') ";
    }

    /*sql data claim*/
    $tahun = date('Y',strtotime($tgldari));
    $bulan = date('m',strtotime($tgldari));
    $month = $tahun.$bulan;
    
   $qryclaim = "SELECT tprkode, ket, invno, invdate, custno, custname, pctpr, pcodename, jml_tpr, amount, NM_DEPO
            FROM rpromo_tpr{$month} a
            LEFT JOIN rdepo b
            ON a.depo = b.KD_DEPO
            WHERE 1=1 ".$qrytglklaima.$qrytglklaimb.$qrypromo.$qrydepo." GROUP BY tprkode,invno";
    $sqlclaim      = mysqli_query($conn, $qryclaim) or die(mysqli_error());
    $depo          = mysqli_query($conn, $qryclaim) or die(mysqli_error());
    $rowFetchArray = mysqli_fetch_array($depo);
    $nama_depo     = $rowFetchArray['NM_DEPO'];
?>
<!--  -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
</head>
<body>
    <table height="25">
        <tr>
            <td colspan="5"><h2><b>PT. PINUS MERAH ABADI</b></h2></td>
            <td colspan="8"><h2><center><b>LAPORAN PROMO UANG DETAIL </b></center></h2></td>
        </tr>
        <tr>
            <td height="5px;"><br></td>
        </tr>
        <tr>
            <td bgcolor="#b3e0ff" colspan="2"><center>FILTER DATA</center></td>
            <td></td>
             <td bgcolor="#b3e0ff" colspan="2"><center>FILTER DEPO dan PROMO</center></td>
        </tr>
        <tr>
            <td bgcolor="#e6e6e6" >From</td>
            
            <td bgcolor="#e6e6e6" align="left">: <?php echo $tgldari==''?'-':$tgldari; ?></td>
            <td></td>
            
            <!-- <td bgcolor="#e6e6e6"align="top">Depo</td> -->
            <td bgcolor="#e6e6e6"align="top">Depo</td>
            
            <td bgcolor="#e6e6e6" align="left">: <?php echo $nama_depo; ?></td>
        </tr>
        <tr>
            <td bgcolor="#e6e6e6" >To</td>
            
            <td bgcolor="#e6e6e6" align="left">: <?php echo $tglsampai==''?'-':$tglsampai; ?></td>
            <td></td>
            
            <td bgcolor="#e6e6e6" valign="top">Promo</td>
            
            <td bgcolor="#e6e6e6" align="left">: <?php echo $listpromo; ?></td>
        </tr>
        
        
        <tr>
            <td height="5px;"><br></td>
        </tr>
        <tr>
            <td height="10px;"><br></td>
        </tr>
    </table>
    <table border="1" style="border-color: white;">
        <tr style="color: #FFFFFF; background-color: #004d99">
            <th><center>TPRKODE</center></font></th>
            <th><center>KETERANGAN</center></font></th>
            <th><center>INVNO</center></font></th>
            <th><center>INVDATE</center></font></th>
            <th><center>CUSTNO</center></font></th>
            <th><center>CUSTNAME</center></font></th>
            <th><center>PCTPR</center></font></th>
            <th><center>NAMA PROMO</center></font></th>
            <th><center>DISC</center></font></th>

            <th><center> NILAI TPR RBP</center></font></th>
            <th><center>NILAI TPR DBP</center></font></th>
            
            <!-- <th><center>NILAI TPR</center></font></th> -->
            <th><center>PRINC</center></font></th>
            <th><center>PRINC NAME</center></font></th>
            <th><center>PCODE</center></font></th>
            <th><center>NAMA BARANG</center></font></th>
            <th><center>KTN</center></font></th>
            <th><center>BOX</center></font></th>
            <th><center>PCS</center></font></th>
            <th><center>JUMLAH RP</center></font></th>
            <th><center>KD DEPO</center></font></th>
            <th><center>DEPO</center></font></th>
        </tr>
        <?php 
            while ($rsc = mysqli_fetch_array($sqlclaim)) {
                $inv = $rsc['invno'];
                $tpr = $rsc['tprkode'];
                $lambe = mysqli_query($conn, "SELECT * from rpromo_tpr{$month} a
                                        LEFT JOIN rdepo b
                                        on a.depo = b.kd_depo
                                        LEFT JOIN rmaster c on a.pctpr = c.PCODE
                                        LEFT JOIN rprinciple d on c.PRINC = d.princ_id
                                        where a.invno = '$inv' AND a.tprkode='$tpr'".$qrytglklaima.$qrytglklaimb.$qrydepo)or die(mysqli_error());
                $disc = 0;
                $qer = mysqli_query($conn, "SELECT * from rpromo_tpr{$month} a where a.invno = '$inv' AND a.tprkode='$tpr'".$qrytglklaima.$qrytglklaimb);
                while($mic = mysqli_fetch_array($qer)){
                    $amount =  $mic['amount'];
                    $disc = $disc + $amount;
                }
                $jmlhtpr = $rsc['jml_tpr'];
                $discount = $jmlhtpr/($disc/100);
        ?>
        <tr style="background-color: #e6e6e6;">
            <td align="left"><?php echo $rsc['tprkode']==null?'-':$rsc['tprkode']; ?></td>
            <td align="left"><?php echo $rsc['ket']==null?'-':$rsc['ket']; ?></td>
            <td align="left"><?php echo $rsc['invno']==null?'-':$rsc['invno']; ?></td>
            <td align="left"><?php echo $rsc['invdate']==null?'-':$rsc['invdate']; ?></td>
            <td align="left"><?php echo $rsc['custno']==null?'-':$rsc['custno']; ?></td>
            <td align="left"><?php echo $rsc['custname']==null?'-':$rsc['custname']; ?></td>
            <td align="left"><?php echo $rsc['pctpr']==null?'-':$rsc['pctpr']; ?></td>
            <td align="left"><?php echo $rsc['pcodename']==null?'-':$rsc['pcodename']; ?></td>
            <td align="left"><?php echo round($discount,2)." %"; ?></td>

            <!-- <td align="left"><?php //echo $rsc['jml_tpr']==null?'-':$rsc['jml_tpr']; ?></td> -->
            <td align="left"><?php echo $rsc['jml_tpr']==null?'-':$rsc['jml_tpr']; $tpr_total += $rsc['jml_tpr'];?></td>
            <td align="left"><?php echo $rsc['amount']==null?'-':$rsc['amount']; ?></td>

            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php
            while ($rsce = mysqli_fetch_array($lambe)) {
        ?>
        <tr style="background-color: #e6e6e6;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="left"><?php echo $rsce['princ_id']==null?'-':$rsce['princ_id']; ?></td>
            <td align="left"><?php echo $rsce['princ_name']==null?'-':$rsce['princ_name']; ?></td>
            <td align="left"><?php echo $rsce['pcsec']==null?'-':$rsce['pcsec']; ?></td>
            <td align="left"><?php echo $rsce['pcodename_1']==null?'-':$rsce['pcodename_1']; ?></td>
            <td align="left"><?php echo $rsce['krt_utuh']==null?'-':$rsce['krt_utuh']; $crt_total += $rsce['krt_utuh'];?></td>
            <td align="left"><?php echo $rsce['totbox']==null?'-':$rsce['totbox']; $box_total += $rsce['totbox'];?></td>
            <td align="left"><?php echo $rsce['pcs']==null?'-':$rsce['pcs']; $pcs_total += $rsce['pcs']; ?></td>
            <td align="left"><?php echo $rsce['amount']==null?'-':$rsce['amount']; $amount_total += $rsce['amount'];?></td>
            <td align="left"><?php echo $rsce['NM_DEPO']==null?'-':$rsce['KD_DEPO']; ?></td>
            <td align="left"><?php echo $rsce['NM_DEPO']==null?'-':$rsce['NM_DEPO']; ?></td>
        </tr>
        <?php }} ?>
        <tr style="background-color: #e6e6e6;">
            <td align="right" colspan="9"><b>Total Nilai TPR</b></td>
            <td><?php echo $tpr_total; ?></td>
            <td align="right"></td>
            <td align="right"></td>
            <td align="right"></td>
            <td align="right"></td>
            <td align="right"></td>
            
            <td align="right"><?php echo $crt_total;?></td>
            <td align="right"><?php echo $box_total;?></td>
            <td align="right"><?php echo $pcs_total;?></td>
            <td align="left"><?php echo $amount_total;?></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <br><br>
    <?php 
        $tS = microtime(true);
        $tElapsedSecs = $tS - $time;
        $sElapsedSecs = str_pad(number_format($tElapsedSecs, 3), 10, " ", STR_PAD_LEFT);
    ?>
    <p style="text-align: right; size: 8px;"><?php echo "Excute time render page:";?> <strong> <?php echo $sElapsedSecs;?></strong> <?php echo "s"; ?></p>
</body>
</html>