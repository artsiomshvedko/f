<?php

/**
 * F — PHP Filesystem Library
 *
 * Version: 1.0.1
 * License: MIT
 * Author: Artsiom Shvedko
 * GitHub: https://github.com/artsiomshvedko/f
 *
 * Description:
 * A compact and powerful PHP library that provides unified, safe, and consistent
 * filesystem operations including scanning, searching, reading, writing, copying,
 * permissions, timestamps, and path utilities.
 *
 * Copyright (c) 2025 Artsiom Shvedko 
*/



define('F_SCAN_ALL',	0);
define('F_SCAN_FILES',	1);
define('F_SCAN_DIRS',	2);
define('F_SCAN_LINKS',	3);

define('F_SPACE_TOTAL',	0);
define('F_SPACE_FREE',	1);
define('F_SPACE_USED',	2);

define('F_ALL',			0);
define('F_ATIME',		1);
define('F_CTIME',		2);
define('F_MTIME',		4);
define('F_TYPE',		8);
define('F_SIZE',		16);
define('F_NAME',		32);
define('F_FILENAME',	64);
define('F_EXTENSION',	128);
define('F_PERMISSION',	256);

class f {
	
	private $buffer;
	
	public function __construct() {
		$this->buffer = [];
	}

	public function accessed(string $path, int|null $time = null): int|null {
		$path = $this->normalize($path);
		if ($time !== null) {
			touch($path, null, $time);
		}
		return fileatime($path) ?: null;
	}
	
	public function append(string $path, string $data, int|null $maxlen = null): bool {
		$path = $this->normalize($path);
		$filesize = 0;
		if (is_file($path)) {
			$filesize = filesize($path) ?: 0;
		}
		return $this->write($path, $data, $filesize, $maxlen);
	}
	
	public function copy(string $source, string $dest, string|null $name = null): bool {
		$return = false;
		$source = $this->normalize($source);
		$dest = $this->normalize($dest);
		$name = ($name === null) ? basename($source) : $name;
		if (file_exists($source)) {
			$target = $dest . "/" . $name;
			if (is_file($source)) {
				$return = copy($source, $target);
				clearstatcache(true, $target);
			} elseif (is_link($source)) {
				$link = readlink($source);
				$return = (($link !== false) && (symlink($link, $target)));
			} elseif (is_dir($source)) {
				if ($this->make($target)) {
					if ($dh = opendir($source)) {
						$return = true;
						while (($item = readdir($dh)) !== false) {
							if (($item === '.') || ($item === '..')) continue;
							if (!$this->copy($source . "/" . $item, $target, $item)) {
								$return = false;
								break;
							}
						}
						closedir($dh);
					}
				}
			}
		}
		return $return;
	}
	
	public function created(string $path): int|null {
		$path = $this->normalize($path);
		return filectime($path) ?: null;
	}
	
	public function cut(string $source, string $dest, string|null $name = null): bool {
		if ($this->copy($source, $dest, $name)) {
			return $this->remove($source);
		}
		return false;
	}
	
	public function extension(string $path): string {
		$path = $this->normalize($path);
		return pathinfo($path, PATHINFO_EXTENSION);
	}
	
	public function filename(string $path): string {
		$path = $this->normalize($path);
		return pathinfo($path, PATHINFO_FILENAME);
	}
	
