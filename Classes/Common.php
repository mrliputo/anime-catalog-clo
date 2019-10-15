<?php

class Common {
	public function cleanFilename($string) {
		return trim(trim(pathinfo(preg_replace("/\[([^\[\]]++|(?R))*+\]/", "", $string), PATHINFO_FILENAME), '-'), ' ');
	}
}