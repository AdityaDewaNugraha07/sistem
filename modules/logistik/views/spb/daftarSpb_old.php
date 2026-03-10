<div class="modal fade draggable-modal" id="modal-daftar-spb" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar SPB telah diajukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<?= "Total : ".count($modDetail).' rows.'; ?>
							<table class="table table-striped table-bordered table-advance table-hover table-laporan" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Kode / No. SPB'); ?></th>
										<th><?= Yii::t('app', 'Tanggal'); ?></th>
										<th><?= Yii::t('app', 'Diminta Oleh'); ?></th>
										<th><?= Yii::t('app', 'Disetujui Oleh'); ?></th>
										<th><?= Yii::t('app', 'Status SPB'); ?></th>
										<th><?= Yii::t('app', 'Status Approval'); ?></th>
										<th style="width: 230px;"><?= Yii::t('app', 'Penerimaan'); ?></th>
										<th style="width: 120px;"><?= Yii::t('app', ''); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($modDetail as $i => $detail){ 
									?>
									<tr>
										<td><?php echo $i+1 ?></td>
										<td><?php echo $detail->spb_kode. (!empty($detail->spb_nomor)?' / '.$detail->spb_nomor:''); ?></td>
										<td><?php echo !empty($detail->spb_tanggal)?\app\components\DeltaFormatter::formatDateTimeForUser2($detail->spb_tanggal):' - '; ?></td>
										<td><?php echo !empty($detail->spb_diminta)?$detail->spbDiminta->pegawai_nama:' - '; ?></td>
										<td><?php echo !empty($detail->spb_disetujui)?$detail->spbDisetujui->pegawai_nama:' - '; ?></td>
										<td>
											<?php
											if($detail->spb_status == 'BELUM DIPROSES'){
												echo '<span class="label label-sm label-info"> '.$detail->spb_status .' </span>';
											}else if($detail->spb_status == 'SEDANG DIPROSES'){
												echo '<span class="label label-sm label-warning"> '.$detail->spb_status .' </span>';
											}else if($detail->spb_status == 'DITOLAK'){
												echo '<span class="label label-sm label-danger"> '.$detail->spb_status .' </span>';
											}else if($detail->spb_status == 'TERPENUHI'){
												echo '<span class="label label-sm label-success"> '.$detail->spb_status .' </span>';
											}
											?>
									
										</td>
										<td>
											<?php
											if(!empty($detail->approve_status)){
												if($detail->approve_status == \app\models\TApproval::STATUS_APPROVED){
													echo '<span class="label label-sm label-success"> '.$detail->approve_status .' </span>';
												}else if($detail->approve_status == \app\models\TApproval::STATUS_REJECTED){
													echo '<span class="label label-sm label-danger"> '.$detail->approve_status  .' </span>';
												}else{
													echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
												}
											}else{
												echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
											}
											?>

										</td>
										<td data-spb="<?= $detail->spb_id; ?>" style="text-align:center">
											<?php 
											$modBpb = \app\models\TBpb::find()->where(['spb_id'=>$detail->spb_id])->orderBy(['created_at'=>SORT_DESC])->all();
											if(count($modBpb)>0){
												foreach($modBpb as $i => $bpb){
													if($i!=0){ echo "<br>"; }
													if($bpb->bpb_status == "BELUM DITERIMA"){
														echo "<a style='font-size:0.8em;' class='font-red-intense' data-bpb='$bpb->bpb_id' onclick='infoBpb(".$bpb->bpb_id.")'>".$bpb->bpb_kode." - ".$bpb->bpb_status."</a>";
													}else if($bpb->bpb_status == "SUDAH DITERIMA"){
														echo "<a style='font-size:0.8em;' class='font-green-meadow' data-bpb='$bpb->bpb_id' onclick='infoBpb(".$bpb->bpb_id.")'>".$bpb->bpb_kode." - ".$bpb->bpb_status."</a>";
													}
												}
											}else{
												echo "<i style='font-size:0.8em;'>-- ".Yii::t('app', 'Belum ada BPB')." --</i>";
											}
											?>
										</td>
										<td style="text-align: center;">
											<a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat data pengajuan SPB" onclick="lihatSpb(<?= $detail->spb_id ?>)"><i class="fa fa-eye"></i></a>
											<?php if( ($detail->spb_status == 'BELUM DIPROSES') && ($detail->approve_status == app\models\TApproval::STATUS_NOT_CONFIRMATED) ){ ?>
											<a class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Ubah data pengajuan SPB" onclick="editSpb(<?= $detail->spb_id ?>)"><i class="fa fa-edit"></i></a>
											<!--<a class="btn btn-xs red tooltips" data-original-title="Batalkan pengajuan SPB ini" onclick="openModal('<?php // echo \yii\helpers\Url::toRoute(['/logistik/spb/delete','id'=>$detail->spb_id])?> ','modal-delete-record')"><i class="fa fa-remove"></i></a>-->
											<?php } ?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							<?= \yii\bootstrap\Html::hiddenInput('row',0,['id'=>'row']) ?>
							<?= \yii\bootstrap\Html::hiddenInput('all',0,['id'=>'row']) ?>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    $(\".tooltips\").tooltip({ delay: 50 });
	
	// Membuat modal layer pertama tidak menumpuk saat muncul modal kedua
	$(document).on('show.bs.modal', '.modal', function (event) {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});
	
", yii\web\View::POS_READY); ?>
<script>
function lihatSpb(spb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/spb/index','spb_id'=>'']); ?>'+spb_id);
}
function editSpb(spb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/spb/index','edit'=>true,'spb_id'=>'']); ?>'+spb_id);
}

