<?php 
// 设定你要清除BOM的根目录（会自动扫描所有子目录和文件）
$HOME = dirname(__FILE__);
//author: selfimpr
//blog: http://blog.csdn.net/lgg201
//mail: lgg860911@yahoo.com.cn
//EF BB BF这三个字节称为bom头
function hasbom(&$content) {
	$firstline = $content[0];
	return ord(substr($firstline, 0, 1)) === 0xEF
		and ord(substr($firstline, 1, 1)) === 0xBB 
		and ord(substr($firstline, 2, 1)) === 0xBF;
}
function unsetbom(&$content) {
	hasbom($content) and ($content[0] = substr($content[0], 3)); 
}
function write($filename, &$content) {
	$file = fopen($filename, 'w');
	fwrite($file, implode($content, ''));
	fclose($file);
}
function filenames($path) {
	$directory = opendir($path);
	while (false != ($filename = readdir($directory))) strpos($filename, '.') !== 0 and $filenames[] = $filename;
	closedir($directory);
	return $filenames;
}
function process($path) {
	$parent = opendir($path);
	while (false != ($filename = readdir($parent))) {
		echo $filename."<br/>";
		if(strpos($filename, '.') === 0) continue;
		if(is_dir($path.'/'.$filename)) {
			process($path.'/'.$filename);
		} else {
			$content = file($path.'/'.$filename);
			unsetbom($content);
			write($path.'/'.$filename, $content);
		}
	}
	closedir($parent);
}
process($HOME);
?>