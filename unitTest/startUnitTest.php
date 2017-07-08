<?php
/**
 * UnitTest用ファイル作成ソース
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

error_reporting(E_ALL);

require __DIR__ . '/common/common.php';

output('##################################');
output(' UnitTest用ファイル作成開始します ');
output('##################################');

(new Plugin())->load();
