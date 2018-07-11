<?php

include "inc/config.php";
include "inc/auth.php";
include "inc/functions.php";

$title = "Storage";

include "inc/header.php";

function formatSize($size, $precision = 1) {
    $base = log($size, 1024);
    $suffixes = array('B', 'kB', 'MB', 'GB', 'TB');   
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

$df_result_str = trim(suExec("diskusage"));
$df_result = explode(":", $df_result_str);
$df_total = formatSize($df_result[0]*1024);
$df_used = formatSize($df_result[1]*1024);
$df_used_percent = ceil(100*$df_result[1]/$df_result[0]);
$df_free = formatSize($df_result[2]*1024);
$df_reserved = formatSize(($df_result[0]-$df_result[1]-$df_result[2])*1024);
$df_reserved_percent = ceil(100*($df_result[0]-$df_result[1]-$df_result[2])/$df_result[0]);

$lv_result = trim(suExec("resizeStatus"));
if (strpos($lv_result, 'ERROR') === false) {
	$lv_layout = explode(":", $lv_result);
	$lv_total = formatSize($lv_layout[0]);
	$lv_allocated = formatSize($lv_layout[1]);
	$lv_available = formatSize($lv_layout[2]);
	$lv_percent = round(100*$lv_layout[1]/$lv_layout[0], 0) or 100;
} else {
	$lv_percent = 100;
}
?>
			<style>
				.progress-bar-light {
					background-color: #558fc0;
				}
				.progress-bar-warning-light {
					background-color: #ebbb77;
				}
				.progress-bar-danger-light {
					background-color: #d2746d;
				}
			</style>

			<div class="description"><a href="settings.php">Settings</a> <span class="glyphicon glyphicon-chevron-right"></span> <span class="text-muted">System</span> <span class="glyphicon glyphicon-chevron-right"></span></div>
			<h2>Storage</h2>

			<div class="row">
				<div class="col-xs-12"> 

					<hr>

					<div style="padding: 12px 0px;" class="description">STORAGE DESCRIPTION</div>

					<h5><strong>File System</strong></h5>

					<div class="row">
						<div class="col-xs-4">
							<div><span class="text-muted">Used:</span> <?php echo $df_used; ?></div>
						</div>
						<div class="col-xs-4">
							<div class="text-center"><span class="text-muted">Reserved:</span> <?php echo $df_reserved; ?></div>
						</div>
						<div class="col-xs-4">
							<div class="text-right"><span class="text-muted">Free:</span> <?php echo $df_free; ?></div>
						</div>
					</div>

					<br>

					<div class="progress">
						<div class="progress-bar <?php echo ($df_used_percent >= 80 ? ($df_used_percent >= 90 ? "progress-bar-danger" : "progress-bar-warning") : ""); ?>" role="progressbar" aria-valuenow="<?php echo $df_used_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $df_used_percent; ?>%;"></div>
						<div class="progress-bar <?php echo ($df_used_percent >= 80 ? ($df_used_percent >= 90 ? "progress-bar-danger-light" : "progress-bar-warning-light") : "progress-bar-light"); ?>" role="progressbar" aria-valuenow="<?php echo $df_reserved_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $df_reserved_percent; ?>%;"></div>
					</div>

					<hr>
					<br>

					<h5><strong>Logical Volume</strong></h5>
<?php if (strpos($lv_result,'ERROR') !== false) { ?>
					<div class="text-muted">
						<span class="glyphicon glyphicon-exclamation-sign"></span> <?php echo str_replace('ERROR: ', '', $lv_result); ?>
					</div>
<?php } else { ?>
					<div class="row">
						<div class="col-xs-4">
							<div><span class="text-muted">Total:</span> <?php echo $lv_total; ?></div>
						</div>
						<div class="col-xs-4">
							<div class="text-center"><span class="text-muted">Allocated:</span> <?php echo $lv_allocated; ?></div>
						</div>
						<div class="col-xs-4">
							<div class="text-right"><span class="text-muted">Available:</span> <?php echo $lv_available; ?></div>
						</div>
					</div>

					<br>

					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $lv_percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $lv_percent; ?>%;"></div>
					</div>

					<div class="text-left">
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#confirmExpand" <?php echo ($lv_percent < 100 ? "" : "disabled"); ?>>Expand</button>
					</div>

					<div class="modal fade" id="confirmExpand" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h3 class="modal-title">Expand Volume</h3>
								</div>
								<div class="modal-body">
									<div class="text-muted">Are you sure you want to expand the volume? The system will require a restart.</div>
								</div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-default btn-sm pull-left">Cancel</button>
									<button type="button" class="btn btn-primary btn-sm" onClick="document.location.href='storageCtl.php?resize=true';">Continue</button>
								</div>
							</div>
						</div>
					</div>
<?php } ?>
				</div> <!-- /.col -->
			</div> <!-- /.row -->
<?php include "inc/footer.php"; ?>