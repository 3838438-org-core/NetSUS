<?php

session_start();

$noAuthURL="index.php";

if (!($_SESSION['isAuthUser'])) {

	echo "Not authorized - please log in";

} else {

	include "inc/config.php";
	include "inc/functions.php";
	
	if (isset($_POST['restart'])) {
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		// Destroy the session.
		session_destroy();
		// Finally, restart
		suExec("restart");
	}

	if (isset($_POST['shutdown'])) {
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		// Destroy the session.
		session_destroy();
		// Finally, shut down
		suExec("shutdown");
	}

	if (isset($_POST['disablegui'])) {
		$conf->setSetting("webadmingui", "Disabled");
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		// Finally, destroy the session.
		session_destroy();
	}

	if (isset($_POST['webadminuser'])) {
		if ($_SESSION['username'] == $conf->getSetting("webadminuser")) {
			$_SESSION['username'] = $_POST['webadminuser'];
		}
		$conf->setSetting("webadminuser", $_POST['webadminuser']);
		$conf->changedPass("webaccount");
	}

	if (isset($_POST['confirmold'])) {
		if (hash("sha256", $_POST['confirmold']) == $conf->getSetting("webadminpass")) {
			echo "true";
		} else {
			echo "false";
		}
	}

	if (isset($_POST['webadminpass'])) {
		$conf->setSetting("webadminpass", hash("sha256", $_POST['webadminpass']));
		$conf->changedPass("webaccount");
	}

	if (isset($_POST['ldapserver'])) {
		if ($_POST['ldapserver'] == "") {
			$conf->deleteSetting("ldapserver");
		} else {
			$conf->setSetting("ldapserver", $_POST['ldapserver']);
		}
	}

	if (isset($_POST['ldapdomain'])) {
		if ($_POST['ldapdomain'] == "") {
			$conf->deleteSetting("ldapdomain");
		} else {
			$conf->setSetting("ldapdomain", $_POST['ldapdomain']);
		}
	}

	if (isset($_POST['ldapbase'])) {
		if ($_POST['ldapbase'] == "") {
			$conf->deleteSetting("ldapbase");
		} else {
			$conf->setSetting("ldapbase", $_POST['ldapbase']);
		}
	}

}
?>