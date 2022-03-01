<?php

namespace Jara\App\Views;

use Jara\App\Configs\Config;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Setting;

class EmailView
{
	public function order_email($order, $secure = true)
	{


		$map = function ($a, $f) {
			return join("\n", array_map($f, $a));
		};

		$app_url = Config::$app_url;
		$phone = Setting::get('settings_phone')->value;
		$email = Setting::get('settings_email')->value;
		$menu = Setting::get('settings_menu_default_view')->value;
		$address = json_decode(Setting::get('settings_address')->value);
		// $currency = htmlspecialchars(Setting::get('settings_currency_symbol')->value);
		$currency = '&euro;';
		$logo = $app_url . '/uploads/images/' .  Setting::get('theme_header_logo_light')->value;

		$content = json_decode($order->content);
		$order_id = $order->id;
		$guid = $order->guid;

		if ($secure) {
			if (!isset($_GET['key'])) {
				http_response_code(404);
				die();
			} elseif ($_GET['key'] != $guid) {
				http_response_code(404);
				die();
			}
		}

		$app_name = Config::$app_name;
		$color = Config::$app_theme_color;
		$website = 'https://sushitown.fr';

		$image = function ($id) use ($app_url) {
			return $app_url . '/uploads/images/' . json_decode(Posts::get_meta($id, 'image')->value)->url;
		};

		foreach ($content->items as $value) {
			$value->image = $image($value->id);
		}

		// html header
		header("Content-Type: text/html; charset=UTF-8");

		return <<<HTML
			<!DOCTYPE html>
			<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

			<head>
				<meta charset="utf-8"> <!-- utf-8 works for most cases -->
				<meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
				<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
				<meta name="x-apple-disable-message-reformatting"> <!-- Disable auto-scale in iOS 10 Mail entirely -->
				<title>New Order ($order_id)</title> <!-- The title tag shows in email notifications, like Android 4.4. -->

				<link href="https://fonts.googleapis.com/css?family=Work+Sans:200,300,400,500,600,700" rel="stylesheet">

				<!-- CSS Reset : BEGIN -->
				<style>
					/* What it does: Remove spaces around the email design added by some email clients. */
					/* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
					html,
					body {
						margin: 0 auto !important;
						padding: 0 !important;
						height: 100% !important;
						width: 100% !important;
						background: #f1f1f1;
					}

					/* What it does: Stops email clients resizing small text. */
					* {
						-ms-text-size-adjust: 100%;
						-webkit-text-size-adjust: 100%;
					}

					/* What it does: Centers email on Android 4.4 */
					div[style*="margin: 16px 0"] {
						margin: 0 !important;
					}

					/* What it does: Stops Outlook from adding extra spacing to tables. */
					table,
					td {
						mso-table-lspace: 0pt !important;
						mso-table-rspace: 0pt !important;
					}

					/* What it does: Fixes webkit padding issue. */
					table {
						border-spacing: 0 !important;
						border-collapse: collapse !important;
						table-layout: fixed !important;
						margin: 0 auto !important;
					}

					/* What it does: Uses a better rendering method when resizing images in IE. */
					img {
						-ms-interpolation-mode: bicubic;
					}

					/* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
					a {
						text-decoration: none;
					}

					/* What it does: A work-around for email clients meddling in triggered links. */
					*[x-apple-data-detectors],
					/* iOS */
					.unstyle-auto-detected-links *,
					.aBn {
						border-bottom: 0 !important;
						cursor: default !important;
						color: inherit !important;
						text-decoration: none !important;
						font-size: inherit !important;
						font-family: inherit !important;
						font-weight: inherit !important;
						line-height: inherit !important;
					}

					/* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
					.a6S {
						display: none !important;
						opacity: 0.01 !important;
					}

					/* What it does: Prevents Gmail from changing the text color in conversation threads. */
					.im {
						color: inherit !important;
					}

					/* If the above doesn't work, add a .g-img class to any image in question. */
					img.g-img+div {
						display: none !important;
					}

					/* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
					/* Create one of these media queries for each additional viewport size you'd like to fix */

					/* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
					@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
						u~div .email-container {
							min-width: 320px !important;
						}
					}

					/* iPhone 6, 6S, 7, 8, and X */
					@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
						u~div .email-container {
							min-width: 375px !important;
						}
					}

