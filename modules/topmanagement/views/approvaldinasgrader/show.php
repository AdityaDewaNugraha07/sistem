<?php
$model = \app\models\TApproval::findOne($approval_id);
$modDkg = \app\models\TDkg::find()->where(['kode'=>$model->reff_no])->one();

app\assets\DatepickerAsset::register($this);
?>

<style>
table tr td{
	padding: 3px;
	border: solid 3px #fff;
}
</style>

            <div class="modal-body">
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-5">
						<table style="width: 100%; background-color: #d5e1f3; color: #083881; border-bottom-color: #FFF">
							<tr>
								<td style="width: 30%">Kode Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->kode ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Tipe Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->tipe ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Tanggal Mulai</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modDkg->tanggal) ?></td>
							</tr>
                            <tr>
                                <td style="width: 30%">Jenis Log</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%">
                                    <?php
                                    if ($modDkg->jenis_log == "LA") {
                                        echo "Log Alam";
                                    } else if ($modDkg->jenis_log == "LS") {
                                        echo "Log Sengon";
                                    } else {
                                        echo "Log Jabon";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                            <?php
                            $modMap = \app\models\MapDkgPmrPengajuanPembelianlog::find()->where(['dkg_id'=>$modDkg->dkg_id])->all();
                            if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "ORIENTASI") {
                            ?>
                                <td style="width: 30%">Kode Permintaan</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 65%">
                                <?php
                                $pmr_id = "";
                                foreach ($modMap as $kolom => $value) {
                                ?>
                                    <?php
                                    $sql_kode = "select kode from t_pmr where pmr_id = ".$value->pmr_id."";
                                    $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();
                                    echo $kode."<br>";
                                    ?>
                                <?php
                                    $pmr_id = $value->pmr_id;
                                }
                                ?>
                                </td>
                            <?php
                            } else if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "GRADING") {
                                $i = 1;
                                ?>
                                <td style="width: 30%">Kode Permintaan</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 65%">
                                <?php
                                $pengajuan_pembelianlog_id = "";
                                foreach ($modMap as $kolom => $value) {
                                ?>
                                    <?php
                                    $sql_kode = "select kode from t_pengajuan_pembelianlog where pengajuan_pembelianlog_id = ".$value->pengajuan_pembelianlog_id."";
                                    $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();
                                    echo $kode;
                                    ?>
                                <?php
                                     $pengajuan_pembelianlog_id = $value->pengajuan_pembelianlog_id;
                                }
                                ?>
                                </td>
                            <?php
                            } else {
                                
                            }
                            ?>
                            </tr>
						</table>
					</div>
					<div class="col-md-5">
						<table style="width: 100%; background-color: #d5e1f3; color: #083881; border-bottom-color: #FFF">
							<tr>
								<td style="width: 30%">Nama Grader</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->graderlog->graderlog_nm ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Wilayah Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->wilayahDinas->wilayah_dinas_nama ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Tujuan Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= !empty($modDkg->tujuan)?$modDkg->tujuan:"-"; ?></td>
							</tr>
                            <tr>
                                <td style="width: 30%">Keterangan</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->keterangan;?></td>
                            </tr>
						</table>
					</div>
					<div class="col-md-1"></div>
				</div>
                <br>
                <?php
                if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "ORIENTASI") {
                    $modMap = \app\models\MapDkgPmrPengajuanPembelianlog::find()->where(['dkg_id'=>$modDkg->dkg_id])->all();
                ?>
                <div class="row">
                    <div class="col-md-12" style="text-align: center;"><h4>Detail Permintaan Pembelian Log</h4></div>
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <tr>
                                <th class="td-kecil text-center">Kode</th>
                                <th class="td-kecil text-center">Tanggal</th>
                                <th class="td-kecil text-center">Tujuan</th>
                                <th class="td-kecil text-center">Tanggal Dibutuhkan</th>
                                <th class="td-kecil text-center">Total Volume (m<sup>3<sup>)</th>
                            </tr>
                            <?php
                            foreach ($modMap as $kolom => $value) {
                            $modPmr = \app\models\TPmr::findOne($value->pmr_id);
                            ?>
                            <tr>
                                <td class="td-kecil text-center"><?php echo $modPmr->kode;?></td>
                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal);?></td>
                                <td class="td-kecil text-center"><?php echo $modPmr->tujuan;?></td>
                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_awal);?> s/d <?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_akhir);?></td>
                                <td class="td-kecil text-right">
                                    <?php
                                    $sql_qty_m3 = "select sum(qty_m3) as vol from t_pmr_detail where pmr_id = ".$pmr_id."";
                                    $qty_m3 = Yii::$app->db->createCommand($sql_qty_m3)->queryScalar();
                                    echo \app\components\DeltaFormatter::formatNumberForAllUser($qty_m3);
                                    ?>
                                </td>
                                <?php
                                
                                ?>
                            </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <?php
                } else if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "GRADING") {
                    $modMap = \app\models\MapDkgPmrPengajuanPembelianlog::find()->where(['dkg_id'=>$modDkg->dkg_id])->all();
                ?>
                <div class="row">
                    <div class="col-md-12" style="text-align: center;"><h4>Detail Pengajuan Pembelian Log</h4></div>
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <tr>
                                <th class="td-kecil text-center">Kode</th>
                                <th class="td-kecil text-center">Tanggal</th>
                                <th class="td-kecil text-center">Volume Kontrak</th>
                                <th class="td-kecil text-center">Asal Kayu</th>
                                <th class="td-kecil text-center">Total Pcs</th>
                                <th class="td-kecil text-center">Total Volume (m<sup>3<sup>)</th>
                            </tr>
                            <?php
                            $modPengajuanPembelianlog = \app\models\TPengajuanPembelianlog::findOne($pengajuan_pembelianlog_id);
                            ?>
                            <tr>
                                <td class="td-kecil text-center"><?php echo $modPengajuanPembelianlog->kode;?></td>
                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPengajuanPembelianlog->tanggal);?></td>
                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($modPengajuanPembelianlog->volume_kontrak);?></td>
                                <td class="td-kecil text-center"><?php echo $modPengajuanPembelianlog->asal_kayu;?></td>
                                <td class="td-kecil text-right">
                                    <?php
                                    $sql_jml = "select sum(qty_batang) as jml from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."";
                                    $qty_jml = Yii::$app->db->createCommand($sql_jml)->queryScalar();
                                    echo \app\components\DeltaFormatter::formatNumberForAllUser($qty_jml);
                                    ?>
                                </td>
                                <td class="td-kecil text-right">
                                    <?php
                                    $sql_qty_m3 = "select sum(qty_m3) as vol from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."";
                                    $qty_m3 = Yii::$app->db->createCommand($sql_qty_m3)->queryScalar();
                                    echo \app\components\DeltaFormatter::formatNumberForAllUser($qty_m3);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php
                } else {

                }
                ?>
                <div class="row">
                    <div class="modal-footer" style="text-align: center;">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                // approve level 1
                                if(!empty($modDkg->approve_reason)){
                                    $modApproveReason = \yii\helpers\Json::decode($modDkg->approve_reason);
                                    foreach($modApproveReason as $kolom => $value){
                                        $modDkg->jenis_log == "LA" ? $approver = 22 : $approver = 56;
                                        if($value['by'] == $approver){
                                            $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                            $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                        ?>
                                           <span>
                                           <br>&nbsp; <span class='font-green-seagreen text-bold'><?php echo $approver;?></span>
                                           <br>&nbsp; <span class='font-green-seagreen'>Approved at : <?php echo $tanggal;?></span>
                                           <br>&nbsp; <span class='font-green-seagreen'>Reason : <?php echo $value['reason'];?></span>
                                           </span>
                                        <?php
                                        }
                                    }
                                }

                                // reject level 1
                                if(!empty($modDkg->reject_reason)){
                                    $modApproveReason = \yii\helpers\Json::decode($modDkg->reject_reason);
                                    foreach($modApproveReason as $kolom => $value){
                                        $modDkg->jenis_log == "LA" ? $approver = 22 : $approver = 56;
                                        if($value['by'] == $approver){
                                            $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                            $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                        ?>
                                           <span>
                                           <br>&nbsp; <span class='text-danger text-bold'><?php echo $approver;?></span>
                                           <br>&nbsp; <span class='text-danger'>Rejected at : <?php echo $tanggal;?></span>
                                           <br>&nbsp; <span class='text-danger'>Reason : <?php echo $value['reason'];?></span>
                                           </span>
                                        <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                // approve level 2
                                if(!empty($modDkg->approve_reason)){
                                    $modApproveReason = \yii\helpers\Json::decode($modDkg->approve_reason);
                                    foreach($modApproveReason as $kolom => $value){
                                        $approver = 124;
                                        if($value['by'] == $approver){
                                            $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                            $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                        ?>
                                           <span>
                                           <br>&nbsp; <span class='font-green-seagreen text-bold'><?php echo $approver;?></span>
                                           <br>&nbsp; <span class='font-green-seagreen'>Approved at : <?php echo $tanggal;?></span>
                                           <br>&nbsp; <span class='font-green-seagreen'>Reason : <?php echo $value['reason'];?></span>
                                           </span>
                                        <?php
                                        }
                                    }
                                }

                                // reject level 2
                                if(!empty($modDkg->reject_reason)){
                                    $modApproveReason = \yii\helpers\Json::decode($modDkg->reject_reason);
                                    foreach($modApproveReason as $kolom => $value){
                                        $approver = 124;
                                        if($value['by'] == $approver){
                                            $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                            $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                        ?>
                                           <span>
                                           <br>&nbsp; <span class='text-danger text-bold'><?php echo $approver;?></span>
                                           <br>&nbsp; <span class='text-danger'>Rejected at : <?php echo $tanggal;?></span>
                                           <br>&nbsp; <span class='text-danger'>Reason : <?php echo $value['reason'];?></span>
                                           </span>
                                        <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
                            <?php if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){ ?>
                            <?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
                            <?php
                            if ($model->level == 1) {
                            ?>
                            <?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
                            <?php
                            }
                            ?>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
			</div>
            
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>
<script>

</script>