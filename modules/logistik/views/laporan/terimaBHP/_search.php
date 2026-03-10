<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet light bordered form-search">
							<div class="portlet-title">
								<div class="tools panel-cari">
									<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
									<span style=""> &nbsp;Filter Pencarian</span>
								</div>
							</div>
							<div class="portlet-body">
								<form id="form-search" class="form-horizontal" method="post">
									<input type="hidden" name="_csrf" value="1xIDAvqw-W3lzP8nCePuFHNI48vd9jPglaI5OVuFvqmYYg76f_JM_5DstbTHL4hOK9DyIBb4oUSrJ3qphJs_JA==">
									<div class="modal-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">Periode</label>
													<div class="col-md-6">
														<span class="input-group-btn" style="width: 50%">
															<div class="form-group field-tsppdetail-tgl_awal">
																<div class="col-md-6">
																	<div class="input-group input-small date date-picker bs-datetime"><input type="text" id="tsppdetail-tgl_awal" class="form-control" name="tgl_awal" value="<?php echo $tgl_awal;?>" readonly="readonly"> <span class="input-group-addon">
																			<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div>
																	<span class="help-block"></span>
																</div>
															</div>
														</span>
														<span class="input-group-addon textarea-addon" style="width: 10%; background-color: #fff; border: 0;"> sd </span>
														<span class="input-group-btn" style="width: 50%">
															<div class="form-group field-tsppdetail-tgl_akhir">
																<div class="col-md-6">
																	<div class="input-group input-small date date-picker bs-datetime"><input type="text" id="tsppdetail-tgl_akhir" class="form-control" name="tgl_akhir" value="<?php echo $tgl_akhir;?>" readonly="readonly"> <span class="input-group-addon">
																		<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div>
																	<span class="help-block"></span>
																</div>
															</div>
														</span>
														<span class="help-block"></span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="col-md-9">
													<input type="text" id="cari_kode" name="cari_kode" class="form-control" placeholder="Cari berdasarkan kode TBP">
												</div>
												<div class="col-md-3 pull-right" style="position: relative;">
													<button type="submit" class="btn hijau btn-outline ciptana-spin-btn pull-right loading" name="search-laporan">Search</button>
												</div>	
											</div>
										</div>
									</div>
									<input type="hidden" name="sort[col]"> <input type="hidden" name="sort[dir]">
								</form>
							</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>