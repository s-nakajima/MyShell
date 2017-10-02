<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
require 'create_common.php';

switch (Inflector::underscore($pluginKey)) {
	case 'access_counters':
	//case 'announcements':
	case 'bbses':
	case 'blogs':
	case 'cabinets':
	case 'calendars':
	//case 'circular_notices':
	case 'faqs':
	case 'iframes':
	case 'links':
	//case 'photo_albums':
	//case 'questionnaires':
	//case 'quizzes':
	//case 'registrations':
	//case 'rss_readers':
	case 'videos':
	case 'tasks':
		break;
	default:
		exit;
}

echo 'Start ' . $pluginKey . chr(10);

/**
 * framesの取得
 */
$frameKey = md5('frame_' . $pluginKey . time());
$where = array(
	'room_id' => '1',
	'plugin_key' => Inflector::underscore($pluginKey),
	'is_deleted' => '0',
);
$result = executeSelect('frames', $where);
foreach ($result as $row) {
	$frame = $row;
	break;
}
//$result->free();
if (empty($frame)) {
	echo 'Not empty.';
	exit;
}

//トランザクション開始
//$db->autocommit(false);
$db->beginTransaction();

/**
 * プラグインデータ取得
 */
$where = array(
	'key' => Inflector::underscore($pluginKey),
	'language_id' => '2',
);
$result = executeSelect('plugins', $where);
foreach ($result as $row) {
	$plugin = $row;
	break;
}
//$result->free();

$blockKey = md5('block_' . $pluginKey . time());

/**
 * blocksのデータ生成
 */
if (Inflector::underscore($pluginKey) !== 'calendars') {
	$params = array(
		'room_id' => '1',
		'plugin_key' => Inflector::underscore($pluginKey),
		'key' => $blockKey,
	);
	executeInsert('blocks', $params);

	$blockId = $db->lastInsertId();

	$params = array(
		'language_id' => '2',
		'block_id' => $blockId,
		'name' => 'Block - ' . $plugin['name'],
	);
	executeInsert('blocks_languages', $params);
} else {
	$blockId = null;
}

/**
 * framesの更新
 */
$where = array(
	'room_id' => '1',
	'plugin_key' => Inflector::underscore($pluginKey),
	'is_deleted' => '0',
);
$params = array(
	'block_id' => $blockId,
	'key' => $frameKey,
	//'name' => 'Frame - ' . $plugin['name']
);
executeUpdate('frames', $params, $where);

/**
 * 各プラグインデータ登録
 */
