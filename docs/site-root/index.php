<?php
// Положить в КОРЕНЬ сайта вместо заглушки установщика.
// Содержимое главной хранится в шаблоне: /local/templates/pipewise/home.php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Plumbing Help for US Homeowners: Guides & Fixes");
$APPLICATION->SetPageProperty("title", "Plumbing Help for US Homeowners: Guides & Fixes | PipeWise");
$APPLICATION->SetPageProperty("description", "Plain-English plumbing guides for US homes: fix leaks, clogs, water heater and toilet problems, and learn when to call a licensed plumber.");
include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/home.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
