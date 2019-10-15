<?php

require_once('../Classes/Anime.php');
require_once('../Classes/Common.php');

$rootDir = '../Anime';
$animeDir = $_GET['title'];
$content = $_GET['new_content'];
$anime = new Anime($rootDir, $animeDir);

if($anime->updateEpisode($content)) {
	echo Common::cleanFilename($content);
}