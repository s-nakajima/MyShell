<?php
/**
 * View/Elementsのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * View/Elementsのテストファイル生成クラス
 */
Class CreateViewElements extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## View/Elementsのテストコード生成(%s)', $testFile['dir']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * View/Elementsのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		if ($this->isBlockRolePermission($this->testFile)) {
			return;
		}

		if (substr($this->testFile['dir'], strlen('View/Elements') + 1) === '') {
			$testControllerName = 'Test' . $this->plugin . Inflector::camelize(ucfirst($this->testFile['file']));
		} else {
			$testControllerName = 'Test' . Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) . Inflector::camelize(ucfirst($this->testFile['file']));
		}

		output('---------------------' . chr(10));
		output(sprintf('#### テストファイル生成  %s', $this->testFile['dir'] . '/' . $this->testFile['file']) . chr(10));

		$this->_create($testControllerName, array($this->testFile['file']));

		$this->_createTestController($testControllerName);
		$this->_createTestView($testControllerName);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return void
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
				$this->testFile['dir'] . '/' . $this->testFile['file'] . 'テスト用Controller'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\Test' . $this->plugin . '\\Controller',
				$this->testFile['dir'] . '/' . $this->testFile['file'] . 'テスト用Controller'
			) .
			'class ' . $testControllerName . 'Controller extends AppController {' . chr(10) .
			'' . chr(10) .
			$this->_classMethod(
				$this->testFile['file'],
				array(
					'@return void',
				),
				$this->testFile['file'] . '()',
				array(
					'$this->autoRender = true;'
				)
			) .
			'}' . chr(10) .
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

		if ($this->testFile['dir'] === 'View/Elements') {
			$element = '$this->element(\'' . $this->plugin . '.' . $this->testFile['file'] . '\');';
		} else {
			$element = '$this->element(\'' .
				$this->plugin . '.' . substr($this->testFile['dir'], strlen('View/Elements') + 1) . '/' . $this->testFile['file'] .
			'\');';
		}

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(),
				$this->testFile['dir'] . '/' . $this->testFile['file'] . 'テスト用Viewファイル'
			) .
			'?>' . chr(10) .
			'' . chr(10) .
			$this->testFile['dir'] . '/' . $this->testFile['file'] . chr(10) .
			'' . chr(10) .
			'<?php echo ' . $element . chr(10) .
			'';
		$this->createTestPluginFile('View/' . $testControllerName . '/' . $this->testFile['file'] . '.ctp', $output);

	}

	/**
	 * Controller/Component::xxxxx()のテストコード生成
	 *
	 * @param string $testControllerName Testコントローラ名
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _create($testControllerName, $param) {
		$element = $param[0];
		if (substr($this->testFile['dir'], strlen('View/Elements') + 1) === '') {
			$className = $this->plugin . Inflector::camelize(ucfirst($this->testFile['file'])) . 'Test';
		} else {
			$className = Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) . Inflector::camelize(ucfirst($this->testFile['file'])) . 'Test';
		}
		$this->createNetCommonsTestCaseFile('NetCommonsControllerTestCase');
		$testSuitePlugin = $this->plugin . '.TestSuite';
		$testSuiteTest = $this->plugin . 'ControllerTestCase';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$element,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
				),
				$this->testFile['dir'] . '/' . $element . 'のテスト'
			) .
			$this->_phpdocClassHeader(
				$element,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' . strtr($this->testFile['dir'], '/', '\\') . '\\' . Inflector::camelize(ucfirst($element)),
				$this->testFile['dir'] . '/' . $element . 'のテスト'
			) .
			'class ' . $this->plugin . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
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
					'//テストコントローラ生成',
					'$this->generateNc(\'Test' . $this->plugin . '.' . $testControllerName . '\');',
				)
			) .
			$this->_classMethod(
				$this->testFile['dir'] . '/' . $element . 'のテスト',
				array(
					'@return void',
				),
				'test' . Inflector::camelize(ucfirst($element)) . '()',
				array(
					'//テスト実行',
					'$this->_testGetAction(',
					chr(9) . '\'/' .
						Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/' . $element . '\',',
					chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\'',
					');',
					'',
					'//チェック',
					'$pattern = \'/\' . preg_quote(\'' . $this->testFile['dir'] . '/' . $element . '\', \'/\') . \'/\';',
					'$this->assertRegExp($pattern, $this->view);',
					'',
					'//TODO:必要に応じてassert追加する',
					'var_export($this->view);',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($element)) . 'Test.php', $output);
		$this->deleteFile('empty');
		$this->deleteFile(PLUGIN_TEST_DIR . 'View/Elements/empty');
	}

}