switch (Inflector::underscore($pluginKey)) {
	case 'access_counters':
		$params = array(
			'block_key' => $blockKey,
		);
		executeInsert('access_counters', $params);

		break;

	case 'bbses':
		$bbsKey = md5('bbs_' . $pluginKey . time());
		$params = array(
			'key' => $bbsKey,
			'block_id' => $blockId,
			'name' => 'Block - ' . $plugin['name'],
			'language_id' => '2',
		);
		executeInsert('bbses', $params);

		$params = array(
			'use_workflow' => '1',
			'use_comment' => '1',
			'use_comment_approval' => '1',
			'use_like' => '1',
			'use_unlike' => '1',
		);
		insertBlockSettings('bbs_settings', $params, array('bbs_key' => $bbsKey), true);

		break;

	case 'blogs':
		$blogKey = md5('blog_' . $pluginKey . time());
		$params = array(
			'key' => $blogKey,
			'block_id' => $blockId,
			'name' => 'Block - ' . $plugin['name'],
			'language_id' => '2',
		);
		executeInsert('blogs', $params);

		$params = array(
			'use_workflow' => '1',
			'use_comment' => '1',
			'use_comment_approval' => '1',
			'use_like' => '1',
			'use_unlike' => '1',
			'use_sns' => '1',
		);
		insertBlockSettings('blog_settings', $params, array('blog_key' => $blogKey), true);

		break;

	case 'cabinets':
		$cabinetKey = md5('cabinet_' . $pluginKey . time());
		$params = array(
			'key' => $cabinetKey,
			'block_id' => $blockId,
			'name' => 'Block - ' . $plugin['name'],
		);
		executeInsert('cabinets', $params);
		$cabinetId = $db->lastInsertId();
		$params = array(
			'use_workflow' => '1',
		);
		insertBlockSettings('cabinet_settings', $params, array('cabinet_key' => $cabinetKey), true);
		$cabinetFileKey = md5('cabinet_file_' . $pluginKey . time());
		$params = array(
			'cabinet_key' => $cabinetKey,
			'key' => $cabinetFileKey,
			'is_folder' => '1',
			'status' => '1',
			'is_active' => '1',
			'is_latest' => '1',
			'language_id' => '2',
			'filename' => 'Block - ' . $plugin['name'],
			'cabinet_file_tree_id' => '1',
		);
		executeInsert('cabinet_files', $params);
		$cabinetFileId = $db->lastInsertId();
		$params = array(
			'cabinet_key' => $cabinetKey,
			'cabinet_file_key' => $cabinetFileKey,
			'lft' => '1',
			'rght' => '2',
		);
		executeInsert('cabinet_file_trees', $params);

		break;

	case 'calendars':
		$params = array(
			'frame_key' => $frameKey,
			'display_type' => '2',
		);
		executeInsert('calendar_frame_settings', $params);

		$params = array(
			'use_workflow' => '1',
		);
		insertBlockSettings('calendars', $params, [], true);

		break;

	case 'faqs':
		$faqKey = md5('fag_' . $pluginKey . time());
		$params = array(
			'key' => $faqKey,
			'block_id' => $blockId,
			'name' => 'Block - ' . $plugin['name'],
			'language_id' => '2',
		);
		executeInsert('faqs', $params);

		$params = array(
			'use_workflow' => '1',
			'use_like' => '1',
			'use_unlike' => '1',
		);
		insertBlockSettings('faq_settings', $params, array('faq_key' => $faqKey), true);

		break;

	case 'iframes':
		$params = array(
			'key' => md5('iframe_' . $pluginKey . time()),
			'block_id' => $blockId,
			'url' => 'http://www.netcommons.org',
		);
		executeInsert('iframes', $params);

		break;

	case 'links':
		$params = array(
			'use_workflow' => '1',
		);
		insertBlockSettings('link_settings', $params, [], true);

		break;

	case 'videos':
		$params = array(
			'use_workflow' => ['type' => 'boolean', 'value' => '1'],
			'use_comment' => ['type' => 'boolean', 'value' => '1'],
			'use_comment_approval' => ['type' => 'boolean', 'value' => '1'],
			'use_like' => ['type' => 'boolean', 'value' => '1'],
			'use_unlike' => ['type' => 'boolean', 'value' => '1'],
			'auto_play' => ['type' => 'boolean', 'value' => '0'],
			//'total_size' => ['type' => 'numeric', 'value' => '0'],
		);
		insertBlockSettings('video_settings', $params, [], true);

		$params = array(
			'block_key' => $blockKey,
		);
		executeInsert('video_settings', $params);

		break;

	case 'tasks':
		$taskKey = md5('task_' . $pluginKey . time());
		$params = array(
			'key' => $taskKey,
			'block_id' => $blockId,
			'name' => 'Block - ' . $plugin['name'],
			'language_id' => '2',
		);
		executeInsert('tasks', $params);

		$params = array(
			'use_workflow' => '1',
			'use_comment' => '1',
			'use_comment_approval' => '1',
		);
		insertBlockSettings('task_settings', $params, array('task_key' => $taskKey), true);

		break;
}

$db->commit();

/* 結果セットを開放します */
//$db->close();

echo 'End ' . $pluginKey . chr(10);
