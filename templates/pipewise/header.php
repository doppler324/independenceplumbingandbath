<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

$isMain = $APPLICATION->GetCurPage(false) === '/';
// pages that draw their own heading (home, /guides/) define PIPEWISE_CUSTOM_HEAD before including header.php
$bare = $isMain || defined("PIPEWISE_CUSTOM_HEAD");

Asset::getInstance()->addString('<link rel="preconnect" href="https://fonts.googleapis.com">');
Asset::getInstance()->addString('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>');
Asset::getInstance()->addString('<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">');
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= SITE_TEMPLATE_PATH ?>/assets/img/favicon.svg" type="image/svg+xml">
	<link rel="icon" href="<?= SITE_TEMPLATE_PATH ?>/assets/img/favicon-32.png" sizes="32x32" type="image/png">
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="<?= SITE_TEMPLATE_PATH ?>/assets/img/apple-touch-icon.png">
	<meta name="theme-color" content="#0F2B33">
	<?php
	// canonical, Open Graph and Twitter tags; values are read when the page is output,
	// so the title and description set by the page itself are picked up
	$APPLICATION->AddBufferContent(function () use ($APPLICATION) {
		$siteUrl = "https://independenceplumbingandbath.com";
		$title = $APPLICATION->GetPageProperty("title") ?: $APPLICATION->GetTitle();
		$desc = $APPLICATION->GetPageProperty("description");
		$url = $siteUrl . $APPLICATION->GetCurPage(false);
		$e = function ($s) { return htmlspecialcharsbx(strip_tags((string)$s)); };

		$html = '<link rel="canonical" href="' . $e($url) . '">' . "\n";
		$html .= '<link rel="alternate" hreflang="en-us" href="' . $e($url) . '">' . "\n";
		$html .= '<link rel="alternate" hreflang="x-default" href="' . $e($url) . '">' . "\n";
		$html .= '<meta property="og:type" content="website">' . "\n";
		$html .= '<meta property="og:site_name" content="PipeWise">' . "\n";
		$html .= '<meta property="og:locale" content="en_US">' . "\n";
		$html .= '<meta property="og:title" content="' . $e($title) . '">' . "\n";
		if ($desc !== "") {
			$html .= '<meta property="og:description" content="' . $e($desc) . '">' . "\n";
		}
		$html .= '<meta property="og:url" content="' . $e($url) . '">' . "\n";
		$html .= '<meta name="twitter:card" content="summary">' . "\n";
		$html .= '<meta name="twitter:title" content="' . $e($title) . '">' . "\n";
		if ($desc !== "") {
			$html .= '<meta name="twitter:description" content="' . $e($desc) . '">' . "\n";
		}
		return $html;
	});
	?>
	<?php if (!$USER->IsAdmin()): // do not count the site administrator's own visits ?>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-Z6YKZMVSQ8"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-Z6YKZMVSQ8');
	</script>
	<!-- Google AdSense -->
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4743829014034637" crossorigin="anonymous"></script>
	<?php endif; ?>
	<?php $APPLICATION->ShowHead(); ?>
	<title><?php $APPLICATION->ShowTitle(); ?></title>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>

<div class="topbar">
	<div class="container topbar__inner">
		<?php
		$APPLICATION->IncludeComponent("bitrix:main.include", "", [
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_TEMPLATE_PATH . "/include/topbar.php",
		], false, ["HIDE_ICONS" => "Y"]);
		?>
	</div>
</div>

<header class="site-header">
	<div class="container">
		<div class="site-header__row">
			<a class="brand" href="/">
				<svg width="34" height="34" viewBox="0 0 34 34" fill="none" aria-hidden="true"><rect width="34" height="34" rx="8" fill="#0F2B33"/><path d="M10 9v9a5 5 0 0 0 5 5h9" stroke="#E08A5B" stroke-width="3.5" stroke-linecap="round"/><circle cx="24" cy="23" r="3" fill="#E08A5B"/></svg>
				<span class="brand__name">PipeWise</span>
			</a>
			<form class="search" action="/search/" method="get">
				<label class="visually-hidden" for="site-search">Search plumbing guides</label>
				<input id="site-search" class="search__input" type="search" name="q" placeholder="Search guides, e.g. low water pressure">
				<button class="search__btn" type="submit" aria-label="Search">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="9" cy="9" r="6" stroke="#fff" stroke-width="2"/><path d="m14 14 4 4" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
				</button>
			</form>
		</div>
		<?php
		$APPLICATION->IncludeComponent("bitrix:menu", "pipewise_top", [
			"ROOT_MENU_TYPE" => "top",
			"MAX_LEVEL" => "1",
			"CHILD_MENU_TYPE" => "",
			"USE_EXT" => "N",
			"DELAY" => "N",
			"ALLOW_MULTI_SELECT" => "N",
			"MENU_CACHE_TYPE" => "A",
			"MENU_CACHE_TIME" => "36000000",
			"MENU_CACHE_USE_GROUPS" => "Y",
			"MENU_CACHE_GET_VARS" => [],
		], false);
		?>
	</div>
</header>

<main class="site-main">
<?php if (!$bare): ?>
	<div class="container page">
		<?php
		$APPLICATION->IncludeComponent("bitrix:breadcrumb", "pipewise", [
			"START_FROM" => "0",
			"PATH" => "",
			"SITE_ID" => SITE_ID,
		], false);
		?>
		<h1 class="page__title"><?php $APPLICATION->ShowTitle(false); ?></h1>
<?php endif; ?>
