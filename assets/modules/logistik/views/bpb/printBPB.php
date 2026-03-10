<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$kode = $model->bpb_kode;
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
//echo"<pre>$_GET[caraprint]";
//exit();
?>
<style>
table{
	font-size: 1.2rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<table style="width: 20cm; margin: 10px; height: 1cm;" border="1">
    <tr>
        <td colspan="3" style="padding: 5px;">
            <table style="width: 100%; " border="0">
                <tr style="">
                    <td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 0.5cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
                        <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 60px;"> 	
                    </td>
                    <td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
                        <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
                    </td>
                    <td style="width: 5cm; height: 1cm; vertical-align: top; padding: 7px;">
                        <table>
                            <tr>
                                <td style="width:2cm;"><b>Kode BPB</b></td>
                                <td>: &nbsp; <?= $kode; ?></td>
                            </tr>
                            <tr>
                                <td><b>Tanggal</b></td>
                                <td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->bpb_tgl_keluar ); ?> </td>
                            </tr>
                        </table>
                        </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3" style="padding: 0px;">
            <table style="width: 100%" id="table-detail">
                <tr style="height: 0.5cm; border-bottom: solid 1px #000;">
                    <td style="padding: 2px 5px; width: 1cm; border-right: solid 1px #000; vertical-align: middle;"><b><left>No</left></b></td>
                    <td style="padding: 2px 5px; pwidth: 5.5cm;adding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><left>Nama Item</left></b></td>
                    <td style="width: 2.5cm; border-right: solid 1px #000; vertical-align: middle;"><b><center>Qty</center></b></td>
                    <td style="padding: 2px 5px; width: 8cm; vertical-align: middle; vertical-align: middle;"><b><left>Keterangan</left></b></td>
                </tr>
                <!--record bpb-->
        <?php        
        $qty = 0;
        $row = 0;

        $modBpb = \app\models\TBpbDetail::find()->where("bpb_id = ".$model->bpb_id)->all();
        if(count($modBpb)>0){
            foreach($modBpb as $ii => $bpb_detail){
                $row = $row+1;
                $bhp_id = $bpb_detail['bhp_id'];
                $sql = "select bhp_nm,bhp_satuan from m_brg_bhp where bhp_id = $bhp_id ";
                $result= Yii::$app->db->createCommand($sql)->queryOne();

        ?>        
                <tr>
                    <td style="padding: 2px 5px; border-right: 1px solid black;vertical-align: middle; border-left: solid 1px transparent;"><?= $row ?></td>
                    <td style="padding: 2px 5px; border-right: 1px solid black;vertical-align: middle;"><?= $result['bhp_nm'] ?></td>
                    <td style="padding: 2px 5px; border-right: 1px solid black;vertical-align: middle; align-items: right"><right><?= $bpb_detail['bpbd_jml']." (".$result['bhp_satuan'].")" ?></right></td>                    
                    <td style="padding: 2px 5px; vertical-align: middle;"><?= $bpb_detail['bpbd_ket'] ?></td>
                </tr>
        <?php 
            }
        }
        ?>
            </table>
        </td>
    </tr>
    <!--print by + no doc-->
    <tr>
        <td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
            <?php
            echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
            echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
            ?>
            <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK</span>
        </td>
    </tr>
    <!--footer-->
    <tr style="border: solid 1px transparent; border-top: solid 1px #000;">
        <td colspan="1" style="border-right: solid 1px transparent;">
            <table style="width: 100%; font-size: 1.1rem; text-align: center;">
                <tr style="height: 0.4cm;  ">
                    <td rowspan="3" style="vertical-align: middle; width: 16cm;">&nbsp;</td>
                    <td style="vertical-align: middle; width: 4cm; ">Disetujui,</td>
                </tr>
                <tr>
                    <td style="height: 35px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="height: 20px; vertical-align: middle;">
                        <?php
                        if(!empty($model->bpb_dikeluarkan)){
                                echo "<span style='font-size:0.8rem'>Karu Logistic</span><br>";
                                echo "<span style='font-size:0.9rem'>(SRI AMPERAWATI)</span>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
        <td colspan="1" style="border-right: solid 1px transparent;">
            <table style="width: 100%; font-size: 1.1rem;">
                <tr style="height: 0.4cm;  ">
                    <td rowspan="3" style="vertical-align: middle; width: 16cm;">&nbsp;</td>
                    <td style="vertical-align: middle; width: 7cm; text-align: center; ">Diterima</td>
                </tr>
                <tr>
                    <td style="height: 20px; vertical-align: bottom; padding-left: 7px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="height:20px; vertical-align: middle;">                        
                        <?php
                        if(!empty($model->bpb_dikeluarkan)){
                                echo "<span style='font-size:0.8rem'>Tgl : </span><br>";
                                echo "<span style='font-size:0.8rem'>Dept : </span><br>";
                                echo "<span style='font-size:0.8rem'>Nama : </span>";                                
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>         
        <td colspan="1" style="border-right: solid 1px transparent;">
            <table style="width: 100%; font-size: 1.1rem; text-align: center;">
                <tr style="height: 0.4cm;  ">
                    <td rowspan="3" style="vertical-align: middle; width: 16cm;">&nbsp;</td>
                    <td style="vertical-align: middle; width: 7cm; ">Dikeluarkan,</td>
                </tr>
                <tr>
                    <td style="height: 35px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="height: 20px; vertical-align: middle;">
                        <?php
                        if(!empty($model->bpb_dikeluarkan)){
                                echo "<span style='font-size:0.8rem'>Admin Logistic</span><br>";
                                echo "<span style='font-size:0.9rem'>( ".$model->bpbDikeluarkan->pegawai_nama." )</span>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
        
    </tr>
</table>