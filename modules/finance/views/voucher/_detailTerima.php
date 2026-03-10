<?php 
$modSuplier = \app\models\MSuplier::findOne($supplier_id);
$supplier = $modSuplier->suplier_nm_company; //$modSuplier->suplier_nm;
$alamat = $modSuplier->suplier_almt;
$bank = $modSuplier->suplier_bank;
$rekening = $modSuplier->suplier_norekening;
$an_rek = $modSuplier->suplier_an_rekening;
?>
<div class="col-md-12">
	<h4><?= Yii::t('app', 'Detail Penerimaan'); ?></h4>
	<?php if($gkk == 'bukan gkk'){ ?>
		<table style="width: 100%; font-size: 1.1rem;">
			<tr>
				<td style="vertical-align: text-top">Penerima</td>
				<td style="vertical-align: text-top; text-align: center;"><b>:</b></td>
				<td style="vertical-align: text-top"><b><?= $supplier.''. ( !empty($alamat)?"<br>".$alamat:"" );?></b></td>
			</tr>
			<tr>
				<td style="width: 120px; vertical-align: text-top;">Rekening Bank</td>
				<td style="width: 20px; vertical-align: text-top; text-align: center;"><b>:</b></td>
				<td style="vertical-align: text-top;"><b><?= $bank." - ".$rekening."<br>a.n. ". $an_rek; ?></b></td>       
			</tr>
		</table>
	<?php } ?>
