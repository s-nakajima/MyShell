<?php

$dbname = $argv[1];
if (isset($argv[2])) {
	define('DBPREFIX', trim($argv[2]));
} else {
	define('DBPREFIX', '');
}

$mysqli = new mysqli('localhost', 'root', 'root', $dbname);
if ($mysqli->connect_errno) {
	echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
	exit;
}
$result = $mysqli->query('SET NAMES utf8;');

//サイト全体のルームID
$result = $mysqli->query('SELECT id FROM ' . DBPREFIX . 'rooms WHERE space_id = 1');
$wholeSiteRoom = $result->fetch_assoc();

//トランザクション開始
$mysqli->autocommit(false);
$mysqli->begin_transaction();

$sqlAll = "
SET NAMES utf8;

#-- --------------------
#-- roles_rooms_users
#-- --------------------
DELETE FROM " . DBPREFIX . "roles_rooms_users 
WHERE user_id IN (2, 3, 4, 5) 
AND room_id IN (1, 2, 3, " . $wholeSiteRoom['id'] . ");

INSERT INTO " . DBPREFIX . "roles_rooms_users (roles_room_id, user_id, room_id)
SELECT roles_rooms.id, users.id, roles_rooms.room_id
FROM " . DBPREFIX . "roles_rooms as roles_rooms
INNER JOIN " . DBPREFIX . "users as users ON (roles_rooms.role_key = users.username)
WHERE roles_rooms.room_id IN (1, 2, 3, " . $wholeSiteRoom['id'] . ");

#-- --------------------
#-- users
#-- --------------------
UPDATE " . DBPREFIX . "users SET email = '';

#-- --------------------
#-- site_settings
#-- --------------------
UPDATE `" . DBPREFIX . "site_settings` SET `value` = '2' WHERE `key` = 'debug';
";

$sqls = explode(';', $sqlAll);
foreach ($sqls as $sql) {
	if (trim($sql)) {
		$result = $mysqli->query($sql);
		if ($mysqli->errno) {
			echo('Error: ' . $mysqli->errno . chr(10) . $mysqli->error . chr(10));
			echo($sql . chr(10));
			$mysqli->rollback();
			die();
		}
	}
}

$mysqli->commit();

/* 結果セットを開放します */
$mysqli->close();

