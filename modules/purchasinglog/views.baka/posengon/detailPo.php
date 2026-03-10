<?php 
app\assets\DatatableAsset::register($this);
$kode = $model->kode;
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
$blankspace = 2;
$panjangs = \yii\helpers\Json::decode($model->panjang);
$panjang = "";
foreach($panjangs as $i => $pan){
    $panjang .= $pan." cm";
    $panjang .= ( count($panjangs)!=($i+1) )?" dan ":"";
}
$kuotas = \yii\helpers\Json::decode($model->kuota);
$kuota = ""; $i = 0;
foreach($kuotas as $j => $kut){
    $kuota .= $j.' cm = '. number_format($kut)." m<sup>3</sup>";
    $kuota .= ( count($kuotas)!=($i+1) )?" dan ":"";
    $i++;
}
$diameterharga = \yii\helpers\Json::decode($model->diameter_harga);
$maxrow = 0;
foreach($diameterharga as $i => $asdasd){
    if( count($asdasd) > $maxrow){
        $maxrow = count($asdasd);
    }
}
$spesifikasi = \yii\helpers\Json::decode( $model->spesifikasi_log );
?>
<style>
table{
	font-size: 1.1rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<div class="modal fade" id="modal-detailpo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">PURCHASE ORDER DETAILS</h4>
            </div>
            <div class="modal-body">
                <table style="width: 20cm; margin: 10px;" border="1">
                    <tr>
                        <td colspan="3" style="padding: 5px;">
                            <table style="width: 100%; " border="0">
                                <tr style="">
                                    <td style="width: 3cm; text-align: center; vertical-align: middle; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
                                        <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
                                    </td>
                                    <td style="width: 8cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
                                        <span style="font-size: 1.3rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
                                        <span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom: solid 1px transparent;">
                            <table style="width: 100%;" border="0">
                                <tr style="">
                                    <td style="width: 5cm; text-align: left; vertical-align: middle;border-right: solid 1px transparent;"></td>
                                    <td style="text-align: center; vertical-align: top; padding: 5px; line-height: 1;">
                                        <span style="font-size: 1.6rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
                                    </td>
                                    <td style="width: 5cm; vertical-align: top;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 5px; background-color: #F1F4F7;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 100%; height: auto; vertical-align: top; padding-left: 5px;">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 1.5cm; vertical-align: top;"><b>Nomor</b></td>
                                                <td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
                                                <td style="vertical-align: top;"><?= $model->kode ?></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;"><b>Tanggal</b></td>
                                                <td style="vertical-align: top; text-align: center;"><b>:</b></td>
                                                <td style="vertical-align: top;"><?= app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal) ?></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;"><b>Kepada</b></td>
                                                <td style="vertical-align: top; text-align: center;"><b>:</b></td>
                                                <td style="vertical-align: top;"><b><?= $model->suplier->suplier_nm ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;"><b>Alamat</b></td>
                                                <td style="vertical-align: top; text-align: center;"><b>:</b></td>
                                                <td style="vertical-align: top; line-height: 1.2;"><?= $model->suplier->suplier_almt ?></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;"><b>Telepon</b></td>
                                                <td style="vertical-align: top; text-align: center;"><b>:</b></td>
                                                <td style="vertical-align: top; line-height: 1.2;"><?= $model->suplier->suplier_phone ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 5px; background-color: #F1F4F7; border-top: solid 1px transparent;">
                            Dengan Hormat, <br> Berikut disampaikan pemesanan barang dengan spesifikasi tersebut dibawah ini :
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 0.5cm; height: auto; vertical-align: top; padding-left: 5px;">
                                    </td>
                                    <td style="height: auto; vertical-align: top; padding-left: 5px;">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 2.5cm; vertical-align: top;">Nama Barang</td>
                                                <td style="width: 0.5cm; vertical-align: top; text-align: center;">:</td>
                                                <td style="vertical-align: top;"><?= $model->nama_barang ?></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">Panjang Log</td>
                                                <td style="vertical-align: top; text-align: center;">:</td>
                                                <td style="vertical-align: top;"><?= $panjang." (No Minus)" ?></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">Kuota</td>
                                                <td style="vertical-align: top; text-align: center;">:</td>
                                                <td style="vertical-align: top;"><?= $kuota ?></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">Diameter & Harga</td>
                                                <td style="vertical-align: top; text-align: center;">:</td>
                                                <td style="vertical-align: top;"</td>
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
                                <tr style="border-bottom: solid 1px #000;">
                                    <?php foreach($diameterharga as $i => $diaharga){ ?>
                                        <th colspan="2" style="padding: 3px; <?= ( count($diameterharga)!=($i+1)?"border-right: solid 1px #000;":"" ) ?>" class="text-align-center">
                                            <?= "Panjang ".$diaharga[0]['panjang']."cm (". ucfirst(str_replace("_", " ", $diaharga[0]['wilayah'])).")" ?>
                                        </th>
                                    <?php } ?>
                                </tr>
                                <tr style="border-bottom: solid 1px #000;">
                                    <?php foreach($diameterharga as $i => $diaharga){ ?>
                                        <th style="padding: 3px; width: <?= (100/Count($diameterharga))/2 ?>%; border-right: solid 1px #000;" class="text-align-center">Diameter</th>
                                        <th style="padding: 3px; width: <?= (100/Count($diameterharga))/2 ?>%; <?= ( count($diameterharga)!=($i+1)?"border-right: solid 1px #000;":"" ) ?>" class="text-align-center">Harga</th>
                                    <?php } ?>
                                </tr>
                                <?php for($jj=0; $jj<$maxrow; $jj++){ ?>
                                <tr>
                                    <td colspan="<?= (count($diameterharga)*2) ?>">
                                        <table style="width: 100%">
                                            <tr style="border-bottom: solid 1px #000;">
                                                <?php foreach($diameterharga as $i => $diaharga){ ?>
                                                    <td style="padding: 1px; width: <?= (100/Count($diameterharga))/2 ?>%; border-right: solid 1px #000;" class="text-align-center">
                                                        <?php echo isset($diaharga[($jj)])? $diaharga[($jj)]['diameter_awal']." - ".$diaharga[($jj)]['diameter_akhir']." cm":""; ?>
                                                    </td>
                                                    <td style="padding: 1px; width: <?= (100/Count($diameterharga))/2 ?>%; <?= ( count($diameterharga)!=($i+1)?"border-right: solid 1px #000;":"" ) ?>" class="text-align-right">
                                                        <?php
                                                        if( Yii::$app->user->identity->pegawai->departement_id == app\components\Params::DEPARTEMENT_ID_PCH || Yii::$app->user->identity->pegawai->departement_id == app\components\Params::DEPARTEMENT_ID_FIN || Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER ){
                                                            $harga_akhir = isset($diaharga[($jj)])? "Rp. &nbsp;".number_format($diaharga[($jj)]['harga']):"";
                                                        }else{
                                                            $harga_akhir = "##########";
                                                        }
                                                        ?>
                                                        <?= $harga_akhir; ?>
                                                    &nbsp; </td>
                                                <?php } ?>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                    <tr style="border-bottom: solid 1px transparent;">
                        <td colspan="3" style="padding: 5px; background-color: #F1F4F7; border-top: solid 1px transparent;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 0.5cm; height: auto; vertical-align: top; padding-left: 5px;">
                                    </td>
                                    <td style="height: auto; vertical-align: top; padding-left: 5px;">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 2.5cm; vertical-align: top;">Waktu Pengiriman</td>
                                                <td style="width: 0.5cm; vertical-align: top; text-align: center;">:</td>
                                                <td style="vertical-align: top;"><b><u><?= app\components\DeltaFormatter::formatDateTimeForUser($modRencana->tanggal_pengiriman_awal)."</u> sd <u>".app\components\DeltaFormatter::formatDateTimeForUser($modRencana->tanggal_pengiriman_akhir) ?></u></b></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">Cara Pembayaran</td>
                                                <td style="vertical-align: top; text-align: center;">:</td>
                                                <td style="vertical-align: top;"><?= $model->cara_bayar ?></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">No Rekening</td>
                                                <td style="vertical-align: top; text-align: center;">:</td>
                                                <td style="vertical-align: top;"><?= $model->rekening_bank ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="vertical-align: top;"><b>&nbsp; &nbsp; &nbsp; &nbsp; Spesifikasi Log</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 0.5cm; height: auto; vertical-align: top; padding-left: 5px;"></td>
                                    <td style="height: auto; vertical-align: top; padding-left: 5px;">
                                        <table style="width: 100%;">
                                            <?php foreach($spesifikasi as $i => $spek){ 
                                                if(!is_numeric($i)){ ?>
                                                <tr>
                                                    <td style="width: 4cm; vertical-align: top;"><?= $i ?></td>
                                                    <td style="width: 0.5cm; vertical-align: top; text-align: center;">:</td>
                                                    <td style="vertical-align: top;"><?= $spesifikasi[$i]; ?></td>
                                                </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <tr><td style="">&nbsp;</td></tr>
                                            <?php foreach($spesifikasi as $i => $spek){ 
                                                if(is_numeric($i)){ ?>
                                                <tr>
                                                    <td colspan="3" style="vertical-align: top;"><?= $spek ?>.</td>
                                                </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </table>
                                    </td>
                                </tr>                                
                            </table>
                        </td>
                    </tr>
                    <tr style="border-bottom: solid 1px transparent;">
                        <td colspan="3" style="padding: 5px;">
                            <table style="width: 100%;">
                                <tr style="height: 1cm;  ">
                                    <td style="vertical-align: bottom; width: 1cm; border-bottom: solid 1px transparent; text-align: left;"></td>
                                    <td style="vertical-align: bottom; width: 4cm; text-align: center;">Disetujui,</td>
                                    <td style="vertical-align: bottom;border-bottom: solid 1px transparent;"></td>
                                    <td style="vertical-align: bottom; width: 4cm; text-align: center;">Hormat Kami,</td>
                                    <td style="vertical-align: bottom; width: 1cm; border-bottom: solid 1px transparent; text-align: left;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 2cm; "></td>
                                    <td style="vertical-align: bottom; line-height: 1;  text-align: center;">
                                        <?php
                                            echo "<span style='font-size:0.9rem'><b><u> ". $model->suplier->suplier_nm." </u></b></span><br>";
                                            echo "<span style='font-size:0.8rem'>Supplier ".$model->nama_barang."</span>";
                                        ?>
                                    </td>
                                    <td style=""></td>
                                    <td style="vertical-align: bottom; line-height: 1;  text-align: center;">
                                        <?php
                                            echo "<span style='font-size:0.9rem'><b><u> ". app\models\MPegawai::findOne($modRencana->menyetujui)->pegawai_nama." </u></b></span><br>";
                                            echo "<span style='font-size:0.8rem'>Kadept. Purchasing BP </span>";
                                        ?>
                                    </td>
                                    <td style=""></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="border-bottom: solid 1px transparent;"><td style="height: <?= (0.5*$blankspace) ?>cm">&nbsp;</td></tr>
                    <tr style="border-bottom: solid 1px transparent;">
                        <td colspan="3" style="padding: 1px;"><i>
                            ** Pengiriman tidak boleh melibihi jumlah kuota yang sudah ditetapkan (Toleransi kelebihan maksimal 5% dari banyaknya kuota) <br>
                            ** Jika ada rencana pengiriman yang lebih dari kebijakan yang diberikan, harap konfirmnasi terlebih dahulu ke pihak <?= $modCompany->name ?>
                        </i></td>
                    </tr>
                    <tr style="border-bottom: solid 1px transparent;">
                        <td colspan="3" style="padding: 1px;"><i></i></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; height: 1.5cm;" colspan="2">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="vertical-align: bottom; font-size: 0.9rem; padding:3px;">
                                        <?php
                                        echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
                                        echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
                                        ?>
                                    </td>
                                </tr>
                                <tr>                                    
                                    <td style="text-align: center; padding:3px;">
                                        <?php echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 6cm;">'; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer text-align-center">
                <a class="btn btn-outline blue btn-md" onclick="printPo(<?= $model->posengon_id ?>)"><i class="fa fa-print"></i> Print</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>