</div>
<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
	<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;" id="table-detail-terima" >
        <thead>
			<tr>
				<th style="width: 20px;"><?php echo Yii::t('app', 'No.'); ?></th>
				<th style="width: 120px; line-height: 0.9;">Kode TBP / <span class="font-blue-soft">Invoice</span></th>
				<th style="width: 75px;"><?= Yii::t('app', 'Tanggal'); ?></th>
				<th style="width: 100px;">DPP</th>
				<th style="width: 100px;"><?= Yii::t('app', 'PPn'); ?></th>
				<th style="width: 100px;"><?= Yii::t('app', 'PPh'); ?></th>
				<th style=""><?= Yii::t('app', ''); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(count($modDetail)){
				$totaldpp = 0;
				$totalppn = 0;
				$totalpph = 0;
				$totalpbbkb = 0;
				$totalbiayatambahan = 0;
                $total_potongan = 0;
				foreach($modDetail as $i => $detail){
                    $sql = "SELECT * FROM t_terima_bhp_detail 
                            JOIN m_brg_bhp ON m_brg_bhp.bhp_id = t_terima_bhp_detail.bhp_id 
                            WHERE terima_bhp_id = ".$detail->terima_bhp_id;
					$detailterimas = Yii::$app->db->createCommand($sql)->queryAll();
					$totalterimadetail = 0;
					$totalpphdetail = 0;
                    $totalretur = 0;
                    $totalreturpph = 0;
                    $totalsemuapph = 0;
                    $totalpph = 0;
					if(count($detailterimas)>0){
						foreach($detailterimas as $ii => $detailterima){
                            $modRetur = \app\models\TReturBhp::findOne(['terima_bhpd_id'=>$detailterima['terima_bhpd_id']]);
                            if(!empty($modRetur)){
                                $totalretur +=  $modRetur->total_kembali;

                                // hitung pph yang diretur
                                $modPphRetur = \app\models\TTerimaBhpDetail::findOne(['terima_bhpd_id'=>$detailterima['terima_bhpd_id']]);
                                $totalreturpph += $modPphRetur->pph_peritem;
                            }

                            // hitung semuah terima detail
                            $totalterimadetail += $detailterima['terimabhpd_harga'] * $detailterima['terimabhpd_qty'];

                            // hitung semuah pph
                            $totalsemuapph += $detailterima['pph_peritem'];
                        }
                    }
                    
                    // kolom dpp
                    // kurangi semuah total terima detail dengan returnya
                    $totalterimadetail = $totalterimadetail - $totalretur;

                    // kolom pph
                    // kurangi semuah pph dengan pph yang diretur
                    $totalpph += $totalsemuapph - $totalreturpph;
                    
                    $pbbkb = !empty($detail->total_pbbkb)?$detail->total_pbbkb:0;
					$biayatambahan = !empty($detail->total_biayatambahan)?$detail->total_biayatambahan:0;
					$totalpbbkb += $pbbkb;
					$totalbiayatambahan += $biayatambahan;
                    $total_potongan += !empty($detail->potonganharga) ? $detail->potonganharga : 0;
					$totaldpp += $totalterimadetail;
					$totalppn += $detail->ppn_nominal;
					$totalpph += $totalpphdetail;
					
					$matauang = "Rp";
					if(!empty($detail->spo_id)){
						$modSPO = \app\models\TSpo::findOne($detail->spo_id);
						if(!empty($modSPO)){
							$matauang = $modSPO->defaultValue->name_en;
						}
					}
				?>
					<tr>
						<td style="text-align: center;"><?= $i+1; ?></td>
						<td style="text-align: center; font-size: 1.2rem; padding: 5px; line-height: 0.85">
							<?= $detail->terimabhp_kode; ?>
							<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
							<?= \yii\bootstrap\Html::hiddenInput('TTerimaBhp[ii][terima_bhp_id]',$detail->terima_bhp_id,[]); ?>
							<!--<span class="no_urut"></span>-->
							<span class="font-blue-soft"><?= (!empty($detail->nofaktur)?"<br>".$detail->nofaktur:'<center>-</center>'); ?></span>
							<span style="font-size: 0.8rem;" class="font-red-mint">
								<?= !empty($detail->tanggal)?"<br>Diajukan tgl ".\app\components\DeltaFormatter::formatDateTimeForUser2($detail->tanggal):""; ?>
								<?= !empty($detail->keterangan)?"<br>". substr($detail->keterangan, 0, 24):""; ?>
							</span>
						</td>
						<td style="text-align: center; font-size: 1.2rem; padding: 5px;">
							<?= app\components\DeltaFormatter::formatDateTimeForUser2($detail->tglterima); ?>
						</td>
                        <td style="font-size: 1.2rem; padding: 5px; text-align: right; font-size: 1.1rem;">
							<?= \yii\bootstrap\Html::hiddenInput('TTerimaBhp[ii][dpp]',$totalterimadetail,[]); ?>
							<span class="pull-left"><?= $matauang ?></span>
                            <span class="pull-right"><?= \app\components\DeltaFormatter::formatNumberForUser($totalterimadetail); ?></span>
						</td>
						<td style="font-size: 1.2rem; padding: 5px; text-align: right;">
							<?= \yii\bootstrap\Html::hiddenInput('TTerimaBhp[ii][ppn]',$detail->ppn_nominal,[]); ?>
							<span class="pull-left"><?= $matauang ?></span>
							<span class="pull-right"><?= \app\components\DeltaFormatter::formatNumberForUser($detail->ppn_nominal); ?></span>
						</td>
						<td style="font-size: 1.2rem; padding: 5px; text-align: right;">
							<?= \yii\bootstrap\Html::hiddenInput('TTerimaBhp[ii][pph]',$totalpph,[]); ?>
							<span class="pull-left"><?= $matauang ?></span>
							<span class="pull-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($totalpph);?></span>
						</td>
						<td style="padding: 0px; text-align: center;">
                            <?= \yii\bootstrap\Html::hiddenInput('TTerimaBhp[ii][pbbkb]',$pbbkb,[]); ?>
                            <?= \yii\bootstrap\Html::hiddenInput('TTerimaBhp[ii][biayatambahan]',$biayatambahan,[]); ?>
                            <a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="detailTerima(this,<?= $detail->terima_bhp_id; ?>,'<?= $detail->terimabhp_kode; ?>')" style="margin-right: -2px;"><i class="fa fa-info-circle"></i></a>
							<?php if(empty($voucher_pengeluaran_id)){ ?>
								<?php if($gkk == 'bukan gkk'){ ?>
								<a class="btn btn-xs red btn-outline" href="javascript:void(0)" onclick="cancelTerima(this)"><i class="fa fa-remove"></i></a>
							<?php }} ?>
                            <?php
                            if($pbbkb>0){
                                echo "<br><span style='font-size:1rem;' class='font-green-seagreen'>+pbbkb</span>";
                            }
                            if($biayatambahan>0){
                                echo "<br><span style='font-size:1rem;' class='font-green-seagreen'>+biaya</span>";
                            }
                            ?>
						</td>
					</tr>
			<?php	}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" style="font-weight: bold; vertical-align: middle; font-size:1.4rem; text-align: right; padding: 8px;">
					<u>Total</u> &nbsp;
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::textInput('totaldppreff', \app\components\DeltaFormatter::formatNumberForUser($totaldpp), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::textInput('totalppnreff', \app\components\DeltaFormatter::formatNumberForUser($totalppn), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::textInput('totalpphreff', \app\components\DeltaFormatter::formatNumberForUser($totalpph), ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
				<td style="font-weight: bold; vertical-align: middle; font-size:1.2rem; text-align: right; padding: 5px;">
					<?= \yii\bootstrap\Html::hiddenInput('totalpbbkb', $totalpbbkb, ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
					<?= \yii\bootstrap\Html::hiddenInput('bhpbiayatambahan', $totalbiayatambahan, ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
                    <?= \yii\bootstrap\Html::hiddenInput('bhppotonganharga', $total_potongan, ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding:3px; font-size: 1.2rem;']); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<script>
setTotalTerima();
function detailTerima(ele,terima_bhp_id,terimabhp_kode){
	if( $('#table-detail-terima > tbody > tr[id="detail-terima-'+terima_bhp_id+'"]').length != 0 ){ // hide
		$(ele).removeClass('animation-loading');
		$(ele).find('i').attr('class','fa fa-info-circle');
		$('#table-detail-terima > tbody > tr[id="detail-terima-'+terima_bhp_id+'"]').remove();
	}else{ // show
		$(ele).addClass('animation-loading');
		$(ele).find('i').attr('class','fa fa-sort-up');
		$('<tr id="detail-terima-'+terima_bhp_id+'"></tr>').hide().insertAfter($(ele).closest('tr')).fadeIn(100,function(){
			var trPlace = $(this);
			$.ajax({
				url    : '<?= \yii\helpers\Url::toRoute(['/finance/voucher/getDetailTerima']); ?>',
				type   : 'POST',
				data   : {terima_bhp_id:terima_bhp_id},
				success: function (data) {
					if(data.html){
						$('#table-detail-terima > tbody > tr[id="detail-terima-'+terima_bhp_id+'"]')
							.html('<td colspan="8" style="width:100%; background-color:#e2f1ff"><center><span style="font-size:1.5rem; margin-top: -5px;">Detail Terima <b>'+terimabhp_kode+'</b></span></center>'+data.html+'</td>');
						$(ele).removeClass('animation-loading');
					}
				},
				error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			});
			
		});
	}
}
function cancelTerima(ele){
	$(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
		setTotalTerima();
		setTotalPembayaran();
        reordertable('#table-detail-terima');
        setAutoItems();
    });
}
function setTotalTerima(){
	var totaldpp = 0;
	var totalppn = 0;
	var totalpph = 0;
	var totalpbbkb = parseInt("<?= $totalpbbkb ?>");
	var totalbiayatambahan = parseInt("<?= $totalbiayatambahan ?>");
	$('#table-detail-terima > tbody > tr').each(function(){
		totaldpp += unformatNumber($(this).find('input[name*="[dpp]"]').val());
		totalppn += unformatNumber($(this).find('input[name*="[ppn]"]').val());
		totalpph += unformatNumber($(this).find('input[name*="[pph]"]').val());
	});
	if(totalpbbkb > 0){
		<?php if(!empty($voucher_pengeluaran_id)){ ?>
			$('input[name*="[total_pbbkb]"]').attr('disabled','disabled');
		<?php }else{ ?>
			$('input[name*="[total_pbbkb]"]').removeAttr('disabled');
		<?php } ?>
	}else{
		$('input[name*="[total_pbbkb]"]').attr('disabled','disabled');
	}
	$('input[name="totaldppreff"]').val(formatInteger(totaldpp));
	$('input[name="totalppnreff"]').val(formatInteger(totalppn));
	$('input[name="totalpphreff"]').val(formatInteger(totalpph));
}
</script>