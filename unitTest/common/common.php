<?php

$rootDir = getenv('ROOT_DIR');
if (!empty($rootDir)) {
	define('ROOT_DIR', $rootDir);
} else {
	define('ROOT_DIR', '/var/www/app/');
	//define('ROOT_DIR', '/var/www/html/nc3/');
}

define('PLUGIN_ROOT_DIR', ROOT_DIR . 'app/' . getenv('PLUGIN_DIR') . '/' . getenv('PLUGIN_NAME') . '/');
define('PLUGIN_TEST_DIR', ROOT_DIR . 'app/' . getenv('PLUGIN_DIR') . '/' . getenv('PLUGIN_NAME') . '/Test/Case/');
define('PLUGIN_TEST_PLUGIN', ROOT_DIR . 'app/' . getenv('PLUGIN_DIR') . '/' . getenv('PLUGIN_NAME') . '/Test/test_app/Plugin/Test' . getenv('PLUGIN_NAME') . '/');
define('DS', '/');
define('TMP', ROOT_DIR . 'app/tmp/');

require __DIR__ . '/Inflector.php';
require __DIR__ . '/Folder.php';
require __DIR__ . '/File.php';
require __DIR__ . '/CakeText.php';
require __DIR__ . '/Hash.php';

require dirname(__DIR__) . '/Plugin/Plugin.php';
require dirname(__DIR__) . '/Plugin/Create.php';
require dirname(__DIR__) . '/Plugin/CreateConfig.php';
require dirname(__DIR__) . '/Plugin/CreateConsoleCommand.php';
require dirname(__DIR__) . '/Plugin/CreateConsoleCommandTask.php';
require dirname(__DIR__) . '/Plugin/CreateController.php';
require dirname(__DIR__) . '/Plugin/CreateController4.php';
require dirname(__DIR__) . '/Plugin/CreateController4AppController.php';
require dirname(__DIR__) . '/Plugin/CreateController4Workflow.php';
require dirname(__DIR__) . '/Plugin/CreateController4WorkflowControllerIndex.php';
require dirname(__DIR__) . '/Plugin/CreateController4WorkflowControllerView.php';
require dirname(__DIR__) . '/Plugin/CreateController4WorkflowControllerEdit.php';
require dirname(__DIR__) . '/Plugin/CreateController4WorkflowControllerAdd.php';
require dirname(__DIR__) . '/Plugin/CreateController4WorkflowControllerDelete.php';
require dirname(__DIR__) . '/Plugin/CreateController4FrameSettingsController.php';
require dirname(__DIR__) . '/Plugin/CreateController4MailSettingsController.php';
require dirname(__DIR__) . '/Plugin/CreateController4Blocks.php';
require dirname(__DIR__) . '/Plugin/CreateController4BlocksController.php';
require dirname(__DIR__) . '/Plugin/CreateController4BlocksPaginatorController.php';
require dirname(__DIR__) . '/Plugin/CreateController4BlocksControllerEdit.php';
require dirname(__DIR__) . '/Plugin/CreateController4BlockPermissionControllerEdit.php';
require dirname(__DIR__) . '/Plugin/CreateController4EditController.php';
require dirname(__DIR__) . '/Plugin/CreateController4AddController.php';
require dirname(__DIR__) . '/Plugin/CreateController4OtherController.php';
require dirname(__DIR__) . '/Plugin/CreateController4UserRolePermission.php';
require dirname(__DIR__) . '/Plugin/CreateControllerComponent.php';
require dirname(__DIR__) . '/Plugin/CreateControllerComponent4.php';
require dirname(__DIR__) . '/Plugin/CreateControllerComponent4Event.php';
require dirname(__DIR__) . '/Plugin/CreateControllerComponent4Other.php';
require dirname(__DIR__) . '/Plugin/CreateModel.php';
require dirname(__DIR__) . '/Plugin/CreateModel4.php';
require dirname(__DIR__) . '/Plugin/CreateModel4Delete.php';
require dirname(__DIR__) . '/Plugin/CreateModel4Event.php';
require dirname(__DIR__) . '/Plugin/CreateModel4Get.php';
require dirname(__DIR__) . '/Plugin/CreateModel4Other.php';
require dirname(__DIR__) . '/Plugin/CreateModel4Save.php';
require dirname(__DIR__) . '/Plugin/CreateModel4Scope.php';
require dirname(__DIR__) . '/Plugin/CreateModel4Validate.php';
require dirname(__DIR__) . '/Plugin/CreateModelBehavior.php';
require dirname(__DIR__) . '/Plugin/CreateModelBehavior4.php';
require dirname(__DIR__) . '/Plugin/CreateModelBehavior4Event.php';
require dirname(__DIR__) . '/Plugin/CreateModelBehavior4Other.php';
require dirname(__DIR__) . '/Plugin/CreateModelBehavior4Scope.php';
require dirname(__DIR__) . '/Plugin/CreateOther.php';
require dirname(__DIR__) . '/Plugin/CreateTestSuite.php';
require dirname(__DIR__) . '/Plugin/CreateViewElements.php';
require dirname(__DIR__) . '/Plugin/CreateViewHelper.php';
require dirname(__DIR__) . '/Plugin/CreateViewHelper4Event.php';
require dirname(__DIR__) . '/Plugin/CreateViewHelper4Scope.php';

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