					/* iPhone 6+, 7+, and 8+ */
					@media only screen and (min-device-width: 414px) {
						u~div .email-container {
							min-width: 414px !important;
						}
					}
				</style>

				<!-- CSS Reset : END -->

				<!-- Progressive Enhancements : BEGIN -->
				<style>
					.primary {
						background: $color;
					}

					.bg_white {
						background: #ffffff;
					}

					.bg_light {
						background: #ffffff;
					}

					.bg_black {
						background: #000000;
					}

					.bg_dark {
						background: rgba(0, 0, 0, .8);
					}

					.email-section {
						padding: 2.5em;
					}

					/*BUTTON*/
					.btn {
						padding: 10px 15px;
						display: inline-block;
					}

					.btn.btn-primary {
						border-radius: 5px;
						background: $color;
						color: #ffffff;
					}

					.btn.btn-white {
						border-radius: 5px;
						background: #ffffff;
						color: #000000;
					}

					.btn.btn-white-outline {
						border-radius: 5px;
						background: transparent;
						border: 1px solid #fff;
						color: #fff;
					}

					.btn.btn-black-outline {
						border-radius: 0px;
						background: transparent;
						border: 2px solid #000;
						color: #000;
						font-weight: 700;
					}

					.btn-custom {
						color: rgba(0, 0, 0, .3);
						text-decoration: underline;
					}

					h1,
					h2,
					h3,
					h4,
					h5,
					h6 {
						font-family: 'Work Sans', sans-serif;
						color: #000000;
						margin-top: 0;
						font-weight: 400;
					}

					body {
						font-family: 'Work Sans', sans-serif;
						font-weight: 400;
						font-size: 15px;
						line-height: 1.8;
						color: rgba(0, 0, 0, .4);
					}

					a {
						color: $color;
					}

					table {}

					/*LOGO*/

					.logo h1 {
						margin: 0;
					}

					.logo h1 a {
						color: $color;
						font-size: 24px;
						font-weight: 700;
						font-family: 'Work Sans', sans-serif;
					}

					/*HERO*/
					.hero {
						position: relative;
						z-index: 0;
					}

					.hero .text {
						color: rgba(0, 0, 0, .3);
					}

					.hero .text h2 {
						color: #000;
						font-size: 34px;
						margin-bottom: 15px;
						font-weight: 300;
						line-height: 1.2;
					}

					.hero .text h3 {
						font-size: 24px;
						font-weight: 200;
					}

					.hero .text h2 span {
						font-weight: 600;
						color: #000;
					}


					/*PRODUCT*/
					.product-entry {
						display: flex;
						position: relative;
						float: left;
						padding-top: 10px;
						padding-bottom: 10px;
					}

					.product-entry .text {
						width: calc(100% - 125px);
						padding-left: 20px;
					}

					.product-entry .text h3 {
						margin-bottom: 0;
						padding-bottom: 0;
					}

					.product-entry .text p {
						margin-top: 0;
					}

					.product-entry img,
					.product-entry .text {
						float: left;
					}

					ul.social {
						padding: 0;
					}

					ul.social li {
						display: inline-block;
						margin-right: 10px;
					}

					/*FOOTER*/

					.footer {
						border-top: 1px solid rgba(0, 0, 0, .05);
						color: rgba(0, 0, 0, .5);
					}

					.footer .heading {
						color: #000;
						font-size: 20px;
					}

					.footer ul {
						margin: 0;
						padding: 0;
					}

					.footer ul li {
						list-style: none;
						margin-bottom: 10px;
					}

					.footer ul li a {
						color: rgba(0, 0, 0, 1);
					}


					@media screen and (max-width: 500px) {}
				</style>


			</head>

