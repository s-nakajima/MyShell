<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

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
#-- pages
#-- --------------------
INSERT INTO " . DBPREFIX . "pages(root_id, parent_id, room_id, lft, rght, permalink, slug)
SELECT 1, 1, 1, (@i := @i + 1), (@i := @i + 1), concat('slug_', `key`), concat('slug_', `key`)
FROM (select @i:=max(rght) from " . DBPREFIX . "pages where parent_id = 1) as dummy, `" . DBPREFIX . "plugins` as plugins
WHERE plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;

UPDATE " . DBPREFIX . "pages as pages, (select max(rght)+1 as max_rght from " . DBPREFIX . "pages where parent_id = 1) as dummy
SET pages.rght = dummy.max_rght WHERE id = 1;

UPDATE " . DBPREFIX . "pages as pages, (select max(rght)+1 as max_rght from " . DBPREFIX . "pages) as dummy
SET pages.lft = dummy.max_rght WHERE id = 2;

UPDATE " . DBPREFIX . "pages as pages, (select max(rght)+1 as max_rght from " . DBPREFIX . "pages) as dummy
SET pages.lft = dummy.max_rght, pages.rght = dummy.max_rght + 1 WHERE id = 5;

UPDATE " . DBPREFIX . "pages as pages, (select max(rght)+1 as max_rght from " . DBPREFIX . "pages) as dummy
SET pages.rght = dummy.max_rght WHERE id = 2;

UPDATE " . DBPREFIX . "pages as pages, (select max(rght)+1 as max_rght from " . DBPREFIX . "pages) as dummy
SET pages.lft = dummy.max_rght, pages.rght = dummy.max_rght + 1 WHERE id = 3;


#-- --------------------
#-- languages_pages
#-- --------------------
INSERT INTO " . DBPREFIX . "pages_languages(language_id, page_id, name)
SELECT plugins.language_id, pages.id, plugins.name
FROM " . DBPREFIX . "pages as pages, " . DBPREFIX . "plugins as plugins
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;


#-- --------------------
#-- page_containers
#-- --------------------
INSERT INTO " . DBPREFIX . "page_containers(page_id, container_type, is_published)
SELECT pages.id, 1, 1
FROM " . DBPREFIX . "pages as pages, " . DBPREFIX . "plugins as plugins 
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.language_id = '2';

INSERT INTO " . DBPREFIX . "page_containers(page_id, container_type, is_published)
SELECT pages.id, 2, 1
FROM " . DBPREFIX . "pages as pages, " . DBPREFIX . "plugins as plugins 
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.language_id = '2';

INSERT INTO " . DBPREFIX . "page_containers(page_id, container_type, is_published)
SELECT pages.id, 3, 1
FROM " . DBPREFIX . "pages as pages, " . DBPREFIX . "plugins as plugins 
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.language_id = '2';

INSERT INTO " . DBPREFIX . "page_containers(page_id, container_type, is_published)
SELECT pages.id, 4, 1
FROM " . DBPREFIX . "pages as pages, " . DBPREFIX . "plugins as plugins 
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.language_id = '2';

INSERT INTO " . DBPREFIX . "page_containers(page_id, container_type, is_published)
SELECT pages.id, 5, 1
FROM " . DBPREFIX . "pages as pages, " . DBPREFIX . "plugins as plugins 
WHERE pages.slug = concat('slug_', plugins.key)
AND plugins.language_id = '2';



#-- --------------------
#-- boxes
#-- --------------------
INSERT INTO " . DBPREFIX . "boxes(container_type, `type`, space_id, room_id, page_id)
SELECT page_containers.container_type, 4, 2, 1, page_containers.page_id
FROM " . DBPREFIX . "plugins as plugins, 
" . DBPREFIX . "page_containers as page_containers, 
" . DBPREFIX . "pages as pages 
WHERE page_containers.page_id = pages.id
AND pages.slug = concat('slug_', plugins.key)
AND plugins.type = 1
AND plugins.key != 'menus'
AND plugins.language_id = '2'
ORDER BY plugins.id;


#-- --------------------
#-- boxes_page_containers
#-- --------------------

INSERT INTO " . DBPREFIX . "boxes_page_containers(page_container_id, page_id, container_type, box_id, is_published, weight)
SELECT 
  page_containers.id, 
  page_containers.page_id, 
  page_containers.container_type, 
  boxes.id, 
  1,
  1
FROM " . DBPREFIX . "page_containers AS page_containers, 
" . DBPREFIX . "pages AS pages,
" . DBPREFIX . "boxes AS boxes
WHERE page_containers.page_id = pages.id
AND pages.slug LIKE 'slug_%'
AND boxes.room_id = 1
AND boxes.type = 1
AND page_containers.container_type = boxes.container_type;

INSERT INTO " . DBPREFIX . "boxes_page_containers(page_container_id, page_id, container_type, box_id, is_published, weight)
SELECT 
  page_containers.id, 
  page_containers.page_id, 
  page_containers.container_type, 
  boxes.id, 
  0,
  boxes.`type`
