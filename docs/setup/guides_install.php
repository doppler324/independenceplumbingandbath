<?php
/**
 * One-time setup for the /guides/ section. Run on the server from the command line,
 * as the site user:
 *
 *   sudo -u <site user> php /path/to/site/local/docs/setup/guides_install.php
 *
 * What it does (safe to run again, nothing is deleted or overwritten):
 *   - creates the "content" infoblock type and the "Guides" infoblock (code: guides)
 *   - adds properties: author, reviewer, read time, difficulty, time, tools
 *   - adds the sections used in the site menu
 *   - adds one sample article
 *   - adds the URL rewrite rule for /guides/ and writes /guides/index.php if it is missing
 */
if (PHP_SAPI !== "cli") {
	die("Run this script from the command line.\n");
}

$_SERVER["DOCUMENT_ROOT"] = dirname(__DIR__, 3);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_NO_ACCELERATOR_RESET", true);
require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

global $USER;
if (!$USER->IsAuthorized()) {
	$USER->Authorize(1);
}
CModule::IncludeModule("iblock");

function say($s) { echo $s . "\n"; }

$site = CSite::GetList("sort", "asc", ["DEFAULT" => "Y"])->Fetch();
if (!$site) {
	$site = CSite::GetList("sort", "asc", [])->Fetch();
}
$siteId = $site["LID"];
say("Site: " . $siteId);

/* 1. infoblock type */
$typeId = "content";
if (!CIBlockType::GetByID($typeId)->Fetch()) {
	$lang = [];
	$rsLang = CLanguage::GetList("sort", "asc");
	while ($l = $rsLang->Fetch()) {
		$lang[$l["LID"]] = ["NAME" => "Content", "SECTION_NAME" => "Sections", "ELEMENT_NAME" => "Items"];
	}
	$obType = new CIBlockType();
	if (!$obType->Add(["ID" => $typeId, "SECTIONS" => "Y", "IN_RSS" => "N", "SORT" => 100, "LANG" => $lang])) {
		die("Could not create infoblock type: " . $obType->LAST_ERROR . "\n");
	}
	say("Created infoblock type: " . $typeId);
} else {
	say("Infoblock type exists: " . $typeId);
}

/* 2. infoblock */
$codeSettings = [
	"IS_REQUIRED" => "Y",
	"DEFAULT_VALUE" => [
		"UNIQUE" => "Y", "TRANSLITERATION" => "Y", "TRANS_LEN" => "100", "TRANS_CASE" => "L",
		"TRANS_SPACE" => "-", "TRANS_OTHER" => "-", "TRANS_EAT" => "Y", "USE_GOOGLE" => "N",
	],
];
$ib = CIBlock::GetList([], ["TYPE" => $typeId, "CODE" => "guides", "CHECK_PERMISSIONS" => "N"])->Fetch();
if (!$ib) {
	$obIb = new CIBlock();
	$ibId = $obIb->Add([
		"IBLOCK_TYPE_ID" => $typeId,
		"LID" => [$siteId],
		"CODE" => "guides",
		"NAME" => "Guides",
		"ACTIVE" => "Y",
		"SORT" => 100,
		"LIST_PAGE_URL" => "#SITE_DIR#guides/",
		"SECTION_PAGE_URL" => "#SITE_DIR#guides/#SECTION_CODE#/",
		"DETAIL_PAGE_URL" => "#SITE_DIR#guides/#SECTION_CODE#/#ELEMENT_CODE#/",
		"INDEX_ELEMENT" => "Y",
		"INDEX_SECTION" => "Y",
		"WORKFLOW" => "N",
		"BIZPROC" => "N",
		"GROUP_ID" => ["1" => "X", "2" => "R"],
		"FIELDS" => ["CODE" => $codeSettings, "SECTION_CODE" => $codeSettings],
	]);
	if (!$ibId) {
		die("Could not create infoblock: " . $obIb->LAST_ERROR . "\n");
	}
	say("Created infoblock Guides, ID " . $ibId);
} else {
	$ibId = (int)$ib["ID"];
	say("Infoblock exists, ID " . $ibId);
}

