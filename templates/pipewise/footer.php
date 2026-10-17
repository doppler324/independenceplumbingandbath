<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$isMain = $APPLICATION->GetCurPage(false) === '/';
?>
<?php if (!$isMain): ?>
	</div>
<?php endif; ?>
</main>

<footer class="site-footer">
	<div class="container site-footer__inner">
		<div class="site-footer__about">
			<span class="site-footer__brand">PipeWise</span>
			<?php
			$APPLICATION->IncludeComponent("bitrix:main.include", "", [
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_TEMPLATE_PATH . "/include/footer_disclaimer.php",
			], false, ["HIDE_ICONS" => "Y"]);
			?>
		</div>
		<?php
		$APPLICATION->IncludeComponent("bitrix:menu", "pipewise_footer", [
			"ROOT_MENU_TYPE" => "bottom",
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
</footer>
</body>
</html>
