<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12" style="align:center;">
                            <?php echo $header; ?>
                            <?php echo "Stock Per Tanggal ".date('d/m/Y')." (Dalam Satuan M<sup>3</sup>) "; ?> 
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        
                        <table class="table table-striped table-bordered table-hover" id="table-laporan">
                        <thead>
                            <tr>
                                <th style="line-height: 2; width: 40px; font-size: 1.2rem;"></th>
                                <th style="text-align: left; line-height: 1; width: 180px; padding: 5px; font-size: 1.2rem;">
                                <?php    
                                    if($model['produk_group'] == 'Plywood' || $model['produk_group'] == 'Platform' || $model['produk_group'] == 'Lamineboard'){
                                        $caption = 'Glue / Grade';
                                        $fieldGrade = 'grade';                    
                                        $fieldGlue  = 'glue';
                                        $sparatorGlue = ',';
                                        $fieldCase = "CASE
                                                            when glue in ('T1F4s','T2F4s') then 'JAS'
                                                            else 'NON JAS'
                                                        END as jas";
                                        $fieldOrder = '2';
                                    }elseif($model['produk_group'] == 'Sawntimber'){
                                        $caption = 'Kondisi Kayu / Grade';
                                        $fieldGrade = 'grade';
                                        $fieldGlue  = 'kondisi_kayu';
                                        $sparatorGlue = '';
                                        $fieldCase = '';
                                        $fieldOrder = '1';
                                    }elseif($model['produk_group'] == 'Moulding'){
                                        $caption = 'Profil_kayu / Grade';
                                        $fieldGrade = 'grade';
                                        $fieldGlue  = 'profil_kayu';
                                        $sparatorGlue = '';
                                        $fieldCase = '';
                                        $fieldOrder = '1';
                                    }elseif($model['produk_group'] == 'Veneer'){
                                        $caption = 'Grade';
                                        $fieldGrade = 'grade';
                                        $fieldGlue  = 'glue';
                                        $sparatorGlue = ',';
                                        $fieldCase = "CASE
                                                            when glue in ('T1F4s','T2F4s') then 'JAS'
                                                            else 'NON JAS'
                                                        END as jas";
                                        $fieldOrder = '1';
                                    }else{
                                        $caption = '';
                                        $fieldGrade = 'grade';
                                        $fieldGlue  = 'glue';
                                        $sparatorGlue = '';
                                        $fieldCase = '';
                                        $fieldOrder = '1';
                                    }

                                    echo"$caption";
                                ?>
                                </th>        
                            <?php
                                $date = date('Y-m-d');
                                $sqlgrade= "SELECT grade FROM h_persediaan_produk
                                            JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
                                            WHERE 
                                                produk_group in ('".$model['produk_group']."') AND tgl_transaksi <= '$date'
                                            GROUP BY 1
                                            HAVING SUM(in_qty_palet-out_qty_palet) > 0
                                            ORDER BY 1 ASC ";
                                $gradejml = \Yii::$app->db->createCommand($sqlgrade)->queryAll();
                                if(count($gradejml)>0){
                                    foreach ($gradejml as $x => $modGrade){
                            ?>       
                                <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;"><?= $modGrade['grade']; ?></th>                      
                            <?php                    
                                    }
                                }        
                            ?>
                               <th tyle="text-align: left; line-height: 1; width: 180px; padding: 5px; font-size: 1.2rem;"></th> 
                            </tr>       
                        </thead>
                        <tbody>
                            <?php    
                    //        echo"<pre>"; print_r($model['produk_group']); echo"</pre>    

                            $sqlglue = "SELECT $fieldGlue $sparatorGlue $fieldCase
                                        FROM h_persediaan_produk
                                        JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
                                        WHERE 
                                            produk_group in ('".$model['produk_group']."') AND tgl_transaksi <= '$date'
                                        GROUP BY 1
                                        HAVING SUM(in_qty_palet-out_qty_palet) > 0 
                                        ORDER BY $fieldOrder ASC";
                            $gluejml = \Yii::$app->db->createCommand($sqlglue)->queryAll();                    
                            if(count($gluejml)>0){
                                foreach($gluejml as $i => $modGlue){ 
                                    if($fieldGlue =='glue'){
                                        if($modGlue['jas'] == 'JAS'){
                                            $jas = "JAS";
                                            $styleJas = 'text-align:left;color:blue;';
                                        }else{
                                            $jas = "";
                                            $styleJas = 'text-align:left;color:#000;';
                                        }
                                    }else{
                                        $jas = "";
                                        $styleJas = 'text-align:left;color:#000;';
                                    }
                                ?>
                            <tr>
                                <td style="line-height: 2; text-align: center; font-size: 1.2rem;vertical-align: middle;"><?= $jas ?></td>
                                <td style="line-height: 2; text-align: left; padding-left: 5px; font-size: 1.2rem;vertical-align: middle;"><?= $modGlue[$fieldGlue] ?></td>                

                                <?php

                                    $sql = "SELECT grade FROM h_persediaan_produk
                                            JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
                                            WHERE 
                                                produk_group in ('".$model['produk_group']."') AND tgl_transaksi <= '$date'
                                            GROUP BY 1
                                            HAVING SUM(in_qty_palet-out_qty_palet) > 0
                                            ORDER BY 1 ASC";               

                                    $sqljml = \Yii::$app->db->createCommand($sql)->queryAll();
                                    if(count($sqljml)>0){
                                        foreach ($sqljml as $xx => $modSql){ 
                                            if($modSql['grade'] == 'D' || $modSql['grade'] == 'D(MK)'){
                                                $bcolor = 'background-color:red;color:#fff;'; 
                                            }else{
                                                $bcolor = 'background-color:green;color:#fff;';
                                            }                
                    //                        echo"<pre>"; print_r($modGlue[$fieldGlue]); echo"</pre> ";

                                            $sqlkubikasi = "SELECT sum(in_qty_m3-out_qty_m3) AS kubikasi FROM h_persediaan_produk
                                                        JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
                                                        WHERE 
                                                        produk_group in ('".$model['produk_group']."') AND tgl_transaksi <= '$date' AND Grade = '$modSql[grade]' AND $fieldGlue = '$modGlue[$fieldGlue]' 
                                                        HAVING SUM(in_qty_palet-out_qty_palet) > 0
                                                        ORDER BY 1 ASC";
                                            $modelkubikasi = \Yii::$app->db->createCommand($sqlkubikasi)->queryAll();
                                            if(count($modelkubikasi)>0){
                                                foreach ($modelkubikasi as $xxy => $modKubikasi){ 
                                                    $nilaiKubikasi = round($modKubikasi['kubikasi'],4);


                                                }
                                            }else{
                                                $nilaiKubikasi = '';
                                            }
                                echo"<td style='line-height: 2; text-align: right; font-size: 1.2rem;vertical-align: middle;"; if(($nilaiKubikasi > 0) ? $backcolor = $bcolor : $backcolor = ''); echo" $backcolor'>$nilaiKubikasi</td>";            
                                        }
                                    }              

                                    ?>

                                <td style="line-height: 2; text-align: right; font-size: 1.1rem;vertical-align: middle;">
                                    <?php
                                    $sqltotalglue = "SELECT sum(in_qty_m3-out_qty_m3) AS kubikasi FROM h_persediaan_produk
                                                    JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
                                                    WHERE 
                                                    produk_group in ('".$model['produk_group']."') AND tgl_transaksi <= '$date' AND $fieldGlue = '$modGlue[$fieldGlue]' 
                                                    HAVING SUM(in_qty_palet-out_qty_palet) > 0
                                                    ORDER BY 1 ASC";
                                    $modeltotalglue = \Yii::$app->db->createCommand($sqltotalglue)->queryOne();
                                    if($modeltotalglue>0){
                    //                    foreach ($modeltotalglue as $xxi => $modTotalglue){ 
                                            $totalglue = round($modeltotalglue['kubikasi'],4);
                    //                    }
                                    }else{
                                        $totalglue = '';
                                    }

                                    ?>
                                    <?= $totalglue ?>
                                </td>
                            </tr> 
                            <?php
                                }
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <?php
                                $sql2 = "SELECT grade FROM h_persediaan_produk
                                        JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
                                        WHERE 
                                            produk_group in ('".$model['produk_group']."') AND tgl_transaksi <= '$date'
                                        GROUP BY 1
                                        HAVING SUM(in_qty_palet-out_qty_palet) > 0
                                        ORDER BY 1 ASC ";      

                                $sql2jml =\Yii::$app->db->createCommand($sql2)->queryAll();
                                if(count($sql2jml)>0){
                                    foreach ($sql2jml as $xxx => $modSql2){ 
                                        if($modSql2['grade'] == 'D' || $modSql2['grade'] == 'D(MK)'){
                                            $bcolor = 'background-color:red;color:#fff;'; 
                                        }else{
                                            $bcolor = 'background-color:green;color:#fff;';
                                        }


                                    $sqltotalgrade = "SELECT sum(in_qty_m3-out_qty_m3) AS kubikasi FROM h_persediaan_produk
                                                    JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id 
                                                    WHERE 
                                                    produk_group in ('".$model['produk_group']."') AND tgl_transaksi <= '$date' AND grade = '$modSql2[grade]' 
                                                    HAVING SUM(in_qty_palet-out_qty_palet) > 0
                                                    ORDER BY 1 ASC";
                                    $modeltotalgrade = \Yii::$app->db->createCommand($sqltotalgrade)->queryOne();
                                    if($modeltotalgrade>0){
                                            $totalgrade = round($modeltotalgrade['kubikasi'],4);
                                    }else{
                                        $totalgrade = '';
                                    }
                            echo"<td style='line-height: 2; text-align: right; font-size: 1.2rem;vertical-align: middle;"; if(($totalgrade > 0) ? $backcolor = $bcolor : $backcolor = ''); echo" $backcolor''>$totalgrade</td>";
                                    }
                                }            
                                ?>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>                          
                         
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>