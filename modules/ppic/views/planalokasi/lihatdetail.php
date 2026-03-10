
<div class="modal fade" id="modal-lihatdetail" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Stok Log Plan Alokasi <b>' . $jenis_alokasi .'</b> dengan Jenis Kayu <u>'.$kayu.'</u>'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-scrollable" style="padding-left: 10px; padding-right: 10px;">
                        <table id="table-detail" class="table table-striped table-bordered table-advance table-hover" style="width: 90%">
							<thead>
								<tr>
                                    <th rowspan="2">No.</th>
                                    <th colspan="4">Nomor</th>
                                    <th colspan="3">Ukuran</th>
                                    <th rowspan="2">Pot</th>
                                    <th colspan="4">⌀ (cm)</th>
                                    <th colspan="3">Cacat (cm)</th>
                                    <th rowspan="2">Status FSC</th>
                                </tr>
                                <tr>
                                    <th>Barcode</th>
                                    <th>Batang</th>
                                    <th>Lapangan</th>
                                    <th>Grade</th>
                                    <th>P<br>(m)</th>
                                    <th>⌀ Rata<br>(cm)</th>
                                    <th>V<br>(m<sup>3</sup>)</th>
                                    <th>Ujung 1</th>
                                    <th>Ujung 2</th>
                                    <th>Pangkal 1</th>
                                    <th>Pangkal 2</th>
                                    <th>P</th>
                                    <th>Gb</th>
                                    <th>Gr</th>
                                </tr>
							</thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" style="text-align:right">Total Per Page:</th>
                                    <th colspan="1" style="text-align:right"></th>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align:right;" class="td-kecil">Total All Page:</th>
                                    <th colspan="1" style="text-align:right;" class="td-kecil"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
    dtDetail();
", yii\web\View::POS_READY); ?>
<script>
function dtDetail(){
    var jenis_alokasi = '<?= $jenis_alokasi; ?>';
    var kayu_id = <?= $kayu_id; ?>;
    var dt_table =  $('#table-detail').dataTable({
		pageLength: 50,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/lihatdetail','jenis_alokasi'=>'']) ?>'+jenis_alokasi+'&kayu_id='+kayu_id,
			data:{
				dt: 'table-detail',
			} 
		},
        columnDefs: [
			{	targets: 0, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
            { 	targets: 1, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return full[0];
                }
            },
            { 	targets: 2, class:'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
					return full[1];
                }
            },
            { 	targets: 3, class:'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
					return full[2];
                }
            },
            { 	targets: 4, class:'td-kecil text-align-center', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[3];
                }
            },
            { 	targets: 5, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[4];
                }
            },
            { 	targets: 6, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[5];
                }
            },
            { 	targets: 7, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[6];
                }
            },
            { 	targets: 8, class:'td-kecil text-align-center', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[7]?full[7]:'-';
                }
            },
            { 	targets: 9, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[8];
                }
            },
            { 	targets: 10, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[9];
                }
            },
            { 	targets: 11, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[10];
                }
            },
            { 	targets: 12, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[11];
                } 
            },
            { 	targets: 13, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[12];
                }
            },
            { 	targets: 14, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[13];
                }
            },
            { 	targets: 15, class:'td-kecil text-align-right', searchable: false,
                render: function ( data, type, full, meta ) {
					return full[14];
                }
            },
            { 	targets: 16, class:'td-kecil text-align-center', searchable: false,
                render: function ( data, type, full, meta ) {
                    if(full[15] == 'true'){
                        var ret = 'FSC 100%';
                    } else {
                        var ret = 'Non FSC';
                    }
					return ret;
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
            $('.dataTables_moreaction a').each(function() {
                var btn = $(this).attr('onclick');
                if (btn.includes('EXCEL') || btn.includes('PRINT') || btn.includes('PDF')) {
                    $(this).hide();
                }
            });
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
        footerCallback: function(row, data, start, end, display) {
			var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                         i : 0;
            };

            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(pageTotal.toFixed(2).toLocaleString())}`);
            $.ajax({
                url: "<?= yii\helpers\Url::toRoute('/ppic/planalokasi/alokasiTotal') ?>?jenis_alokasi="+jenis_alokasi+"&kayu_id="+kayu_id,
                success: res => {
                    $('tr:eq(1) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).total.toFixed(2).toLocaleString())}`)  
                }                                                                                                                                                                                                                                                                                                                                                                                                             
            })
		},
		// "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}
</script>