	public function find(string $path, int $key = F_SCAN_ALL, int $keys = F_ALL, bool $recursive = false, string|null $query = null, int|null $depth = null): array {
		$filter = json_decode($query ?? '', true) ?: [];
		$pattern = $filter['pattern'] ?? null;
		$min = isset($filter['size']['min']) ? (int)$filter['size']['min'] : 0;
		$max = isset($filter['size']['max']) ? (int)$filter['size']['max'] : PHP_INT_MAX;
		$time = $filter['time'] ?? [];
		$keys = ($keys === F_ALL) ? F_ATIME | F_CTIME | F_MTIME | F_TYPE | F_SIZE | F_NAME | F_FILENAME | F_EXTENSION | F_PERMISSION : $keys;
		$check_time = function(int $value, array $limits): bool {
			if (isset($limits['min']) && $value < (int)$limits['min']) return false;
			if (isset($limits['max']) && $value > (int)$limits['max']) return false;
			return true;
		};
		$check_size = function(int $size) use ($min, $max): bool {
			return $size >= $min && $size <= $max;
		};
		$result = [];
		$files = $this->scan($path, $key, true, $recursive, $depth);
		foreach ($files as $file) {
			$basename = basename(str_replace('\\', '/', $file));
			if ($pattern !== null && !fnmatch($pattern, $basename)) continue;
			$atime = (int)$this->accessed($file);
			$ctime = (int)$this->created($file);
			$mtime = (int)$this->modified($file);
			$size = (int)$this->size($file);
			if (isset($time['atime']) && !$check_time($atime, $time['atime'])) continue;
			if (isset($time['ctime']) && !$check_time($ctime, $time['ctime'])) continue;
			if (isset($time['mtime']) && !$check_time($mtime, $time['mtime'])) continue;
			if (!$check_size($size)) continue;
			$data = [];
			if ($keys & F_ATIME) $data['atime'] = $atime;
			if ($keys & F_CTIME) $data['ctime'] = $ctime;
			if ($keys & F_MTIME) $data['mtime'] = $mtime;
			if ($keys & F_SIZE) $data['size'] = $size;
			if ($keys & F_NAME) $data['name'] = $this->name($file);
			if ($keys & F_FILENAME) $data['filename'] = $this->filename($file);
			if ($keys & F_EXTENSION) $data['extension'] = $this->extension($file);
			if ($keys & F_TYPE) $data['type'] = is_link($file) ? 'link' : (is_dir($file) ? 'dir' : (is_file($file) ? 'file' : 'unknown'));
			if ($keys & F_PERMISSION) $data['permission'] = $this->permission($file);
			$result[$file] = $data;
		}
		return $result;
	}
	
	public function ini_get(string $path, string $section, string $key, mixed $value = ""): mixed {
		$path = $this->normalize($path);
		if (!isset($this->buffer['ini'][$path])) {
			$this->buffer['ini'][$path] = $this->read_ini($path);
			if ($this->buffer['ini'][$path] === false) {
				$this->buffer['ini'][$path] = [];
			}
		}
		if (!isset($this->buffer['ini'][$path][$section][$key])) {
			$this->buffer['ini'][$path][$section][$key] = $value;
		}
		return $this->buffer['ini'][$path][$section][$key];
	}
	
	public function ini_set(string $path, string $section, string $key, mixed $value): mixed {
		$path = $this->normalize($path);
		if (!isset($this->buffer['ini'][$path])) {
			$this->buffer['ini'][$path] = $this->read_ini($path);
		}
		$this->buffer['ini'][$path][$section][$key] = $value;
		return $this->buffer['ini'][$path][$section][$key];
	}
	
	public function location(string $path): string {
		$path = $this->normalize($path);
		return pathinfo($path, PATHINFO_DIRNAME);
	}
	
	public function modified(string $path, int|null $time = null): int|null {
		$path = $this->normalize($path);
		if ($time !== null) {
			touch($path, $time);
		}
		return filemtime($path) ?: null;
	}
	
	public function make(string $path, string $chmod = '0755'): bool {
		$path = $this->normalize($path);
		if (is_dir($path)) {
			return true;
		}
		$mode = intval($chmod, 8);
		return mkdir($path, $mode, true);
	}
	
	public function name(string $path): string {
		$name = $this->filename($path);
		$extension = $this->extension($path);
		if ($extension) {
			$name .= '.' . $extension;
		}
		return $name;
	}
	
	public function normalize(string $path): string|false {
		$key = md5($path);
		if (isset($this->buffer["normalize"][$key])) {
			return $this->buffer["normalize"][$key];
		}
		$unc = "";
		$drive = "";
		$path = str_replace("\\", "/", $path);
		if (preg_match('#^//([^/]+)/([^/]+)(/.*)?#', $path, $matches)) {
			$unc = "//" . $matches[1] . "/" . $matches[2];
			$path = isset($matches[3]) ? $matches[3] : "";
		} else {
			$pattern = '#^([a-zA-Z]:)?(/.*)#';
			if (preg_match($pattern, $path, $matches)) {
				$drive = $matches[1];
				$path = $matches[2];
			} else {
				$path = dirname($_SERVER['SCRIPT_FILENAME']) . "/" . $path;
				$path = str_replace("\\", "/", $path);
				if (preg_match($pattern, $path, $matches)) {
					$drive = $matches[1];
					$path = $matches[2];
				}
			}
		}
		$parts = [];
		foreach (explode("/", $path) as $part) {
			if ($part === "" || $part === ".") {
				continue;
			} elseif ($part === "..") {
				if (empty($parts)) {
					throw new InvalidArgumentException("Failed to normalize path");
				}
				array_pop($parts);
			} else {
				$parts[] = $part;
			}
		}
		$normalized = "/" . implode("/", $parts);
		if (preg_match('#[<>:"|?*\x00]#', $normalized)) {
			throw new InvalidArgumentException("Invalid characters in path");
		}
		if ($unc) {
			$normalized = $unc . $normalized;
		} else {
			$normalized = $drive . $normalized;
		}
		$this->buffer["normalize"][$key] = $normalized;
		return $normalized;
	}
	
