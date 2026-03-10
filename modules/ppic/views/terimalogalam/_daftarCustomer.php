<?php 
use yii\helpers\Url;
use app\assets\DatatableAsset;

DatatableAsset::register($this); 
?>
<div class="modal fade" id="modal-daftar-customer" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Customer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-daftar-customer">
							<thead>
								<tr>
									<th>No</th>
									<th>Kode Customer</th>
									<th>Atas Nama</th>
									<th>Perusahaan</th>
									<th>Alamat</th>
									<th></th>
								</tr>
							</thead>
						</table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs("init()") ?>
<script>
function init(){
    $('#table-daftar-customer').dataTable({
        ajax: { 
            url: '<?= Url::toRoute('/ppic/terimalogalam/daftarcustomer') ?>',data:{dt: 'modal-daftar-customer'} 
        },
        columnDefs: [
            {	targets: 3, 
                render: function(data, type, row) {
                    if(!data) {
                        return `<i>Perorangan</i>`;
                    }

                    return data;
                }
            },	
            {   targets: 4,
                render: function(data, type, row) {
                    if(!data) {
                        return row[5];
                    }

                    return data;
                }
            },
            {   targets: 5,
                render: function(data, type, row) {
                    let customer = row[3] || row[2];
                    let alamat   = row[5] || row[4];
                    return `<button type="button" class="btn btn-xs btn-default" onclick="pickCustomer('${customer}', '${alamat}')"><i class="fa fa-plus"></i></button>`;
                }
            }
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        "rowCallback": function(row, data, index) {
            var dt = this.api().page.info();
            var start = dt.start;
            var counter = start + index + 1;
            
            $('td', row).eq(0).html(counter); // Mengganti isi kolom nomor urut dengan nilai counter
        }
    });
}
</script>