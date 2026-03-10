<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover" id="table-laporan">
    <thead>
        <tr>
            <th rowspan="3" style="line-height: 2; width: 20px; font-size: 1.2rem;">No.</th>
            <th rowspan="3" style="text-align: left; line-height: 2; width: 180px; padding: 5px; font-size: 1.2rem;">Produk</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Januari</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Februari</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Maret</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">April</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Mei</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Juni</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Juli</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Agustus</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">September</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Oktober</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">November</th>
            <th colspan="2" style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1.2rem;">Desember</th>
                        
        </tr>
        <tr>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Target<br>(Container)</th>
            <th style="text-align: center; line-height: 1; width: 30px; padding: 5px; font-size: 1rem;">Hasil<br>(Container)</th>
        </tr>
    </thead>
    <tbody>
        <?php    
        $Merger2 = 0;
        $sqljml = "select target_jenis_produk
                    from t_target_penjualan
                    where 
                    substring(target_periode,0,5)='".$model['periode']."' and type_penjualan='Export'
                    group by 1";
        $modeljml = \Yii::$app->db->createCommand($sqljml)->queryAll();
        if(count($modeljml)>0){
            foreach ($modeljml as $x => $xmod){

            }
            if($x % 2 == 0 ){
                $Merger2 = $x-1 ;
            }else{
                $Merger2 = $x;
            }            
        }
        
        $sql = "select target_jenis_produk from t_target_penjualan
                    where substring(target_periode,0,5)='".$model['periode']."' and type_penjualan='Export'
                    group by 1
                    order by 1 asc";
        $modele = \Yii::$app->db->createCommand($sql)->queryAll();
                    
        if(count($modele)>0){
            foreach($modele as $i => $mod1){
                if($mod1['target_jenis_produk']=='Plywood'){
                    $nama_jenisproduk = "Plywood, Lamineboard, Platform";
                }elseif($mod1['target_jenis_produk']=='Moulding'){
                    $nama_jenisproduk = "Moulding, Decking";
                }else{
                    $nama_jenisproduk = $mod1['target_jenis_produk'];
                }

            ?>
            <tr>
                <td rowspan="<?= $Merger2 ?>" style="line-height: 4; text-align: center; font-size: 1.2rem;vertical-align: middle;"><?= ($i+1) ?></td>
                <td rowspan="<?= $Merger2 ?>" style="line-height: 1; text-align: left; padding-left: 5px; font-size: 1.2rem;vertical-align: middle;"><?= "<b>".$nama_jenisproduk."</b>" ?></td>                
                
            <?php
            
                $sql2 = "select target_penjualan_id,
                            target_jenis_produk,
                            target_jml,
                            target_periode
                            from t_target_penjualan
                            where substring(target_periode,0,5)='".$model['periode']."' and type_penjualan='Export' 
                                   and target_jenis_produk='".$mod1['target_jenis_produk']."'
                            order by substring(target_periode,6,7)::numeric asc";               
                
                $model2 = \Yii::$app->db->createCommand($sql2)->queryAll();
                    
                    $sqlawal = "select target_periode,substring(target_periode,6,7)::numeric as awalbln
                                from t_target_penjualan
                                where substring(target_periode,0,5)='".$model['periode']."' 
                                group by 1
                                order by substring(target_periode,6,7)::numeric asc
                                limit 1";
                    $modelawal = \Yii::$app->db->createCommand($sqlawal)->queryOne();
                    if($modelawal['awalbln'] > 0){
                        $awalbatas = ($modelawal['awalbln']-1) * 2;
                        for ($x = 0; $x < $awalbatas; $x++) {
                            echo"<td style='line-height: 2; text-align: center; font-size: 1.1rem;vertical-align: middle;'>-</td>";
                        }
                    }
                    
                    
                if(count($model2)>0){    
                    foreach($model2 as $ii => $mod){
                        $jml_lamineboard = 0;
                        $jml_platform = 0;
                        $jml_hasil = 0; 
                        $nama_jenisproduk = $mod['target_jenis_produk'];
                        if($mod['target_jenis_produk']=='Plywood'){
                            $sqllamineboard = "select jenis_produk,sum(jmlcontainer_no) as jmllamineboard from view_export_container
                                            where to_char(DATE(peb_tanggal), 'YYYY-MM')='".$mod['target_periode']."' and jenis_produk='Lamineboard' group by jenis_produk";

                            $sqlplatform = "select jenis_produk,sum(jmlcontainer_no) as jmlplatform from view_export_container
                                            where to_char(DATE(peb_tanggal), 'YYYY-MM')='".$mod['target_periode']."' and jenis_produk='Platform' group by jenis_produk";

                            $modlamineboard = \Yii::$app->db->createCommand($sqllamineboard)->queryOne();
                            $modplatform = \Yii::$app->db->createCommand($sqlplatform)->queryOne();

                            $jml_lamineboard = round($modlamineboard['jmllamineboard'],4);
                            $jml_platform = round($modplatform['jmlplatform'],4);                        
                        }

                        $sqlhasil = "select jenis_produk,sum(jmlcontainer_no) as jmlcontainer from view_export_container
                                where to_char(DATE(peb_tanggal), 'YYYY-MM')='".$mod['target_periode']."' and jenis_produk='".$mod['target_jenis_produk']."'
                                group by jenis_produk";
                        
                        $modhasil = \Yii::$app->db->createCommand($sqlhasil)->queryOne();

                        $modhasil = \Yii::$app->db->createCommand($sqlhasil)->queryOne();
                        $jml_hasil = $modhasil['jmlcontainer'] + $jml_lamineboard + $jml_platform ;
                        $prosentase = $jml_hasil/$mod['target_jml'] * 100;
                        if($jml_hasil >= $mod['target_jml']){
                            $classText = "color: green";
                        }else{
                            $classText = "color: red";
                        }

                ?>

                    <td style="line-height: 2; text-align: center; font-size: 1.1rem;vertical-align: middle;"><?= number_format($mod['target_jml']) ?></td>
                    <td style="line-height: 2; text-align: center; font-size: 1.1rem;vertical-align: middle;<?= $classText ?>">
                        <a title="Klick untuk melihat info Pencapaian Export" style="<?= $classText ?>" onclick="info('<?= $mod['target_periode']."/".$modhasil['jenis_produk'] ?>')"><?= number_format($jml_hasil) ?></a>
                    </td>

                    <?php
                        

                    }   

                    $sqlakhir = "select target_periode,substring(target_periode,6,7)::numeric as akhirbln
                                from t_target_penjualan
                                where substring(target_periode,0,5)='".$model['periode']."' 
                                group by 1
                                order by substring(target_periode,6,7)::numeric desc
                                limit 1";
                    $modelakhir = \Yii::$app->db->createCommand($sqlakhir)->queryOne();
                    if($modelakhir['akhirbln'] > 0){
                        $akhirbatas = (12-$modelakhir['akhirbln']) * 2;
                        for ($x = 0; $x < $akhirbatas; $x++) {
                            echo"<td style='line-height: 2; text-align: center; font-size: 1.1rem;vertical-align: middle;'>-</td>";
                        }
                    }
                ?>

        </tr> 
        
        <?php
                
                }
            }
        }
        
        ?>
    </tbody>
</table>
</div>

<script>
function info(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/exim/laporan/infoPenjualan','periode'=>'']) ?>'+id,'modal-master-info','90%'," $('#modal-master-info').dataTable().fnClearTable(); ");
}  
</script>