	public function permission(string $path, string|null $chmod = null): string|bool {
		$path = $this->normalize($path);
		if ($chmod === null) {
			if (($perms = fileperms($path)) === false) {
				return false;
			}
			return sprintf('%04o', $perms & 0777);
		}
		$mode = intval($chmod, 8);
		return chmod($path, $mode);
	}
	
	public function remove(string $path): bool {
		$return = false;
		$path = $this->normalize($path);
		if ((is_file($path)) || (is_link($path))) {
			$return = unlink($path);
			if ($return) {
				clearstatcache(true, $path);
			}
		} elseif (is_dir($path)) {
			$return = true;
			if ($dh = opendir($path)) {
				while (($item = readdir($dh)) !== false) {
					if (($item === '.') || ($item === '..')) continue;
					if (!$this->remove($path . "/" . $item)) {
						$return = false;
						break;
					}
				}
				closedir($dh);
			}
			if ($return) {
				$return = rmdir($path);
				if ($return) {
					clearstatcache(true, $path);
				}
			}
		}
		return $return;
	}
	
	public function read(string $path, int|null $offset = null, int|null $maxlen = null): string {
		$return = "";
		$path = $this->normalize($path);
		if (!is_file($path)) {
			return $return;
		}
		$filesize = filesize($path) ?: 0;
		if ($filesize === 0) {
			return $return;
		}
		if ($offset === null) {
			$offset = 0;
		} elseif ($offset < 0) {
			$offset = max(0, $filesize + $offset);
		}
		if ($offset > $filesize) {
			return $return;
		}
		if ($maxlen === null) {
			$maxlen = $filesize - $offset;
		} elseif ($offset + $maxlen > $filesize) {
			$maxlen = $filesize - $offset;
		}
		if ($maxlen < 0) {
			$maxlen = 0;
		}
		$fp = @fopen($path, "rb");
		if ($fp === false) {
			return $return;
		}
		$i = 0;
		$flock = false;
		while ($i++ < 100) {
			$flock = flock($fp, LOCK_SH | LOCK_NB);
			if ($flock) {
				break;
			}
			usleep(10000);
		}
		if ($flock) {
			if (fseek($fp, $offset) === 0) {
				$return = (string)fread($fp, $maxlen);
			}
			flock($fp, LOCK_UN);
		}
		fclose($fp);
		return $return;
	}
	
	public function read_csv(string $path, string $separator = ';'): array {
		$data = [];
		$content = $this->read($path);
		$content = preg_replace('/^\x{FEFF}/u', '', $content);
		$lines = preg_split('/\r\n|\r|\n/', trim($content));
		foreach ($lines as $line) {
			if (trim($line) === '') continue;
			$row = str_getcsv($line, $separator);
			if (end($row) === '') {
				array_pop($row);
			}
			$data[] = $row;
		}
		return $data;
	}
	
	public function read_ini(string $path): array {
		$data = parse_ini_string($this->read($path), true, INI_SCANNER_TYPED);
		if ($data === false) {
			return [];
		}
		return $data;
	}
	
	public function read_json(string $path): mixed {
		return json_decode($this->read($path), true);
	}
	
	public function rename(string $path, string|null $name = null): bool {
		$path = $this->normalize($path);
		$name = ($name === null) ? basename($path) : $name;
		$new = dirname($path) . "/" . $name;
		if (file_exists($new)) {
			return false;
		}
		return rename($path, $new);
	}
	
	public function scan(string $path, int $key = F_SCAN_ALL, bool $absolute = false, bool $recursive = false, int|null $depth = null): array {
		$result = [];
		if (is_int($depth)) {
			if ($depth <= 0) {
				return $result;
			}
			$depth--;
		}
		$path = $this->normalize($path);
		if (is_dir($path)) {
			if ($scan = scandir($path)) {
				$scan = array_diff($scan, ['.', '..']);
				foreach ($scan as $value) {
					$link = $path . "/" . $value;
					if (is_file($link) && ($key === 0 || $key === F_SCAN_FILES)) {
						$result[] = $link;
					} elseif (is_link($link) && ($key === 0 || $key === F_SCAN_LINKS)) {
						$result[] = $link;
					} elseif (is_dir($link)) {
						if ($key === 0 || $key === F_SCAN_DIRS) {
							$result[] = $link;
						}
						if ($recursive) {
							$result = array_merge($result, $this->scan($link, $key, true, true, $depth));
						}
					}
				}
				if (!$absolute) {
					$result = array_map('basename', $result);
				}
			}
		}
		return $result;
	}
	
