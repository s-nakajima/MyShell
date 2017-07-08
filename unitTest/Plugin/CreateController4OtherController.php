<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4OtherController extends CreateController4 {

	/**
	 * XxxxControllerのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param atring $action アクション
	 * @return bool 成功・失敗
	 */
	public function createTest($param, $action = null) {
		$function = $param[0];
		if (! $action) {
			$action = $function;
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$this->createNetCommonsTestCaseFile('NetCommonsControllerTestCase');
		$testSuitePlugin = $this->plugin;
		$testSuiteTest = $this->plugin . 'ControllerTestCase';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Controller\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_getClassVariable($function) .
			$this->_classMethod(
				'setUp method',
				array(
					'@return void',
				),
				'setUp()',
				array(
					'parent::setUp();',
					'',
					'//ログイン',
					'TestAuthGeneral::login($this);',
				)
			) .
			$this->_classMethod(
				'tearDown method',
				array(
					'@return void',
				),
				'tearDown()',
				array(
					'//ログアウト',
					'TestAuthGeneral::logout($this);',
					'',
					'parent::tearDown();',
				)
			) .
			$this->_classMethod(
				$action . '()アクションのGetリクエストテスト',
				array(
					'@return void',
				),
				'test' . Inflector::camelize(ucfirst($function)) . 'Get()',
				array(
					'//テスト実行',
					'$this->_testGetAction(',
					chr(9) . 'array(\'action\' => \'' . $action . '\'), ',
					chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\'',
					');',
					'',
					'//チェック',
					'//TODO:assert追加',
					'var_export($this->view);',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}