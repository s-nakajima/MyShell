<?php
/**
 * ModelBehaviorのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * ModelBehaviorのテストファイル生成クラス
 */
Class CreateModelBehavior4 extends CreateObject {

	/**
	 * デストラクター.
	 */
	function __destruct() {
		//親クラスの__destruct()を実行させないため
	}

	/**
	 * Phpdoc ClassVariable
	 *
	 * @return string
	 */
	protected function _createTestModel($testModelName, $function = '', $useTable = false) {
		$this->createTestPluginDir('Model');

		if ($function) {
			$phpDescription = '::' . $function . '()';
		} else {
			$phpDescription = '';
		}
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'AppModel\', \'Model\')',
				),
				$this->testFile['class'] . $phpDescription . 'テスト用Model'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\Test' . $this->plugin . '\\Model',
				$this->testFile['class'] . $phpDescription . 'テスト用Model'
			) .
			'class ' . $testModelName . ' extends AppModel {' . chr(10) .
			'' . chr(10);

		if (! $useTable) {
			$output .=
				$this->_classVariable(
					'テーブル名',
					'mixed',
					'public',
					'useTable',
					array('false;')
				);
		}

		$output .=
			$this->_classVariable(
				'使用ビヘイビア',
				'array',
				'public',
				'actsAs',
				array(
					'array(',
					chr(9) . '\'' .  $this->plugin . '.' . substr($this->testFile['class'], 0, -1 * strlen('Behavior')) . '\'',
					');',
				)
			) .
			'}' .
			'' . chr(10) .
			'';
		$this->createTestPluginFile('Model/' . $testModelName . '.php', $output);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createFixture($testModelName, $function = '') {
		if ($function) {
			$phpDescription = '::' . $function . '()';
		} else {
			$phpDescription = '';
		}

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(),
				$this->testFile['class'] . $phpDescription . 'テスト用Fixture'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\Fixture',
				$this->testFile['class'] . $phpDescription . 'テスト用Fixture'
			) .
			'class ' . $testModelName . 'Fixture extends CakeTestFixture {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
				'Fields',
				'array', 'public', 'fields', array(
					'array(',
					chr(9) . '\'id\' => array(\'type\' => \'integer\', \'null\' => false, \'default\' => null, \'unsigned\' => false, \'key\' => \'primary\', \'comment\' => \'\'),',
					chr(9) . '\'language_id\' => array(\'type\' => \'integer\', \'null\' => false, \'default\' => null, \'length\' => 6, \'unsigned\' => false),',
					chr(9) . '\'key\' => array(\'type\' => \'string\', \'null\' => false, \'default\' => null, \'collate\' => \'utf8_general_ci\', \'comment\' => \'\', \'charset\' => \'utf8\'),',
					chr(9) . '\'status\' => array(\'type\' => \'integer\', \'null\' => false, \'default\' => null, \'length\' => 4, \'unsigned\' => false, \'comment\' => \'\'),',
					chr(9) . '\'is_active\' => array(\'type\' => \'boolean\', \'null\' => false, \'default\' => \'0\', \'comment\' => \'\'),',
					chr(9) . '\'is_latest\' => array(\'type\' => \'boolean\', \'null\' => false, \'default\' => \'0\', \'comment\' => \'\'),',
					chr(9) . '\'content\' => array(\'type\' => \'text\', \'null\' => true, \'default\' => null, \'collate\' => \'utf8_general_ci\', \'comment\' => \'\', \'charset\' => \'utf8\'),',
					chr(9) . '\'created_user\' => array(\'type\' => \'integer\', \'null\' => true, \'default\' => \'0\', \'unsigned\' => false, \'comment\' => \'\'),',
					chr(9) . '\'created\' => array(\'type\' => \'datetime\', \'null\' => true, \'default\' => null, \'comment\' => \'\'),',
					chr(9) . '\'modified_user\' => array(\'type\' => \'integer\', \'null\' => true, \'default\' => \'0\', \'unsigned\' => false, \'comment\' => \'\'),',
					chr(9) . '\'modified\' => array(\'type\' => \'datetime\', \'null\' => true, \'default\' => null, \'comment\' => \'\'),',
					chr(9) . '\'indexes\' => array(',
					chr(9) . chr(9) . '\'PRIMARY\' => array(\'column\' => \'id\', \'unique\' => 1),',
					chr(9) . '),',
					chr(9) . '\'tableParameters\' => array(\'charset\' => \'utf8\', \'collate\' => \'utf8_general_ci\', \'engine\' => \'InnoDB\'),',
					');',
				)
			) .
			$this->_classVariable(
				'Records',
				'array', 'public', 'records',
				array(
					'array(',
					chr(9) . 'array(',
					chr(9) . chr(9) . '\'id\' => \'1\',',
					chr(9) . chr(9) . '\'language_id\' => \'2\',',
					chr(9) . chr(9) . '\'key\' => \'not_publish_key\',',
					chr(9) . chr(9) . '\'status\' => \'3\',',
					chr(9) . chr(9) . '\'is_active\' => false,',
					chr(9) . chr(9) . '\'is_latest\' => true,',
					chr(9) . chr(9) . '\'content\' => \'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.\',',
					chr(9) . chr(9) . '\'created_user\' => \'1\',',
					chr(9) . chr(9) . '\'created\' => \'' . date('Y-m-d H:i:s') . '\',',
					chr(9) . chr(9) . '\'modified_user\' => \'1\',',
					chr(9) . chr(9) . '\'modified\' => \'' . date('Y-m-d H:i:s') . '\'',
					chr(9) . '),',
					chr(9) . 'array(',
					chr(9) . chr(9) . '\'id\' => \'2\',',
					chr(9) . chr(9) . '\'language_id\' => \'2\',',
					chr(9) . chr(9) . '\'key\' => \'publish_key\',',
					chr(9) . chr(9) . '\'status\' => \'1\',',
					chr(9) . chr(9) . '\'is_active\' => true,',
					chr(9) . chr(9) . '\'is_latest\' => true,',
					chr(9) . chr(9) . '\'content\' => \'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.\',',
					chr(9) . chr(9) . '\'created_user\' => \'1\',',
					chr(9) . chr(9) . '\'created\' => \'' . date('Y-m-d H:i:s') . '\',',
					chr(9) . chr(9) . '\'modified_user\' => \'1\',',
					chr(9) . chr(9) . '\'modified\' => \'' . date('Y-m-d H:i:s') . '\'',
					chr(9) . '),',
					');',
				)
			) .
			'}' .
			'' . chr(10) .
			'';
		$this->createFile(PLUGIN_ROOT_DIR . 'Test/Fixture/' . $testModelName . 'Fixture.php', $output);
	}

}

