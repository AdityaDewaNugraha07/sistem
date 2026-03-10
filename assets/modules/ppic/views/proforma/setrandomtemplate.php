<div class="modal fade" id="modal-setrandomtemplate" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Set Bundle Details'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-setrandomtemplate',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="form-group col-md-12" style="margin-bottom: 5px;">
                            <label class="col-md-4 control-label">Total Bundles</label>
                            <div class="col-md-8">
								<?php echo yii\bootstrap\Html::textInput("total_bundles",0,["class"=>"form-control float","onblur"=>'setBundlePartitionValues(this)']); ?>
							</div>
                        </div>
						<div class="form-group col-md-12" style="margin-bottom: 5px;">
                            <label class="col-md-4 control-label">Total Partition</label>
                            <div class="col-md-8">
								<?php echo yii\bootstrap\Html::textInput("total_partition",1,["class"=>"form-control float","onblur"=>'setBundlePartitionValues(this)']); ?>
							</div>
                        </div>
						<div class="form-group col-md-12 place-ukuran" style="margin-bottom: 10px;">
                            <label class="col-md-4 control-label">Thicknes Size</label>
                            <div class="col-md-8">
								<div class="place-size">
									<div class="place-size-item">
										<span class="input-group-btn" style="width: 150px;">
											<?php echo yii\bootstrap\Html::textInput("thick[ii]",0,["class"=>"form-control float place-thick font-purple","style"=>"font-weight: 600"]); ?>
										</span>
										<span class="input-group-btn" style="width: 90px">
											<?php echo yii\bootstrap\Html::dropDownList("thick_unit", "mm", app\models\MDefaultValue::getOptionList("produk-satuan-dimensi"),['class'=>'form-control']); ?>
										</span>
									</div>
								</div>
								<a style="font-size: 1.1rem;" onclick="add(this)" class="add-btn"><i class="fa fa-plus"></i> Add Thicknes Size</a>
							</div>
                        </div>
						<div class="form-group col-md-12 place-ukuran" style="margin-bottom: 10px;">
                            <label class="col-md-4 control-label">Width Size</label>
							<div class="col-md-8">
								<div class="place-size">
									<div class="place-size-item">
										<span class="input-group-btn" style="width: 150px;">
											<?php echo yii\bootstrap\Html::textInput("width[ii]",0,["class"=>"form-control float place-thick font-purple","style"=>"font-weight: 600"]); ?>
										</span>
										<span class="input-group-btn" style="width: 90px">
											<?php echo yii\bootstrap\Html::dropDownList("width_unit", "mm", app\models\MDefaultValue::getOptionList("produk-satuan-dimensi"),['class'=>'form-control']); ?>
										</span>
									</div>
								</div>
								<a style="font-size: 1.1rem;" onclick="add(this)" class="add-btn"><i class="fa fa-plus"></i> Add Width Size</a>
							</div>
                        </div>
						<div class="form-group col-md-12 place-ukuran" style="margin-bottom: 10px;">
                            <label class="col-md-4 control-label">Length Size</label>
							<div class="col-md-8">
								<div class="place-size">
									<div class="place-size-item">
										<span class="input-group-btn" style="width: 150px;">
											<?php echo yii\bootstrap\Html::textInput("length[ii]",0,["class"=>"form-control float place-thick font-purple","style"=>"font-weight: 600"]); ?>
										</span>
										<span class="input-group-btn" style="width: 90px">
											<?php echo yii\bootstrap\Html::dropDownList("length_unit", "mm", app\models\MDefaultValue::getOptionList("produk-satuan-dimensi"),['class'=>'form-control']); ?>
										</span>
									</div>
								</div>
								<a style="font-size: 1.1rem;" onclick="add(this)" class="add-btn"><i class="fa fa-plus"></i> Add Length Size</a>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Set'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
//                    'onclick'=>'set(this);'
                    'onclick'=>'inputContainerRandom(this);'
                    ]);
                        ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig(); reorder();
