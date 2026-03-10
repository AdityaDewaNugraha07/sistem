<?php
$model = \app\models\TApproval::findOne($approval_id);
$approval_id = $model->approval_id;

$model_m_harga_produk = \app\models\MHargaProduk::findAll(['kode'=>$model->reff_no]);
$model_m_harga_produkx = \app\models\MHargaProduk::findOne(['kode'=>$model->reff_no]);

//$approve_reason = yii\helpers\Json::decode($model_h_harga_produk->approve_reason);
//$reject_reason = yii\helpers\Json::decode($model_h_harga_produk->reject_reason);

$model_t_approval = \app\models\TApproval::findAll(['reff_no'=>trim($model->reff_no)]);

?>

<div class="modal-body">


	<table class="table">
		<tr>
			<th style="padding: 20px;">No.</th>
			<th style="padding: 20px;">Kode Nama</th>
			<th style="padding: 20px;">Kode Dimensi</th>
			<th style="padding: 20px;" class="text-right">Harga Sebelumnya</th>
			<th style="padding: 20px;" class="text-right">Harga Pengajuan</th>
		</tr>
		<?php
		$i = 1;
		foreach ($model_m_harga_produk as $list => $kolom) {
			$produk_id = $kolom['produk_id'];
			$harga_tanggal_penetapan = $kolom['harga_tanggal_penetapan'];

			$harga_enduser = $kolom['harga_enduser'];
			
			$produk = \app\models\MBrgProduk::findOne($produk_id);
			$produk_kode = $produk->produk_kode;
			$produk_nama = $produk->produk_nama;
			$produk_dimensi = $produk->produk_dimensi;

			$status_approval = $kolom['status_approval'];
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $produk_nama;?></td>
			<td><?php echo $produk_dimensi;?></td>
			<td class="text-right">
			<?php
			$sql_harga_lama = "select a.harga_enduser ".
							"	from m_harga_produk a ".
							"	where a.produk_id = ".$produk_id." ".
							"	and a.status_approval = 'APPROVED' ".
							"	and a.harga_tanggal_penetapan < '".$harga_tanggal_penetapan."' ".
							"	order by a.harga_id desc ".
							"	limit 1 ".
							"	";
			$harga_lama = Yii::$app->db->createCommand($sql_harga_lama)->queryScalar();
			$harga_lama > 0 || $harga_lama != NULL ? $harga_lama = $harga_lama : $harga_lama = 0;
			echo $harga_lama;
			?>
			</td>
			<td class="text-right">Rp <?php echo \app\components\DeltaFormatter::formatNumberForUser($harga_enduser);?></td>
		</tr>
		<?php
			$i++;
		}
		?>
	</table>

	<div class="modal-footer" style="text-align: center;">
		<div class="container col-md-12">
			<div class="row">
	        <?php
	        $model_m_harga_produk = \app\models\MHargaProduk::findOne(['kode'=>$model->reff_no]);

	        if (!empty($model_m_harga_produk->approve_reason) || !empty($model_m_harga_produk->reject_reason)) {
                //approval 1 : gm marketing (inge tjandra 122)
                //approval 2 : kadiv akt (nowo eko yulianto 58)
                //approval 3 : dirut (heryanto suwardi 22)
                //approval 4 : dir (agus soewito 59)
	        	$levels = array('level1'=>122,'level2'=>58,'level3'=>22,'level4'=>59);
	        	foreach ($levels as $level => $pegawai_id) {
	        		$pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$pegawai_id]);
	        		$t_approval = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>$pegawai_id]);
	        	?>
	            <div class="col-md-3" style="font-size: 1.2rem;">
	                <?php
	                $color = "";
	                if ($t_approval->status == "APPROVED") {
	                    $color = "#38C68B";
	                    $reasons = json_decode($model_m_harga_produk->approve_reason);
	                    foreach($reasons as $reason) {
	                        if ($pegawai_id == $reason->by) {
	                            $reasonx = $reason->reason;
	                        }
	                    }
	                } 

	                if ($t_approval->status == "REJECTED") {
	                    $color = "#f00";
	                    $reasons = json_decode($model_m_harga_produk->reject_reason);
	                    foreach($reasons as $reason) {
	                        if ($pegawai_id == $reason->by) {
	                            $reasony = $reason->reason;
	                        }
	                    }				                            	
	                }

	                isset($reasonx) ? $reasonx = $reasonx : $reasonx = "";
	                isset($reasony) ? $reasony = $reasony : $reasony = "";
	                ?>
	                <span style="color: <?php echo $color;?>"><strong><?php echo $pegawai->pegawai_nama;?></strong></span>
	                <br>
	                <span style="color: <?php echo $color;?>"><?php echo $t_approval->status;?></span> 
	                <span style="color: <?php echo $color;?>">at <?php echo app\components\DeltaFormatter::formatDateTimeForUser2($t_approval->updated_at);?></span>
	                <br>
	                <span style="color: <?php echo $color;?>">
	                	<?php 
	                	if ($t_approval->status == "APPROVED") {
	                		echo $reasonx;
	                	} 

	                	if ($t_approval->status == "REJECTED") {
	                		echo $reasony; 
	                	}
	                	?>
	                </span> 
	            </div>
	        	<?php
	        	}
	        }
	        ?>
		    </div>

			<?php
			if ($model->status == "Not Confirmed") {
			?>
			<div class="row" style="padding-top: 10px; padding-bottom: 10px;">
			<?php
				if( (empty($model_t_approval->approved_by)) && (empty($model_t_approval->tanggal_approve)) ){
					if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )) {
						$model_m_harga_produk = \app\models\MHargaProduk::findOne(['kode'=>$model->reff_no]);
						
						// cari level dibawahnya dulu
						$level_approver_sebelumnya = $model->level - 1;

						$sql = "select * from t_approval where reff_no = trim('".$model_m_harga_produk->kode."') AND level = ".$level_approver_sebelumnya." AND status != 'Not Confirmed' ";
						$checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();

						if ($model->level == 1 && count($checkApprovals) == 0) {
							echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
						}

						if ($model->level == 2 && count($checkApprovals) > 0) {
							echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
						}

						if ($model->level == 3 && count($checkApprovals) > 0) {
							echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
						}

						if ($model->level == 4 && count($checkApprovals) > 0) {
							echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
						}
					}
				}
			?>
			</div>
			<?php
			}
			?>
		</div>

	</div>
</div>


