<?php
/**
 * View/Helperのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * View/Helperのテストファイル生成クラス
 */
Class CreateViewHelper extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## View/Helperのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * View/Helperのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions(true);

		foreach ($functions as $param) {
			if ($param[0] === '__construct') {
				continue;
			}

			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if (substr($param[0], 0, strlen('afterRenderFile')) === 'afterRenderFile' ||
					substr($param[0], 0, strlen('beforeRender')) === 'beforeRender' ||
					substr($param[0], 0, strlen('afterRender')) === 'afterRender' ||
					substr($param[0], 0, strlen('beforeLayout')) === 'beforeLayout') {

				(new CreateViewHelper4Event($this->testFile, false))->createTest($param);
			} elseif ($param[5]) {
				(new CreateViewHelper4Scope($this->testFile))->createTest($param);
			} else {
				$this->_create($param);
			}
		}
	}

	/**
	 * View/Helper::xxxxx()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _create($param) {
		$function = $param[0];
		$argument = $param[1];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$this->createNetCommonsTestCaseFile('NetCommonsHelperTestCase');
		$testSuitePlugin = $this->plugin . '.TestSuite';
		$testSuiteTest = $this->plugin . 'HelperTestCase';

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
		$processes[] = '$result = $this->' . substr($this->testFile['class'], 0, -1 * strlen('Helper')) . '->' . $function . '(' . substr($methodArg, 2) . ');';
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:assertを書く';
		$processes[] = 'var_export($result);';

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
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\View\\Helper\\' . Inflector::camelize(ucfirst($this->testFile['file']))
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
					'//テストデータ生成',
					'//TODO:必要に応じてセットする',
					'$viewVars = array();',
					'$requestData = array();',
					'$params = array();',
					'',
					'//Helperロード',
					'$this->loadHelper(\'' . $this->plugin . '.' . substr($this->testFile['class'], 0, -1 * strlen('Helper')) . '\', ' .
										'$viewVars, $requestData, $params);',
				)
			) .
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
		$this->deleteFile(PLUGIN_TEST_DIR . 'View/Helper/empty');
	}

}