			<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
				<center style="width: 100%; background-color: #f1f1f1;">
					<div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
						&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
					</div>
					<div style="max-width: 600px; margin: 0 auto;" class="email-container">
						<!-- BEGIN BODY -->
						<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
							<tr>
								<td valign="top" class="bg_white" style="padding: 1em 2.5em 0 2.5em;">
									<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="logo" style="text-align: left;">
												<!-- <img src="$logo" width="80" height="80" alt="$app_name" title="$app_name" style="display:block" > -->
												<div style='display:block;height:80px;width:80px;background-image:url($logo);background-repeat:no-repeat;background-size:cover;'></div>
												<!-- <h1><a href="#">$app_name</a></h1> -->
											</td>
										</tr>
									</table>
								</td>
							</tr><!-- end tr -->
							<tr>
								<td valign="middle" class="hero bg_white" style="padding: 2em 0 2em 0;">
									<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td style="padding: 0 2.5em; text-align: left;">
												<div class="text">
													<h2>Your order has been received.</h2>
													<p>We have received your order successfully, we are processing your order as soon as possible. Thanks for ordering.</p>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr><!-- end tr -->
							<tr>
								<table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
										<th width="80%" style="text-align:left; padding: 0 2.5em; color: #000; padding-bottom: 20px">Item</th>
										<th width="20%" style="text-align:right; padding: 0 2.5em; color: #000; padding-bottom: 20px">Price</th>
									</tr>
									{$map($content->items, function ($item) use ($currency) {
			return "
			<tr style='border-bottom: 1px solid rgba(0,0,0,.05);'>
										<td valign='middle' width='80%' style='text-align:left; padding: 0 2.5em;'>
											<div class='product-entry'>
												<div style='min-width: 150px;max-width: 600px;min-height: 100px;max-height: 300px;display: block;background-image: url($item->image);background-repeat: no-repeat;background-size: cover;'></div>
												<div class='text'>
													<h3 style='min-width: 150px;'>$item->name x $item->quantity</h3>
													<span style='display:block;'>Options: $currency $item->option_price</span>
													<span style='display:block;'>Additions: $currency $item->addition_price</span>
												</div>
											</div>
										</td>
										<td valign='middle' width='20%' style='text-align:left; padding: 0 2.5em;'>
										
											<span class='price' style='color: #000; font-size: 20px;min-width: 150px;'>$currency $item->total</span>
										</td>
									</tr>
										";
		})}
									
									<tr>
										<td valign="middle" style="text-align:left; padding: 1em 2.5em;">
											<p><a href="$website" class="btn btn-primary">Visit Website</a></p>
										</td>
									</tr>
								</table>
							</tr><!-- end tr -->
							<!-- 1 Column Text + Button : END -->
						</table>
						<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="display:block;margin: auto;">
							<tr>
								<td valign="middle" class="bg_light footer email-section">
									<table>
											<td valign="top" width="50%" style="padding-top: 20px;">
												<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
													<tr>
														<td style="text-align: left; padding-left: 5px; padding-right: 5px;">
															<h3 class="heading">Contact Info</h3>
															<ul>
																<li><span class="text">$app_name, $address->street, $address->city, $address->contry</span></li>
																<li><span class="text"><a href="tel:$phone">$phone</a></span></a></li>
																<li><span class="text"><a href="mailto:$phone">$email</a></span></a></li>
															</ul>
														</td>
													</tr>
												</table>
											</td>
											<td valign="top" width="50%" style="padding-top: 20px;">
												<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
													<tr>
														<td style="text-align: left; padding-left: 10px;">
															<h3 class="heading">Useful Links</h3>
															<ul>
																<li><a href="$website">Home</a></li>
																<li><a href="$website/$menu">Menu</a></li>
															</ul>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr><!-- end: tr -->
							<tr>
								<td class="bg_white" style="text-align: center; width: 100%;">
									<p class="bg_white">Can not see this email? <a target="_blank" href="$app_url/email/$order_id/?key=$guid" style="color: rgba(0,0,0,.8);">Open in browser.</a></p>
								</td>
							</tr>
						</table>

					</div>
				</center>
			</body>

			</html>

			HTML;
	}
}
