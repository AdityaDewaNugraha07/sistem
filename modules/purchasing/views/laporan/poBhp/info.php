<?php 
/*
pastikan div id modal adalah id yang dipanggil di index.php
*/
?>
<div class="modal fade" id="modal-pobhp-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Info Penawaran'); ?> <?php echo $t_spo->spo_kode;?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <tr>
                            <th class='text-center'>No. Penawaran</th>
                            <th class='text-center'>Tgl. Penawaran</th>
                            <th class='text-center'>Nama Supplier</th>
                            <th class='text-center'>Nama Barang</th>
                            <th class='text-center'>Harga</th> 
                            <th class='text-center'>Keterangan</th> 
                        </tr>
                        <?php // KONTEN MODAL ?>
                        <?php
                        foreach ($map_penawaran_bhp as $key) {
                            $penawaran_bhp_id = $key['penawaran_bhp_id'];
                            $t_penawaran_bhp = \app\models\TPenawaranBhp::find()->where('penawaran_bhp_id = '.$penawaran_bhp_id.'')->one();
                                $kode = $t_penawaran_bhp->kode;
                                $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($t_penawaran_bhp->tanggal);
                                $suplier_id = $t_penawaran_bhp->suplier_id;
                                $bhp_id = $t_penawaran_bhp->bhp_id;
                                $harga_satuan = \app\components\DeltaFormatter::formatNumberForAllUser($t_penawaran_bhp->harga_satuan);
                                $keterangan = $t_penawaran_bhp->keterangan;
                            $suplier_nm = Yii::$app->db->createCommand('select suplier_nm from m_suplier where suplier_id = '.$suplier_id)->queryScalar();
                            $bhp_nm = Yii::$app->db->createCommand('select bhp_nm from m_brg_bhp where bhp_id = '.$bhp_id)->queryScalar();
                            $suplier_id == $supplier_id ? $bgColor = '#F5FCC9' : $bgColor = '';
                        ?>
                        <tr>
                            <td style="background-color: <?php echo $bgColor;?>;"><?php echo $kode;?></td>
                            <td style="background-color: <?php echo $bgColor;?>;"><?php echo $tanggal;?></td>
                            <td style="background-color: <?php echo $bgColor;?>;"><?php echo $suplier_nm;?></td>
                            <td style="background-color: <?php echo $bgColor;?>;"><?php echo $bhp_nm;?></td>
                            <td style="background-color: <?php echo $bgColor;?>;" class='text-right'><?php echo $harga_satuan;?></td>
                            <td style="background-color: <?php echo $bgColor;?>;" class='text-right'><?php echo $keterangan;?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <?php /* EO KONTEN MODAL */ ?>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
