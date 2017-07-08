<?php
/**
 * Controller/Componentのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controller/Componentのテストファイル生成クラス
 */
Class CreateControllerComponent4 extends CreateObject {

	/**
	 * デストラクター.
	 */
	function __destruct() {
		//親クラスの__destruct()を実行させないため
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestController($testControllerName, $process = array()) {
		$this->createTestPluginDir('Controller');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'AppController\', \'Controller\')',
				),
				$this->testFile['class'] . 'テスト用Controller'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\Test' . $this->plugin . '\\Controller',
				$this->testFile['class'] . 'テスト用Controller'
			) .
			'class ' . $testControllerName . 'Controller extends AppController {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
					'使用コンポーネント',
					'array',
					'public',
					'components',
					array(
						'array(',
						chr(9) . '\'' .  $this->plugin . '.' . substr($this->testFile['class'], 0, -1 * strlen('Component')) . '\'',
						');',
					)
			) .
			$this->_classMethod(
				'index',
				array(
					'@return void',
				),
				'index()',
				Hash::merge(array(
					'$this->autoRender = true;'
				), $process)
			) .
			'}' .
			'' . chr(10) .
			'';
		$this->createTestPluginFile('Controller/' . $testControllerName . 'Controller.php', $output);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestView($testControllerName) {
		$this->createTestPluginDir('View/' . $testControllerName);

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(),
				$this->testFile['class'] . 'テスト用Viewファイル'
			) .
			'?>' . chr(10) .
			'' . chr(10) .
			$this->testFile['dir'] . '/' . $testControllerName . '/index' . chr(10) .
			'';
		$this->createTestPluginFile('View/' . $testControllerName . '/index.ctp', $output);
	}

}

