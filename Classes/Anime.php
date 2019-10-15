<?php

class Anime {

	private $animePath, $jsonData;
	private static $lastWatched = './lastwatched';

	public function __construct($rootDir, $animeDir) {
		$this->animePath = $rootDir . '/'. $animeDir;
		$this->jsonData = $this->setJson();
	}

	private function setJson() {
		$path = $this->animePath . "/data.json";
		
		if($this->dataExists()) {
			return json_decode(file_get_contents($path), true);
		}else return false;
	}

	public function dataExists() {
		return file_exists($this->animePath . "/data.json");
	}

	public function getType() {
		return $this->jsonData['type'];
	}

	public function getGenre() {
		return $this->jsonData['genre'];
	}

	public function getTotalEps() {
		if($this->getEpisodes()) {
			return count($this->getEpisodes());
		}else return 0;
	}

	public function getEpisodes() {
		$scannedDirs = preg_grep('~\.(mkv|mp4|mov|flv)$~', scandir($this->animePath));
		
		if($scannedDirs) return array_values($scannedDirs);
		else return false;
	}

	public function getLastEps() {
		return $this->jsonData['episode'];
	}

	public function getImage() {
		$path = $this->animePath . "/image.jpg";
		
		if(file_exists($path)) return $path;
		else return './assets/images/noimage.jpeg';
	}

	public function updateEpisode($episode) {
		$path = $this->animePath . "/data.json";
		$decoded = json_decode(file_get_contents($path), true);
		$decoded['episode'] = $episode;
		$encoded = json_encode($decoded);
		
		return file_put_contents($path, $encoded);
	}

	public function delete() {
		if(file_exists($this->animePath)) {
			$i = new DirectoryIterator($this->animePath);
			foreach($i as $f) {
				if($f->isFile()) {
					unlink($f->getRealPath());
				} else if(!$f->isDot() && $f->isDir()) {
					rrmdir($f->getRealPath());
				}
			}
			rmdir($this->animePath);
			return true;
		}else return false;
	}


	// STATIC METHODS

	public static function exists($rootDir, $animeName) {
		return file_exists($rootDir . '/' . $animeName);
	}

	public static function dirs($rootDir) {
		$dirArr = array();
		$scannedDirs = scandir($rootDir);

		foreach($scannedDirs as $item) {
			if($item != '..' 
				&& $item != '.' 
				&& $item != '.DS_Store'
				&& is_dir($rootDir . '/' . $item)) {
				array_push($dirArr, $item);
		}
	}

		if($dirArr) return $dirArr;
		else return false;
	}

	public static function getLastWatched() {
		if(file_exists(self::$lastWatched)) {
			return file_get_contents(self::$lastWatched);
		}else return false;
	}

	public static function updateLastWatched($animeName) {
		if(file_exists(self::$lastWatched)) {
			file_put_contents(self::$lastWatched, $animeName);
		}
	}

	public static function createDataFile($rootDir, $title, $type, $genre, $image, $imageUrl) {
		$path = $rootDir . '/' . $title;

		$data = array();
		$data['type'] = $type;
		$data['genre'] = $genre;
		$data['episode'] = "Plan to Watch";
		$uploadImage = null;

		$createFile = file_put_contents($path . '/data.json', json_encode($data));

		if(empty($imageUrl) && $image['size'] > 0) {
			$uploadImage = move_uploaded_file($image["tmp_name"], $path . '/image.jpg');
		}else if(!empty($imageUrl) && $image['size'] == 0){
			$url = $imageUrl;
			$img = $path . '/image.jpg';
			$uploadImage = file_put_contents($img, file_get_contents($url));
		}

		if($createFile && $uploadImage) return true;
		else return false;
	}

}