", yii\web\View::POS_READY); ?>
<script type="text/javascript">
function add(ele){
	var name = $(ele).parents('.form-group').find('.form-control:first').attr('name').split('[')[0];
	$(ele).parents('.form-group').find('.place-size').append('<div class="place-size-item"><span class="input-group-btn" style="width: 150px;"><input class="form-control float place-thick font-purple" name="'+name+'[ii]" value="0" type="text" style="font-weight: 600"></span>\n\
															  <span class="input-group-btn" style="width: 90px;"><a class="btn btn-sm red btn-outline" onclick="remove(this)" style="margin-left: 2px;"><i class="fa fa-close"></i></a></span>');
	reorder();
}
function reorder(){
	var row = 0;
	$(".place-thick").each(function(index){
		var el_name = $(this).attr('name').split('[')[0];
		$(this).attr('name',el_name+'['+index+']');
	});
	$(".place-ukuran").each(function(index){
		if($(this).find('input').length > 1){
			$(this).find('a.add-btn').css('visibility','visible');
		}else{
			$(this).find('a.add-btn').css('visibility','hidden');
		}
	});
	if($('input.place-thick').length <= 3){
		$('a.add-btn').css('visibility','visible');
	}
}
function remove(ele){
	$(ele).parents(".place-size-item").remove();
	reorder();
}

function setBundlePartitionValues(ele){
	var total_partition = unformatNumber($(ele).val());
	var asd = total_partition;
	if(total_partition <= 1){
		asd = 1;
	}
	$(ele).val( formatNumberForUser(asd) );
}

function set(ele){
	var has_error = 0;
	$('#modal-setrandomtemplate').find('input.form-control').each(function(){
		$(this).val( unformatNumber($(this).val()) );
		if( unformatNumber( $(this).val() ) <= 0 ){
			has_error = has_error + 1;
			$(this).addClass("error-tb-detail");
		}else{
			$(this).removeClass("error-tb-detail");
		}
	});
	if(has_error === 0){
		var $form = $(ele.closest('form'));
		var formData = new FormData($form[0]);
		$.ajax({
			url    : '<?= \yii\helpers\Url::toRoute(['/ppic/proforma/setRandomTemplate']); ?>?jenis_produk=<?= $jenis_produk ?>',
			type   : 'POST',
			data   : { params:$("#form-setrandomtemplate").serialize() },
			success: function (data) {
				if(data.html){
					$('#modal-setrandomtemplate').modal('hide');
					$('#place-notfound').remove();
					$('#place-container').append(data.html);
					$(".tooltips").tooltip({ delay: 50 });
					reordercontainer();
					$(".table-contrainer").each(function(){
						if( $(this).find('.qtyperuk').length == 0 ){
							$(this).find('input[name*="[pcs]"]').removeAttr('disabled');
						}
						reordertablebundle($(this).attr("id"));
						total();
					});
				}
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
    }
    return false;
}

function inputContainerRandom(ele){
	var has_error = 0;
	var packinglist_id = <?= $packinglist_id ?>;
	$('#modal-setrandomtemplate').find('input.form-control').each(function(){
		$(this).val( unformatNumber($(this).val()) );
		if( unformatNumber( $(this).val() ) <= 0 ){
			has_error = has_error + 1;
			$(this).addClass("error-tb-detail");
		}else{
			$(this).removeClass("error-tb-detail");
		}
	});
	if(has_error === 0){
		var formData = $("#form-setrandomtemplate").serialize();
		var formData2 = $("#form-transaksi").serialize();
		openModal('<?= \yii\helpers\Url::toRoute('/ppic/proforma/InputContainerRandom') ?>?packinglist_id='+packinglist_id+'&jenis_produk=<?= $jenis_produk ?>&tipe=new&'+formData+"&"+formData2,'modal-inputrandom','90%');
    }
    return false;
}
</script>