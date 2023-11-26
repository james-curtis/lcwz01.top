<?php

class dirCache
{
	private $_unitNum = 999;
	private $_unitFloor = 3;
	private $_pix = '_';
	private $_now = 0;
	public $baseDir = '';
	public $dirMode = 511;
	public function __construct($baseDir = null)
	{
		if ($baseDir) {
			$this->baseDir = $baseDir;
		} else {
			$this->baseDir = dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)) . DIRECTORY_SEPARATOR . 'cacheData';
		}
		if ($_SERVER['REQUEST_TIME']) {
			$this->_now = $_SERVER['REQUEST_TIME'];
		} else {
			$this->_now = time();
		}
	}
	public function isExists($id, $timeout = 0, $isCreate = false)
	{
		$file = $this->hashId($id);
		if (!is_file($file)) {
			if (is_file($file . '.lock')) {
				return 'LOCKED';
			}
			return false;
		}
		$timefun = $isCreate ? 'filectime' : 'filemtime';
		if ($timeout && $timefun($file) + $timeout < $this->_now) {
			return false;
		}
		return true;
	}
	public function set($id, $data = null)
	{
		return $this->_writeFile($this->hashId($id), $this->_encode($data));
	}
	public function get($id, $lockread = false)
	{
		return $this->_decode($this->_readFile($this->hashId($id), $lockread));
	}
	public function del($id)
	{
		return $this->_deleteFile($this->hashId($id));
	}
	public function hashId($id)
	{
		if (!is_numeric($id)) {
			$id = $this->enId($id);
			$notNum = 'a';
		}
		$id = $tid = $id;
		$hash = '';
		$pow = 0;
		for ($i = $this->_unitFloor; $i; $i--) {
			$pow = pow($this->_unitNum, $i);
			$unit = floor($tid / $pow);
			if ($unit > $this->_unitNum) {
				$unit = $this->_unitNum;
			}
			$hash .= $this->_pix . $unit . DIRECTORY_SEPARATOR;
			$tid = $tid - $unit * $pow;
		}
		unset($pow, $tid, $unit, $i);
		return $this->baseDir . DIRECTORY_SEPARATOR . $hash . $notNum . $id . '.html';
	}
	public function enId($data)
	{
		return sprintf('%011u', crc32($data));
	}
	private function _encode($data)
	{
		$array = array('source' => $data);
		return '<?php die(\'Cache Page by dirCache.\') ?>' . serialize($array);
	}
	protected function _decode($data)
	{
		$array = unserialize($data);
		return $array['source'];
	}
	protected function _writeFile($file, $data)
	{
		$dir = dirname($file);
		if (!is_dir($dir)) {
			mkdir($dir, $this->dirMode, true);
		}
		if (!is_file($file) && is_file($file . '.lock')) {
			return false;
		}
		if (PHP_VERSION_ID < 70000) {
			$mqr = @get_magic_quotes_runtime();
			@set_magic_quotes_runtime(0);
			$re = @file_put_contents($file, $data, LOCK_EX);
			@set_magic_quotes_runtime($mqr);
		} else {
			$re = @file_put_contents($file, $data, LOCK_EX);
		}
		return $re;
	}
	protected function _readFile($file, $lockread = false)
	{
		if (!is_file($file)) {
			if (!$lockread) {
				return false;
			}
			$file .= '.lock';
			if (!is_file($file)) {
				return false;
			}
		}
		return file_get_contents($file, null, null, 39);
	}
	protected function _deleteFile($file)
	{
		if (is_file($file)) {
			return unlink($file);
		}
		return false;
	}
	public function lock($id)
	{
		$file = $this->hashId($id);
		if (!is_file($file) && is_file($file . '.lock')) {
			return true;
		}
		return rename($file, $file . '.lock');
	}
	public function unlock($id)
	{
		$file = $this->hashId($id);
		if (is_file($file) && !is_file($file . '.lock')) {
			return true;
		}
		return rename($file . '.lock', $file);
	}
}