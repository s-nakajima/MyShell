<?php
/**
 * TestSuiteのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * TestSuiteのテストファイル生成クラス
 */
Class CreateTestSuite extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## TestSuiteテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * TestSuiteのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$testClassName = 'TestSuite' . $this->testFile['class'];
		$functions = $this->getFunctions();

		$matches = array();
		if (preg_match('/^[A-Za-z0-9]+Controller([A-Za-z0-9]+)?Test$/', $this->testFile['class'], $matches)) {
			if (isset($matches[1])) {
				$action = Inflector::underscore(strtolower($matches[1]));
			} else {
				$action = 'index';
			}
			$this->_createTestController($testClassName, $action);
			$this->_createTestView($testClassName, $action);
			$this->_createTestSuiteControllerTestCase($testClassName, $functions);
		} else {
			$this->_createTestSuiteCakeTestCase($testClassName, $functions);
		}

		foreach ($functions as $param) {
			//if (in_array(strtolower($param[0]), ['setup', 'teardown'], true)) {
			//	continue;
			//}

			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if ($this->testFile['class'] === 'NetCommonsCakeTestCase') {
				$testSuitePlugin = 'TestSuite';
				$testSuiteTest = 'CakeTestCase';
			} elseif ($this->testFile['class'] === 'NetCommonsControllerTestCase') {
				$testSuitePlugin = 'TestSuite';
				$testSuiteTest = 'ControllerTestCase';
			} elseif (preg_match('/^[A-Za-z0-9]+Controller[A-Za-z0-9]+$/', $this->testFile['class'])) {
				$testSuitePlugin = 'NetCommons.TestSuite';
				$testSuiteTest = 'NetCommonsControllerTestCase';
			} else {
				$testSuitePlugin = 'NetCommons.TestSuite';
				$testSuiteTest = 'NetCommonsCakeTestCase';
			}

			$this->_create($param, $testClassName, $testSuitePlugin, $testSuiteTest);
		}
	}

	/**
	 * TestSuiteのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testClassName テストSuiteのクラス名
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	protected function _create($param, $testClassName, $testSuitePlugin, $testSuiteTest) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->testFile['dir'] . $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

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
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' . $this->testFile['dir'] . '\\' . Inflector::camelize(ucfirst($this->testFile['file']))
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
					'App::uses(\'' . $testClassName . '\', \'Test' . $this->plugin . '.TestSuite\');',
					'$this->TestSuite = new ' . $testClassName . '();',
				)
			);

		//メソッドの内容
		$processes = array();
		$processes[] = '//データ生成';
		$arguments = explode(', ', $argument);
		$methodArg = '';
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/^([\$_0-9a-zA-Z]+)( \= )?(.*)/', $arg, $matches)) {
				$processes[] = $matches[1] . ' = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', ' . $matches[1];
			}
		}
		$processes[] = '';
		$processes[] = '//テスト実施';
		$processes[] = '$result = $this->TestSuite->' . $function . '(' . substr($methodArg, 2) . ');';
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:assertを書く';
		if (substr($function, 0, strlen('dataProvider')) !== 'dataProvider' &&
				in_array($testSuiteTest, ['NetCommonsControllerTestCase', 'ControllerTestCase'], true)) {
			$processes[] = 'var_export($result->view);';
		} else {
			$processes[] = 'var_export($result);';
		}
		$output .= $this->_classMethod(
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				$processes
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

	/**
	 * _createTestSuiteController
	 *
	 * @return string
	 */
	protected function _createTestSuiteCakeTestCase($testClassName, $functions) {
		$this->createTestPluginDir('TestSuite');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'' . $this->testFile['class'] . '\', \'' . $this->plugin . '.TestSuite\')',
				),
				$this->testFile['class'] . 'テスト用TestSuite'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\Test' . $this->plugin . '\\TestSuite',
				$this->testFile['class'] . 'テスト用TestSuite'
			) .
			'class ' . $testClassName . ' extends ' . $this->testFile['class'] . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
				'Plugin name',
				'string',
				'public',
				'plugin',
				array(
					'\'test_' . Inflector::underscore($this->plugin). '\';',
				)
			);

		foreach ($functions as $function) {
			$output .=
				preg_replace('/@return .*/', '@return mixed テスト結果', $function[2]) . chr(10) .
				chr(9) . 'public function ' . $function[0] . '(' . $function[1] . ') {' . chr(10) .
				chr(9) . chr(9) . 'return parent::' . $function[0] . '(' . preg_replace('/ = ([_\'"\-\(\)a-zA-Z0-9])+/', '', $function[1]) . ');' . chr(10) .
				chr(9) . '}' . chr(10) .
				'' . chr(10);
		}

		$output .=
			'}' .
			'' . chr(10) .
			'';
		$this->createTestPluginFile('TestSuite/' . $testClassName . '.php', $output);
	}

	/**
	 * _createTestSuiteController
	 *
	 * @return string
	 */
	protected function _createTestSuiteControllerTestCase($testClassName, $functions) {
		$this->createTestPluginDir('TestSuite');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'' . $this->testFile['class'] . '\', \'' . $this->plugin . '.TestSuite\')',
				),
				$this->testFile['class'] . 'テスト用TestSuite'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\Test' . $this->plugin . '\\TestSuite',
				$this->testFile['class'] . 'テスト用TestSuite'
			) .
			'class ' . $testClassName . ' extends ' . $this->testFile['class'] . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
				'Plugin name',
				'string',
				'public',
				'plugin',
				array(
					'\'test_' . Inflector::underscore($this->plugin). '\';',
				)
			) .
			$this->_classVariable(
				'Controller name',
				'string',
				'protected',
				'_controller',
				array('\'' . $testClassName . '\';')
			);

		foreach ($functions as $function) {
			if (substr($function[0], 0, strlen('dataProvider')) === 'dataProvider') {
				$output .=
					preg_replace('/@return .*/', '@return mixed テスト結果', $function[2]) . chr(10) .
					chr(9) . 'public function ' . $function[0] . '(' . $function[1] . ') {' . chr(10) .
					chr(9) . chr(9) . 'return parent::' . $function[0] . '(' . preg_replace('/ = ([_\'"\-\(\)a-zA-Z0-9])+/', '', $function[1]) . ');' . chr(10) .
					chr(9) . '}' . chr(10) .
					'' . chr(10);
			} else {
				$output .=
					preg_replace('/@return .*/', '@return mixed テスト結果', $function[2]) . chr(10) .
					chr(9) . 'public function ' . $function[0] . '(' . $function[1] . ') {' . chr(10) .
					chr(9) . chr(9) . '//テストコントローラ生成' . chr(10) .
					chr(9) . chr(9) . '$this->generateNc(\'Test' . $this->plugin . '.' . $testClassName . '\');' . chr(10) .
					chr(9) . chr(9) . 'parent::' . $function[0] . '(' . preg_replace('/ = ([_\'"\-\(\)a-zA-Z0-9])+/', '', $function[1]) . ');' . chr(10) .
					chr(9) . chr(9) . 'return $this;' . chr(10) .
					chr(9) . '}' . chr(10) .
					'' . chr(10);
			}
		}

		$output .=
			'}' .
			'' . chr(10) .
			'';
		$this->createTestPluginFile('TestSuite/' . $testClassName . '.php', $output);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestController($testControllerName, $action) {
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
			$this->_classMethod(
				$action,
				array(
					'@return void',
				),
				$action . '()',
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
	protected function _createTestView($testControllerName, $action) {
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
			$this->testFile['dir'] . '/' . $this->testFile['file'] . '/' . $action . '.ctp' . chr(10) .
			'';
		$this->createTestPluginFile('View/' . $testControllerName . '/' . $action . '.ctp', $output);
	}

}