/* 3. properties */
$props = [
	["AUTHOR", "Author", "S", 10],
	["REVIEWER", "Reviewed by (name, license)", "S", 20],
	["READ_TIME", "Read time, minutes", "N", 30],
	["DIFFICULTY", "Difficulty", "S", 40],
	["TIME_NEEDED", "Time needed", "S", 50],
	["TOOLS", "Tools", "S", 60],
];
foreach ($props as [$code, $name, $type, $sort]) {
	if (CIBlockProperty::GetList([], ["IBLOCK_ID" => $ibId, "CODE" => $code])->Fetch()) {
		continue;
	}
	$obProp = new CIBlockProperty();
	if ($obProp->Add(["IBLOCK_ID" => $ibId, "CODE" => $code, "NAME" => $name, "PROPERTY_TYPE" => $type, "SORT" => $sort, "ACTIVE" => "Y"])) {
		say("Added property " . $code);
	} else {
		say("Property " . $code . " failed: " . $obProp->LAST_ERROR);
	}
}

/* 4. sections */
$sections = [
	["leaks-faucets", "Leaks & Faucets", "Dripping faucets, leaking supply lines and hidden leaks: how to find them and fix them."],
	["drains-clogs", "Drains & Clogs", "Slow sinks, blocked tubs and backed-up lines: what works before you call a plumber."],
	["water-heaters", "Water Heaters", "No hot water, strange noises and leaks: troubleshooting gas and electric water heaters."],
	["toilets", "Toilets", "Running, clogged and weak-flush toilets, and the parts that usually fail."],
	["pipes", "Pipes & Repiping", "Pipe materials, low water pressure, noisy pipes and when repiping makes sense."],
	["costs", "Costs", "What common plumbing repairs and replacements involve, and what affects the price."],
	["emergencies", "Emergencies", "Burst pipes and major leaks: the first steps to take right now."],
];
$secIds = [];
$sort = 10;
foreach ($sections as [$code, $name, $descr]) {
	$row = CIBlockSection::GetList([], ["IBLOCK_ID" => $ibId, "CODE" => $code], false, ["ID"])->Fetch();
	if ($row) {
		$secIds[$code] = (int)$row["ID"];
		continue;
	}
	$obSec = new CIBlockSection();
	$id = $obSec->Add([
		"IBLOCK_ID" => $ibId, "NAME" => $name, "CODE" => $code, "ACTIVE" => "Y", "SORT" => $sort,
		"DESCRIPTION" => $descr, "DESCRIPTION_TYPE" => "text",
	]);
	if ($id) {
		$secIds[$code] = (int)$id;
		say("Added section " . $name);
	} else {
		say("Section " . $name . " failed: " . $obSec->LAST_ERROR);
	}
	$sort += 10;
}

