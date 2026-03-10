<div class="modal fade" id="modal-setalias" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				
                <h4 class="modal-title"><?= Yii::t('app', 'Set Alias '); ?><?php // echo ($jenis_produk=="Moulding")?"Profil":"Glue" ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-setalias',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    
                    <div class="col-md-12">
                        <h5 style="font-size: 15px; margin-bottom: 3px;">&nbsp; &nbsp; Grade : </h5>
                        <div id="grade-place"></div>
                    </div>
                    <div class="col-md-12">
                        <h5 style="font-size: 15px; margin-bottom: 3px;">&nbsp; &nbsp; Jenis Kayu : </h5>
                        <div id="jenis_kayu-place"></div>
                    </div>
                    <div class="col-md-12">
                        <h5 style="font-size: 15px; margin-bottom: 3px;" id="glueprofil-label"></h5>
                        <div id="glueprofil-place"></div>
                    </div>
                </div>
				<br><br>
				<center><a class="btn btn-outline btn-xs blue-hoki" onclick="sortAlias()">Sort</a></center>
            </div>
			<?= yii\helpers\Html::hiddenInput('sorting','') ?>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Set'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save(this,"$(\'#modal-setalias\').modal(\'hide\');");'
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
    formconfig();
	loadAlias();
", yii\web\View::POS_READY); ?>
<script type="text/javascript">
function loadAlias(){
	var packinglist_id = "<?=$modPackinglist->packinglist_id ?>";
	var sorting = $("input[name='sorting']").val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/exim/packinglist/loadAlias']); ?>',
        type   : 'POST',
        data   : {packinglist_id:packinglist_id,sorting:sorting},
        success: function (data) {
			if(data.grade){
				$("#grade-place").html(data.grade);
			}
			if(data.jenis_kayu){
				$("#jenis_kayu-place").html(data.jenis_kayu);
			}
			if(data.glueprofil){
				$("#glueprofil-place").html(data.glueprofil);
			}
			if(data.glueprofil_label){
                var glueprofil_label = data.glueprofil_label.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
				$("#glueprofil-label").html("&nbsp; &nbsp; "+glueprofil_label+" : ");
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function sortAlias(){
	var sorting = $("input[name='sorting']").val();
	if(sorting == 'desc'){
		$("input[name='sorting']").val('asc');
	}else{
		$("input[name='sorting']").val('desc');
	}
	loadAlias();
}

function save(ele,callback){
    var $form = $('#form-setalias');
    if(formrequiredvalidate($form)){
        if(validatingDetail()){
            submitformajax(ele,callback)
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
	$("input[type='text'][name*='[grade]']:enabled").each(function(){
		var field1 = $(this);
		if(!field1.val()){
			$(this).parents('.col-md-6').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).parents('.col-md-6').removeClass('error-tb-detail');
		}
	});
	$("input[type='text'][name*='[jenis_kayu]']:enabled").each(function(){
		var field1 = $(this);
		if(!field1.val()){
			$(this).parents('.col-md-6').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).parents('.col-md-6').removeClass('error-tb-detail');
		}
	});
	$("input[type='text'][name*='[glue]']:enabled").each(function(){
		var field1 = $(this);
		if(!field1.val()){
			$(this).parents('.col-md-6').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).parents('.col-md-6').removeClass('error-tb-detail');
		}
	});
	$("input[type='text'][name*='[profil]']:enabled").each(function(){
		var field1 = $(this);
		if(!field1.val()){
			$(this).parents('.col-md-6').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).parents('.col-md-6').removeClass('error-tb-detail');
		}
	});
	
    if(has_error === 0){
        return true;
    }
    return false;
}
</script>