FROM " . DBPREFIX . "page_containers AS page_containers, 
" . DBPREFIX . "pages AS pages,
" . DBPREFIX . "boxes AS boxes
WHERE page_containers.page_id = pages.id
AND pages.slug LIKE 'slug%'
AND boxes.room_id = 1
AND boxes.type != 1 
AND boxes.page_id IS NULL
AND boxes.container_type != 3
AND page_containers.container_type = boxes.container_type;

INSERT INTO " . DBPREFIX . "boxes_page_containers(page_container_id, page_id, container_type, box_id, is_published, weight)
SELECT 
  page_containers.id, 
  page_containers.page_id, 
  page_containers.container_type, 
  boxes.id, 
  0,
  boxes.`type`
FROM " . DBPREFIX . "page_containers AS page_containers, 
" . DBPREFIX . "pages AS pages,
" . DBPREFIX . "boxes AS boxes
WHERE page_containers.page_id = pages.id
AND pages.slug LIKE 'slug%'
AND boxes.room_id = 1
AND boxes.type != 1 
AND boxes.container_type != 3
AND page_containers.page_id = boxes.page_id
AND page_containers.container_type = boxes.container_type;

INSERT INTO " . DBPREFIX . "boxes_page_containers(page_container_id, page_id, container_type, box_id, is_published, weight)
SELECT 
  page_containers.id, 
  page_containers.page_id, 
  page_containers.container_type, 
  boxes.id, 
  1,
  1
FROM " . DBPREFIX . "page_containers AS page_containers, 
" . DBPREFIX . "pages AS pages,
" . DBPREFIX . "boxes AS boxes
WHERE page_containers.page_id = pages.id
AND pages.slug LIKE 'slug%'
AND boxes.room_id = 1
AND boxes.type != 1 
AND boxes.container_type = 3
AND page_containers.page_id = boxes.page_id
AND page_containers.container_type = boxes.container_type;


#-- --------------------
#-- frames
#-- --------------------
INSERT INTO " . DBPREFIX . "frames (room_id, box_id, plugin_key, `key`, header_type, weight, is_deleted)
SELECT 1, boxes.id, plugins.key, MD5(plugins.key), 'default', 1, 0
FROM (select @i:=2) as dummy, " . DBPREFIX . "plugins as plugins
INNER JOIN " . DBPREFIX . "pages as pages ON (pages.slug = concat('slug_', plugins.key))
INNER JOIN " . DBPREFIX . "boxes as boxes ON (pages.id = boxes.page_id)
WHERE boxes.type = 4 AND boxes.container_type = 3
AND plugins.key != 'menus'
AND plugins.language_id = '2';

INSERT INTO " . DBPREFIX . "frames_languages (language_id, frame_id, name, is_origin)
SELECT plugins.language_id, frames.id, CONCAT('Frame - ', plugins.name), 1
FROM (select @i:=2) as dummy, " . DBPREFIX . "plugins as plugins
INNER JOIN " . DBPREFIX . "pages as pages ON (pages.slug = concat('slug_', plugins.key))
INNER JOIN " . DBPREFIX . "boxes as boxes ON (pages.id = boxes.page_id)
INNER JOIN " . DBPREFIX . "frames as frames ON (frames.`key` = MD5(plugins.key))
WHERE boxes.type = 4 AND boxes.container_type = 3
AND plugins.key != 'menus'
AND plugins.language_id = '2';

INSERT INTO " . DBPREFIX . "frame_public_languages (language_id, frame_id, is_public)
SELECT 0, frames.id, 1
FROM (select @i:=2) as dummy, " . DBPREFIX . "plugins as plugins
INNER JOIN " . DBPREFIX . "pages as pages ON (pages.slug = concat('slug_', plugins.key))
INNER JOIN " . DBPREFIX . "boxes as boxes ON (pages.id = boxes.page_id)
INNER JOIN " . DBPREFIX . "frames as frames ON (frames.`key` = MD5(plugins.key))
WHERE boxes.type = 4 AND boxes.container_type = 3
AND plugins.key != 'menus'
AND plugins.language_id = '2';


#-- --------------------
#-- groups
#-- --------------------
INSERT INTO " . DBPREFIX . "groups(id, name, created_user)
VALUES
('1', 'グループ 1', '1'),
('2', 'グループ 2', '2'),
('3', 'グループ 3', '1');


#-- --------------------
#-- groups_users
#-- --------------------
INSERT INTO " . DBPREFIX . "groups_users(id, group_id, user_id, created_user)
VALUES
('1', '1', '2', '1'),
('2', '1', '3', '1'),
('3', '2', '1', '2'),
('4', '2', '3', '2'),
('5', '3', '2', '1');
";

$sqls = explode(';', $sqlAll);
foreach ($sqls as $sql) {
	if (trim($sql)) {
		$result = $mysqli->query($sql);
		if ($mysqli->errno) {
			echo('Error: ' . $mysqli->errno . chr(10) . $mysqli->error . chr(10));
			echo($sql . chr(10));
			//$mysqli->rollback();
			//die();
		}
	}
}

$mysqli->commit();

/* 結果セットを開放します */
$mysqli->close();

