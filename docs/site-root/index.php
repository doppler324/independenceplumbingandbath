<?php
// Положить в КОРЕНЬ сайта вместо заглушки установщика.
// Содержимое главной хранится в шаблоне: /local/templates/pipewise/home.php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("PipeWise: home plumbing, explained");
include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/home.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
