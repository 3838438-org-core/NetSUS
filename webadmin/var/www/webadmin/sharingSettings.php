<?php

include "inc/config.php";
include "inc/auth.php";
include "inc/functions.php";

$title = "File Sharing";

include "inc/header.php";

// Helper Functions
function shareExec($cmd) {
	return shell_exec("sudo /bin/sh scripts/shareHelper.sh ".escapeshellcmd($cmd)." 2>&1");
}

$netboot_running = (trim(shareExec("getdhcpstatus")) === "true");

$smb_running = (trim(shareExec("getsmbstatus")) === "true");
$smb_conns = trim(shareExec("smbconns"));

$afp_running = (trim(shareExec("getafpstatus")) === "true");
$afp_conns = trim(shareExec("afpconns"));
?>
			<link rel="stylesheet" href="theme/awesome-bootstrap-checkbox.css"/>
			<link rel="stylesheet" href="theme/bootstrap-toggle.css">

			<script type="text/javascript" src="scripts/toggle/bootstrap-toggle.min.js"></script>

			<script type="text/javascript">
				var netboot = '<?php echo $conf->getSetting("netboot"); ?>';

				function toggleService() {
					if ($('#sharingenabled').prop('checked')) {
						$('#sharing').removeClass('hidden');
						$('#smbstatus').prop('disabled', false);
						if (netboot == 'disabled') {
							$('#afpstatus').prop('disabled', false);
						}
						ajaxPost('sharingCtl.php', 'service=enable');
						if ($('#smbstatus').prop('checked') == false) {
							var smbstatus = document.getElementById('smbstatus');
							$('#smbstatus').prop('checked', true);
							toggleSMB(smbstatus);
						}
					} else {
						var smb_users = parseInt(ajaxPost('sharingCtl.php', 'smbconns'));
						var afp_users = 0;
						if (netboot == 'disabled') {
							var afp_users = parseInt(ajaxPost('sharingCtl.php', 'afpconns'));
						}
						if (smb_users + afp_users == 0) {
							disableSharing();
						} else {
							$('#sharingenabled').bootstrapToggle('on');
							if (smb_users + afp_users == 1) {
								message = 'is 1 user';
							} else {
								message = 'are ' + (smb_users + afp_users) + ' users';
							}
							$('#sharing-message').text(message);
							$('#sharing-warning').modal('show');
						}
					}
				}

				function disableSharing() {
					$('#sharing').addClass('hidden');
					$('#smbstatus').prop('disabled', true);
					$('#afpstatus').prop('disabled', true);
					ajaxPost('sharingCtl.php', 'service=disable');
					ajaxPost('sharingCtl.php', 'smb=disable');
					$('#smbstatus').prop('checked', false);
					$('#smb_conns').text('File Sharing: Off');
					if (netboot == 'disabled') {
						ajaxPost('sharingCtl.php', 'afp=disable');
						$('#afpstatus').prop('checked', false);
						$('#afp_conns').text('File Sharing: Off');
					}
				}

				function toggleDashboard() {
					if ($('#sharingdashboard').prop('checked')) {
						ajaxPost('sharingCtl.php', 'dashboard=true');
					} else {
						ajaxPost('sharingCtl.php', 'dashboard=false');
					}
				}

				function toggleSMB(element) {
					var smb_users = parseInt(ajaxPost('sharingCtl.php', 'smbconns'));
					if (element.checked) {
						ajaxPost('sharingCtl.php', 'smb=enable');
						$('#smb_conns').text('Number of users connected: ' + smb_users);
					} else {
						if (smb_users > 0) {
							if (smb_users == 1) {
								message = 'is 1 user';
							} else {
								message = 'are ' + smb_users + ' users';
							}
							$('#smb-message').text(message);
							$('#smb-warning').modal('show');
							element.checked = true;
						} else {
							ajaxPost('sharingCtl.php', 'smb=disable');
							$('#smb_conns').text('File Sharing: Off');
						}
					}
				}

				function toggleAFP(element) {
					var afp_users = parseInt(ajaxPost('sharingCtl.php', 'afpconns'));
					if (element.checked) {
						ajaxPost('sharingCtl.php', 'afp=enable');
						$('#afp_conns').text('Number of users connected: ' + afp_users);
					} else {
						if (afp_users > 0) {
							if (afp_users == 1) {
								message = 'is 1 user';
							} else {
								message = 'are ' + afp_users + ' users';
							}
							$('#afp-message').text(message);
							$('#afp-warning').modal('show');
							element.checked = true;
						} else {
							ajaxPost('sharingCtl.php', 'afp=disable');
							$('#afp_conns').text('File Sharing: Off');
						}
					}
				}
			</script>

			<nav id="nav-title" class="navbar navbar-default navbar-fixed-top">
				<div style="padding: 19px 20px 1px;">
					<div class="description"><a href="settings.php">Settings</a> <span class="glyphicon glyphicon-chevron-right"></span> <span class="text-muted">Services</span> <span class="glyphicon glyphicon-chevron-right"></span></div>
					<div class="row">
						<div class="col-xs-10"> 
							<h2>File Sharing</h2>
						</div>
						<div class="col-xs-2 text-right"> 
							<input type="checkbox" id="sharingenabled" <?php echo ($conf->getSetting("sharing") == "enabled" ? "checked" : ""); ?> data-toggle="toggle" onChange="toggleService();">
						</div>
					</div>
				</div>
			</nav>

			<div style="padding: 70px 20px 1px; background-color: #f9f9f9;">
				<div class="checkbox checkbox-primary">
					<input name="sharingdashboard" id="sharingdashboard" class="styled" type="checkbox" value="true" onChange="toggleDashboard();" <?php echo ($conf->getSetting("showsharing") == "false" ? "" : "checked"); ?>>
					<label><strong>Show in Dashboard</strong><br><span style="font-size: 75%; color: #777;">Display service status in the NetSUS dashboard.</span></label>
				</div>
			</div>

			<hr>

			<div style="padding: 1px 20px;">
				<div class="checkbox checkbox-primary" style="padding-top: 6px">
					<input name="smbstatus" id="smbstatus" class="styled" type="checkbox" value="true" onChange="toggleSMB(this);" <?php echo ($smb_running ? "checked" : ""); ?> <?php echo ($conf->getSetting("sharing") == "enabled" ? "" : "disabled"); ?>>
					<label><strong>Share files and folders using SMB</strong><br><span id="smb_conns" style="font-size: 75%; color: #777;"><?php echo ($smb_running ? "Number of users connected: ".$smb_conns : "SMB Sharing: Off"); ?></span></label>
				</div>
			</div>

			<hr>

			<div style="padding: 7px 20px 1px; background-color: #f9f9f9;">
				<div id="afp_info" style="margin-top: 9px; margin-bottom: 17px;" class="panel panel-primary <?php echo ($conf->getSetting("netboot") == "enabled" ? "" : "hidden"); ?>">
					<div class="panel-body">
						<div class="text-muted"><span class="text-info glyphicon glyphicon-info-sign" style="padding-right: 12px;"></span>AFP is required for the NetBoot service.</div>
					</div>
				</div>

				<div class="checkbox checkbox-primary"">
					<input name="afpstatus" id="afpstatus" class="styled" type="checkbox" value="true" onChange="toggleAFP(this);" <?php echo ($afp_running ? "checked" : ""); ?> <?php echo ($conf->getSetting("sharing") == "enabled" && $conf->getSetting("netboot") != "enabled" ? "" : "disabled"); ?>>
					<label><strong>Share files and folders using AFP</strong><br><span id="afp_conns" style="font-size: 75%; color: #777;"><?php echo ($afp_running ? "Number of users connected: ".$afp_conns : "AFP Sharing: Off"); ?></span></label>
				</div>
			</div>

			<hr>

			<!-- Sharing Warning Modal -->
			<div class="modal fade" id="sharing-warning" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Disable File Sharing</h3>
						</div>
						<div class="modal-body">
							<div style="padding: 8px 0px;">Are you sure you want to disable File Sharing?</div>
							<div class="text-muted" style="padding: 8px 0px;"><span class="glyphicon glyphicon-exclamation-sign"></span> There <span id="sharing-message">users</span> connected to this server.</div>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default btn-sm pull-left">Cancel</button>
							<button type="button" data-dismiss="modal" class="btn btn-danger btn-sm pull-right" onClick="disableSharing(); $('#sharingenabled').bootstrapToggle('off');">Disable</button>
						</div>
					</div>
				</div>
			</div>
			<!-- /#modal -->

			<!-- SMB Warning Modal -->
			<div class="modal fade" id="smb-warning" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Disable SMB</h3>
						</div>
						<div class="modal-body">
							<div style="padding: 8px 0px;">Are you sure you want to disable SMB?</div>
							<div class="text-muted" style="padding: 8px 0px;"><span class="glyphicon glyphicon-exclamation-sign"></span> There <span id="smb-message">users</span> connected to this server.</div>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default btn-sm pull-left">Cancel</button>
							<button type="button" data-dismiss="modal" class="btn btn-danger btn-sm pull-right" onClick="ajaxPost('sharingCtl.php', 'smb=disable'); $('#smbstatus').prop('checked', false); $('#smb_conns').text('File Sharing: Off');">Disable</button>
						</div>
					</div>
				</div>
			</div>
			<!-- /#modal -->

			<!-- AFP Warning Modal -->
			<div class="modal fade" id="afp-warning" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Disable AFP</h3>
						</div>
						<div class="modal-body">
							<div style="padding: 8px 0px;">Are you sure you want to disable AFP?</div>
							<div class="text-muted" style="padding: 8px 0px;"><span class="glyphicon glyphicon-exclamation-sign"></span> There <span id="afp-message">users</span> connected to this server.</div>
						</div>
						<div class="modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default btn-sm pull-left">Cancel</button>
							<button type="button" data-dismiss="modal" class="btn btn-danger btn-sm pull-right" onClick="ajaxPost('sharingCtl.php', 'afp=disable'); $('#afpstatus').prop('checked', false); $('#afp_conns').text('File Sharing: Off');">Disable</button>
						</div>
					</div>
				</div>
			</div>
			<!-- /#modal -->
<?php include "inc/footer.php"; ?>