/* 5. sample article */
$sampleCode = "how-to-fix-a-leaky-faucet";
if (!CIBlockElement::GetList([], ["IBLOCK_ID" => $ibId, "CODE" => $sampleCode], false, false, ["ID"])->Fetch() && !empty($secIds["leaks-faucets"])) {
	$body = <<<'HTML'
<p>Most drips come from a worn washer, O-ring or cartridge inside the faucet. Parts are cheap, but they have to match your faucet, so take the old part with you to the hardware store.</p>

<h2>1. Shut off the water</h2>
<p>Look under the sink for the two shut-off valves and turn each clockwise until it stops. Open the faucet to drain what is left in the line. If there are no valves, shut off the main water valve instead.</p>
<div class="callout"><strong>Valve won&rsquo;t turn?</strong> Don&rsquo;t force it. A corroded valve can snap and flood the cabinet. Shut off the main and see &ldquo;When to call a plumber&rdquo; below.</div>

<h2>2. Plug the drain</h2>
<p>Stuff a rag in the drain opening so a dropped screw or washer doesn&rsquo;t vanish. Lay a towel in the basin to protect the finish.</p>

<h2>3. Take the faucet apart</h2>
<p>Pry off the decorative cap on the handle, remove the screw underneath, and lift the handle away. What you see next tells you the faucet type.</p>
<div class="type-grid">
<div class="type-card"><strong>Compression</strong>Two handles, a stem you unscrew. Usually a worn rubber washer or seat.</div>
<div class="type-card"><strong>Cartridge</strong>One handle that lifts and turns. Replace the cartridge or its O-rings.</div>
<div class="type-card"><strong>Ball</strong>One handle over a rounded cap. Springs, seats and O-rings wear out.</div>
<div class="type-card"><strong>Ceramic disc</strong>One handle, wide cylinder body. Replace the seals or the disc cartridge.</div>
</div>

<h2>4. Replace the worn part</h2>
<p>Remove the worn washer, O-ring or cartridge and compare it with the new one side by side. Coat new rubber parts with plumber&rsquo;s grease before they go in.</p>

<h2>5. Reassemble and test</h2>
<p>Put everything back in reverse order. Open the faucet, turn the shut-off valves on slowly, then let the water run for a minute. Check under the sink for drips.</p>

<div class="pro-box">
<h2>When to call a plumber</h2>
<ul>
<li>The leak is at the supply line, a shut-off valve or behind the wall.</li>
<li>Valves are corroded and won&rsquo;t turn or won&rsquo;t seal.</li>
<li>You see water stains on a ceiling or cabinet floor below.</li>
<li>The faucet still drips after you replaced the part.</li>
</ul>
</div>
HTML;
	$obEl = new CIBlockElement();
	$elId = $obEl->Add([
		"IBLOCK_ID" => $ibId,
		"IBLOCK_SECTION_ID" => $secIds["leaks-faucets"],
		"NAME" => "How to fix a leaky faucet, whatever type you have",
		"CODE" => $sampleCode,
		"ACTIVE" => "Y",
		"ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
		"PREVIEW_TEXT" => "A steady drip is usually one worn part. Here is how to find out which, replace it, and know when the job is bigger than a faucet.",
		"PREVIEW_TEXT_TYPE" => "text",
		"DETAIL_TEXT" => $body,
		"DETAIL_TEXT_TYPE" => "html",
		"PROPERTY_VALUES" => [
			"AUTHOR" => "PipeWise Editorial Team",
			"READ_TIME" => 8,
			"DIFFICULTY" => "Beginner",
			"TIME_NEEDED" => "About an hour",
			"TOOLS" => "Adjustable wrench, screwdrivers, plumber's grease",
		],
	]);
	say($elId ? "Added sample article" : "Sample article failed: " . $obEl->LAST_ERROR);
} else {
	say("Sample article exists (or leaks section missing)");
}

/* 6. URL rewrite rule */
$hasRule = false;
foreach (CUrlRewriter::GetList(["SITE_ID" => $siteId]) as $rule) {
	if ($rule["ID"] === "bitrix:news" && strpos($rule["CONDITION"], "/guides/") !== false) {
		$hasRule = true;
	}
}
if (!$hasRule) {
	CUrlRewriter::Add([
		"SITE_ID" => $siteId,
		"CONDITION" => "#^/guides/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/guides/index.php",
	]);
	say("Added URL rewrite rule for /guides/");
}

/* 7. /guides/index.php */
$dir = $_SERVER["DOCUMENT_ROOT"] . "/guides";
$file = $dir . "/index.php";
if (!file_exists($file)) {
	if (!is_dir($dir)) {
		mkdir($dir, 0755, true);
	}
	$page = <<<'PHP'
<?php
define("PIPEWISE_CUSTOM_HEAD", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Plumbing guides");
$APPLICATION->IncludeComponent("bitrix:news", "pipewise_guides", [
	"IBLOCK_TYPE" => "content",
	"IBLOCK_ID" => "__IBLOCK_ID__",
	"NEWS_COUNT" => "12",
	"USE_SEARCH" => "N",
	"USE_RSS" => "N",
	"USE_RATING" => "N",
	"USE_CATEGORIES" => "N",
	"USE_REVIEW" => "N",
	"USE_FILTER" => "N",
	"USE_PERMISSIONS" => "N",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"CHECK_DATES" => "Y",
	"SEF_MODE" => "Y",
	"SEF_FOLDER" => "/guides/",
	"SEF_URL_TEMPLATES" => [
		"news" => "",
		"section" => "#SECTION_CODE#/",
		"detail" => "#SECTION_CODE#/#ELEMENT_CODE#/",
	],
	"AJAX_MODE" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "Y",
	"SET_STATUS_404" => "Y",
	"SHOW_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "Y",
	"ADD_ELEMENT_CHAIN" => "Y",
	"STRICT_SECTION_CHECK" => "Y",
], false);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
PHP;
	file_put_contents($file, str_replace("__IBLOCK_ID__", (string)$ibId, $page));
	say("Wrote /guides/index.php");
} else {
	say("/guides/index.php exists");
}

BXClearCache(true);
say("Done. Infoblock ID: " . $ibId);
