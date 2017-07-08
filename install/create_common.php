<?php

class App {
	public static function uses() {
	}
}
require 'Utility/CakeText.php';
require 'Utility/Hash.php';
require 'Utility/Inflector.php';

$pluginKey = $argv[1];
$dbname = $argv[2];
if (isset($argv[3])) {
	define('DBPREFIX', trim($argv[3]));
} else {
	define('DBPREFIX', '');
}

$mysqli = new mysqli('localhost', 'root', 'root', $dbname);
if ($mysqli->connect_errno) {
	echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
}
$result = $mysqli->query('SET NAMES utf8;');


/**
 * SQL実行
 */
function executeSelect($table, $where = array()) {
	global $mysqli;

	if ($where) {
		$where = array_map(function ($key, $value) {
			global $mysqli;

			if (substr($value, 0, 2) === '!=') {
				$sign = '!=';
				$value = trim(substr($value, 2));
			} elseif (substr($value, 0, 1) === '=') {
				$sign = '=';
				$value = trim(substr($value, 1));
			} else {
				$sign = '=';
				$value = trim($value);
			}
			
			if ($value !== null) {
				$value = $mysqli->real_escape_string($value);
			}

			return '`' . $key . '`' . ' ' . $sign . ' \'' . $mysqli->real_escape_string($value) . '\'';
		}, array_keys($where), array_values($where));
	} else {
		$where = array('1 = 1');
	}

	$sql = 'SELECT * FROM ' . DBPREFIX . $table .
			' WHERE ' . implode(' AND ', $where);

	$result = $mysqli->query($sql);

	if ($mysqli->errno) {
		echo('Error: ' . $mysqli->errno . chr(10) . $mysqli->error . chr(10));
		echo($sql . chr(10));
		die();
	}

	return $result;
}

/**
 * SQL実行
 */
function executeInsert($table, $params) {
	global $mysqli;

	$params = array_merge($params, array(
		'created_user' => '1',
		'created' => gmdate('Y-m-d H:i:s'),
		'modified_user' => '1',
		'modified' => gmdate('Y-m-d H:i:s'),
	));

	$params = array_map(function ($value) {
		global $mysqli;
		return '\'' . $mysqli->real_escape_string($value) . '\'';
	}, $params);

	$sql = 'INSERT INTO ' . DBPREFIX . $table . '(`' . implode('`, `', array_keys($params)) . '`)';
	$sql .= ' VALUES (' . implode(', ', array_values($params)) . ')';

	$result = $mysqli->query($sql);

	if ($mysqli->errno) {
		echo('Error: ' . $mysqli->errno . chr(10) . $mysqli->error . chr(10));
		echo($sql . chr(10));
		$mysqli->rollback();
		die();
	}

	return $result;
}

/**
 * SQL実行
 */
function executeUpdate($table, $params, $where = array()) {
	global $mysqli;

	$params = array_merge($params, array(
		'modified_user' => '1',
		'modified' => gmdate('Y-m-d H:i:s'),
	));

	$params = array_map(function ($key, $value) {
		global $mysqli;
		if (! isset($value)) {
			return '`' . $key . '`' . ' = NULL';
		} else {
			return '`' . $key . '`' . ' = \'' . $mysqli->real_escape_string($value) . '\'';
		}
	}, array_keys($params), array_values($params));

	if ($where) {
		$where = array_map(function ($key, $value) {
			global $mysqli;
			if (substr($value, 0, 2) === '!=') {
				$sign = '!=';
				$value = trim(substr($value, 2));
			} elseif (substr($value, 0, 1) === '=') {
				$sign = '=';
				$value = trim(substr($value, 1));
			} else {
				$sign = '=';
				$value = trim($value);
			}

			return '`' . $key . '`' . ' ' . $sign . ' \'' . $mysqli->real_escape_string($value) . '\'';
		}, array_keys($where), array_values($where));
	} else {
		$where = array('1 = 1');
	}

	$sql = 'UPDATE ' . DBPREFIX . $table .
			' SET ' . implode(', ', $params) .
			' WHERE ' . implode(' AND ', $where);

	$result = $mysqli->query($sql);

	if ($mysqli->errno) {
		echo('Error: ' . $mysqli->errno . chr(10) . $mysqli->error . chr(10));
		echo($sql . chr(10));
		$mysqli->rollback();
		die();
	}

	return $result;
}

/**
 * SQL実行
 */
function insertBlockSettings($table, $params, $key = array(), $blockSetting = false) {
	global $blockKey, $pluginKey;

	if ($blockSetting) {
		foreach ($params as $fieldName => $value) {
			if (!is_array($value)) {
				$tmp = array('type' => 'boolean', 'value' => $value);
				$value  = $tmp;
			}
			$result = executeInsert('block_settings', array(
				'plugin_key' => Inflector::underscore($pluginKey),
				'room_id' => '1',
				'block_key' => $blockKey,
				'field_name' => $fieldName,
				'value' => $value['value'],
				'type' => $value['type'],
			));
		}
	} else {
		if ($key) {
			$params = array_merge($params, $key);
		} else {
			$params['block_key'] = $blockKey;
		}
		$result = executeInsert($table, $params);
	}

	return $result;
}
