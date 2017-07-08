<?php

define('ROOT_DIR', '/var/www/app/');
//define('ROOT_DIR', '/var/www/html/nc3/');

define('DS', '/');
define('TMP', ROOT_DIR . 'app/tmp/');

require __DIR__ . '/Inflector.php';
require __DIR__ . '/Folder.php';
require __DIR__ . '/File.php';
require __DIR__ . '/CakeText.php';
require __DIR__ . '/Hash.php';

/**
 * 出力関数
 *
 * @param string $message メッセージ
 * @return void
 */
function output($message) {
	echo $message . chr(10);
}

/**
 * 時間出力関数
 *
 * @param string $message メッセージ
 * @return void
 */
function outputTime() {
	echo date('Y-m-d H:i:s') . chr(10);
}

/**
 * CakeSchemaのダミークラス
 */
class CakeSchema {

}

/**
 * __d()のダミー関数
 */
function __d($plugin, $message) {

}

/**
 * Appのダミークラス
 */
class App {
	public static function uses($plugin, $dir) {

	}
}
