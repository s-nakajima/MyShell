<?php
/**
 * View/Helperのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * View/Helperのテストファイル生成クラス
 */
Class CreateViewHelper4Event extends CreateObject {

	/**
	 * デストラクター.
	 */
	function __destruct() {
		//親クラスの__destruct()を実行させないため
	}

	/**
	 * View/Helperのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$testControllerName = 'Test' . $this->testFile['class'] . Inflector::camelize(ucfirst($param[0]));

		$this->_createTestController($testControllerName);
		$this->_createTestView($testControllerName);
		$this->_create($testControllerName, $param);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestController($testControllerName) {
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
					'使用ヘルパー',
					'array',
					'public',
					'helpers',
					array(
						'array(',
						chr(9) . '\'' .  $this->plugin . '.' . substr($this->testFile['class'], 0, -1 * strlen('Helper')) . '\'',
						');',
					)
			) .
			$this->_classMethod(
				'index',
				array(
					'@return void',
				),
				'index()',
				array(
					'$this->autoRender = true;'
				)
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
			$this->testFile['dir'] . '/' . $testControllerName . chr(10) .
			'';
		$this->createTestPluginFile('View/' . $testControllerName . '/index.ctp', $output);
	}

	/**
	 * View/Helper::xxxxx()のテストコード生成
	 *
	 * @param string $testControllerName Testコントローラ名
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _create($testControllerName, $param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$this->createNetCommonsTestCaseFile('NetCommonsControllerTestCase');
		$testSuitePlugin = $this->plugin . '.TestSuite';
		$testSuiteTest = $this->plugin . 'ControllerTestCase';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Controller\\Component\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
					'Fixtures',
					'array',
					'public',
					'fixtures',
					array(
						'array();',
					)
			) .
			$this->_classVariablePlugin() .
			$this->_classMethod(
				'setUp method',
				array(
					'@return void',
				),
				'setUp()',
				array(
					'parent::setUp();',
					'',
					'//テストプラグインのロード',
					'NetCommonsCakeTestCase::loadTestPlugin($this, \'' . $this->plugin . '\', \'Test' . $this->plugin . '\');',
				)
			) .
			$this->_classMethod(
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				array(
					'//テストコントローラ生成',
					'$this->generateNc(\'Test' . $this->plugin . '.' . $testControllerName . '\');',
					'',
					'//テスト実行',
					'$this->_testGetAction(\'/' .
						Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/index\',',
					chr(9) . chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\');',
					'',
					'//チェック',
					'$pattern = \'/\' . preg_quote(\'' . $this->testFile['dir'] . '/' . $testControllerName . '\', \'/\') . \'/\';',
					'$this->assertRegExp($pattern, $this->view);',
					'',
					'//cssのURLチェック',
					'//TODO:不要だったら削除する',
					'$pattern = \'/<link.*?\' . preg_quote(\'/' .
							Inflector::underscore($this->plugin) . '/css/style.css\', \'/\') . \'.*?>/\';',
					'//$this->assertRegExp($pattern, $this->contents);',
					'',
					'//scriptのURLチェック',
					'//TODO:不要だったら削除する',
					'$pattern = \'/<script.*?\' . preg_quote(\'/' .
							Inflector::underscore($this->plugin) . '/js/' . Inflector::underscore($this->plugin) . '.js\', \'/\') . \'.*?>/\';',
					'//$this->assertRegExp($pattern, $this->contents);',
					'',
					'//TODO:必要に応じてassert追加する',
					'var_export($this->view);',
					'',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
		$this->deleteFile(PLUGIN_TEST_DIR . 'View/Helper/empty');
	}

}

