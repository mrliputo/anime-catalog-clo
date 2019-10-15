<?php

require_once('../Classes/Anime.php');

$rootDir = '../Anime';
$animeDir = $_GET['anime'];
$anime = new Anime($rootDir, $animeDir);

if($anime->delete()) {
  header("Location: ../index.php");
} else {
  echo 'An error occured.';
}
