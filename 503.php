<?php
/**
 * 503 page
 */

// include_once 'config.php';

// if (!function_exists('bs4_get_option')) {
// 	function bs4_get_option($dummy) { return false; }
// }

// if (!function_exists('get_theme_file_uri')) {
// 	function get_theme_file_uri($append) { $p = pathinfo($_SERVER['REQUEST_URI']); return $p['dirname'] . $append; }
// }

// if (!defined('DOTMIN')) define('DOTMIN', '.min');

global $upgrading;
if ((!isset($upgrading)) || ($upgrading === false) || ($upgrading <= today()))
 	$upgrading = mktime( date("H"), date("i")+10 );  // 10 min from now

$protocol = $_SERVER["SERVER_PROTOCOL"];
if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol ) $protocol = 'HTTP/1.0';
header( $protocol . ' 503 Service Temporarily Unavailable', true, 503 );
header( 'Status: 503 Service Temporarily Unavailable' );
header( 'Content-Type: text/html; charset=utf-8' );
header( 'Retry-After: ' . date('r', $upgrading) );
//** set up cache
header( 'Cache-Control: public' );
header( 'Expires: ' . date('r', $upgrading) );
header( 'vary: User-Agent');
header( 'ETag: "' . date('YmdHis', $upgrading) . '-wp-bs4"' );

$css_url = trim( bs4_get_option('bootstrap_css') );
if (empty($css_url))
	$css_url = get_theme_file_uri( '/vendor/bootstrap/css/bootstrap' . DOTMIN . '.css' );

$jqry_url = trim( bs4_get_option('jquery_js') );
if (empty($jqry_url))
	$jqry_url = get_theme_file_uri( '/vendor/jquery/js/jquery-' . JQUERY_VERSION . DOTMIN . '.js' );

$bsjs_url = trim( bs4_get_option('bootstrap_js') );
if (empty($bsjs_url))
	$bsjs_url = get_theme_file_uri( '/vendor/bootstrap/js/bootstrap' . DOTMIN . '.js' );

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Service Temporarily Unavailable</title>
	<link rel="stylesheet" href="<?= $css_url; ?>" type="text/css" id="bootstrap-css">
</head>
<body style="height:100%">

<div id="thisModal" class="modal fade" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Service Temporarily Unavailable</h4>
			</div>
			<div class="modal-body">
				<p>Temporarily unavailable for scheduled maintenance.</p>
			</div>
			<div class="modal-footer">
				<button id="thisReturn" type="button" class="btn btn-secondary">Back</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= $jqry_url ?>"></script>
<script type="text/javascript" src="<?= $bsjs_url ?>"></script>
<script type="text/javascript">
	$( document ).ready(function() {
		$('#thisReturn').click(function() {
			parent.history.back();
			return false;
		});
		$('#thisModal').modal('show');
	})(jQuery);
</script>

</body>
</html>
