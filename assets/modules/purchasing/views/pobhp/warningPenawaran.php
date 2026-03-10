<div class="modal fade" id="modal-warning-penawaran" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-globalconfirm" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">Warning!! ada item yang belum ada penawarannya</h4>
            </div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<h5>
							<span id="place-pesanwarning"></span><br>
							<b>Apakah anda akan tetap melanjutkan walaupun masih ada item yang belum ada penawarannya ??</b>
						</h5>
					</div>
				</div>
			</div>
            <div class="modal-footer text-align-center">
                <?= yii\helpers\Html::button(Yii::t('app', 'Yes'),['class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>"yes()"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'No'),['class'=>'btn red btn-outline ciptana-spin-btn','data-dismiss'=>'modal']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
asd();
function asd(){
	var dfg = "";
	$('#table-detail > tbody > tr').each(function(i){
		if( ($(this).find("#tbl-penawaran").length) == 0){
			dfg += "&nbsp; - "+$.trim( $(this).find("input[name*='[sppd_id]']").parents('td').contents().filter(function() {
					return this.nodeType == Node.TEXT_NODE;
				  }).text() );
			dfg += "<br>";
		}
	});
	$("#place-pesanwarning").html(dfg);
}
function yes(){
	$("#allowpenawaran").val("1");
	save();
}
</script>