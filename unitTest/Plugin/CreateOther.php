<?php
/**
 * その他のテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * その他のテストファイル生成クラス
 */
Class CreateOther extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## その他のテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * その他のテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions(true);

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if ($this->testFile['class'] === 'NetCommonsCakeTestCase') {
				$testSuitePlugin = 'TestSuite';
				$testSuiteTest = 'CakeTestCase';
			//} elseif ($this->testFile['class'] === 'NetCommonsControllerTestCase') {
			//	$testSuitePlugin = 'TestSuite';
			//	$testSuiteTest = 'ControllerTestCase';
			//} elseif (preg_match('/^[A-Za-z0-9]+Controller[A-Za-z0-9]+$/', $this->testFile['class'])) {
			//	$testSuitePlugin = 'NetCommons.TestSuite';
			//	$testSuiteTest = 'NetCommonsControllerTestCase';
			} else {
				$this->createNetCommonsTestCaseFile('NetCommonsCakeTestCase');
				$testSuitePlugin = $this->plugin . '.TestSuite';
				$testSuiteTest = $this->plugin . 'CakeTestCase';
			}

			if ($param[5]) {
				$this->_createScope($param, $testSuitePlugin, $testSuiteTest);
			} else {
				$this->_create($param, $testSuitePlugin, $testSuiteTest);
			}
		}
	}

	/**
	 * その他のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	protected function _create($param, $testSuitePlugin, $testSuiteTest) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->plugin . Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) .
						$this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

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
		$processes[] = '//$result = $this->' . $function . '(' . substr($methodArg, 2) . ');';
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:assertを書く';
		$processes[] = '//var_dump($result);';

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
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' .
						str_replace('/', '\\', $this->testFile['dir']) . '\\' .
						Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariablePlugin() .
			$this->_classMethod(
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
	 * その他のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	protected function _createScope($param, $testSuitePlugin, $testSuiteTest) {
		$function = $param[0];
		$argument = $param[1];
		$scope = $param[4];
		$scopeFile = $param[5];

		$className = $this->plugin . Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) .
						$this->testFile['class'] . $scope . Inflector::camelize(ucfirst($function)) . 'Test';

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
		$processes[] = '//$instance = new ' . $this->testFile['class'] . '();';
		$processes[] = '//$result = $this->_testReflectionMethod(';
		$processes[] = '//' . chr(9) . '$instance, \'' . $function . '\', array(' . substr($methodArg, 2) . ')';
		$processes[] = '//);';
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:assertを書く';
		$processes[] = '//var_dump($result);';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
					'App::uses(\'' . $this->testFile['class'] . '\', \'' . $this->plugin . '.' . $this->testFile['type'] . '\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' .
						str_replace('/', '\\', $this->testFile['dir']) . '\\' .
						Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariablePlugin() .
			$this->_classMethod(
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst(trim(strtr($function, '_', ' '))) . '()',
				$processes
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile($scope . Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