	public function size(string $path): int	{
		$path = $this->normalize($path);
		$size = 0;
		if (is_file($path)) {
			$size = filesize($path) ?: 0;
		} elseif (is_link($path)) {
			$size = 0;
		} elseif (is_dir($path)) {
			if ($dh = opendir($path)) {
				while (($item = readdir($dh)) !== false) {
					if (($item === '.') || ($item === '..')) continue;
					$size += $this->size($path . "/" . $item);
				}
				closedir($dh);
			}
		}
		return $size;
	}

	public function space(string $path, int $key = F_SPACE_TOTAL): int {
		$space = 0;
		$path = $this->normalize($path);
		if ($key === F_SPACE_FREE) {
			$space = (int)disk_free_space($path);
		} elseif ($key === F_SPACE_USED) {
			$space = (int)disk_total_space($path) - (int)disk_free_space($path);
		} elseif ($key === F_SPACE_TOTAL) {
			$space = (int)disk_total_space($path);
		}
		return $space;
	}
	
	public function write(string $path, string $data = '', int|null $offset = null, int|null $maxlen = null): bool {
		$return = false;
		$path = $this->normalize($path);
		if (!$this->make(dirname($path))) {
			return $return;
		}
		if (($maxlen !== null) && (strlen($data) > $maxlen)) {
			$data = substr($data, 0, $maxlen);
		}
		$filesize = 0;
		if (is_file($path)) {
			$filesize = filesize($path) ?: 0;
		}
		$mode = "c+b";
		if ($offset === null) {
			$mode = "w+";
			$offset = 0;
		} elseif ($offset < 0) {
			$offset = max(0, $filesize + $offset);
		}
		$fp = fopen($path, $mode);
		if ($fp === false) {
			return $return;
		}
		$i = 0;
		$flock = false;
		while ($i++ < 100) {
			$flock = flock($fp, LOCK_EX | LOCK_NB);
			if ($flock) {
				break;
			}
			usleep(10000);
		}
		if ($flock) {
			if (fseek($fp, $offset) === 0) {
				$return = (fwrite($fp, $data) === strlen($data));
				fflush($fp);
			}
			flock($fp, LOCK_UN);
		}
		fclose($fp);
		if ($return) {
			clearstatcache(true, $path);
		}
		return $return;
	}
	
	public function write_csv(string $path, array $array = [], string $separator = ';'): bool {
		$rows = [];
		foreach ($array as $row) {
			$row = array_map('strval', $row);
			$rows[] = implode($separator, $row) . $separator;
		}
		return $this->write($path, implode("\n", $rows));
	}
	
	public function write_ini(string $path, array $array = []): bool {
		$data = "";
		foreach ($array as $section => $values) {
			if ((!is_string($section)) && (!is_int($section))) continue;
			$section = (string)$section;
			if (!preg_match('/^[\w\.\-]+$/u', $section)) continue;
			if (!is_array($values)) continue;
			$data .= "[" . $section . "]" . PHP_EOL;
			foreach ($values as $key => $value) {
				if ((!is_string($key)) && (!is_int($key))) continue;
				$key = (string)$key;
				if (!preg_match('/^[\w\.\-]+$/u', $key)) continue;
				if (is_bool($value)) {
					$value = $value ? 'true' : 'false';
				} elseif (is_null($value)) {
					$value = 'null';
				} elseif (is_int($value) || is_float($value)) {
					$value = (string)$value;
				} elseif (is_string($value)) {
					$value = '"' . strtr($value, ['\\' => '\\\\', '"'  => '\"', "\r" => '\\r', "\n" => '\\n']) . '"';
				} else {
					continue;
				}
				$data .= $key . ' = ' . $value . PHP_EOL;
			}
			$data .= PHP_EOL;
		}
		$data = rtrim($data, PHP_EOL);
		return $this->write($path, $data);
	}
	
	public function write_json(string $path, array $array = []): bool {
		$json = json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		if ($json === false) {
			return false;
		}
		return $this->write($path, $json);
	}
	
	public function __destruct() {
		if (isset($this->buffer['ini'])) {
			foreach ($this->buffer['ini'] as $path => $value) {
				if ($this->read_ini($path) !== $this->buffer['ini'][$path]) {
					$this->write_ini($path, $this->buffer['ini'][$path]);
				}
			}
		}
	}
	
}

?>