function refreshInfoSpb(bpb_id){
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/logistik/spb/daftarSpb']); ?>',
        type   : 'GET',
        data   : {bpb_id:bpb_id,refresh:true},
        success: function (data) {
            if(data){
                $('#table-detail tbody tr td a[data-bpb="'+bpb_id+'"]').parents('td').html(data);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

//function loadmore(){
//	// get summary data
//	$.ajax({
//        url    : '<?= \yii\helpers\Url::toRoute(['/logistik/spb/daftarSpb']); ?>',
//        type   : 'GET',
//        data   : {loadmoresummary:true},
//        success: function (data) {
//            var row = Number($('#row').val());
//			var allcount = data.allcount;
//			var rowperpage = 3;
//			row = row + rowperpage;
//        },
//        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
//    });
//	
//	
//	var row = Number($('#row').val());
//	var allcount = Number($('#all').val());
//	var rowperpage = 3;
//	row = row + rowperpage;
//
//	if(row <= allcount){
//		$("#row").val(row);
//		$.ajax({
//			url: 'getData.php',
//			type: 'post',
//			data: {row:row},
//			beforeSend:function(){
//				$(".load-more").text("Loading...");
//			},
//			success: function(response){
//
//				// Setting little delay while displaying new content
//				setTimeout(function() {
//					// appending posts after last post with class="post"
//					$(".post:last").after(response).show().fadeIn("slow");
//
//					var rowno = row + rowperpage;
//
//					// checking row value is greater than allcount or not
//					if(rowno > allcount){
//
//						// Change the text and background
//						$('.load-more').text("Hide");
//						$('.load-more').css("background","darkorchid");
//					}else{
//						$(".load-more").text("Load more");
//					}
//				}, 2000);
//
//			}
//		});
//	}else{
//		$('.load-more').text("Loading...");
//
//		// Setting little delay while removing contents
//		setTimeout(function() {
//
//			// When row is greater than allcount then remove all class='post' element after 3 element
//			$('.post:nth-child(3)').nextAll('.post').remove();
//
//			// Reset the value of row
//			$("#row").val(0);
//
//			// Change the text and background
//			$('.load-more').text("Load more");
//			$('.load-more').css("background","#15a9ce");
//
//		}, 2000);
//
//
//	}
//}

</script>