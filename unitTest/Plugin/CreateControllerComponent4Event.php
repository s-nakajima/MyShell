<?php
/**
 * Controller/Componentのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controller/Componentのテストファイル生成クラス
 */
Class CreateControllerComponent4Event extends CreateControllerComponent4 {

	/**
	 * Controller/Componentのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$testControllerName = 'Test' . $this->testFile['class'];

		$this->_createTestController($testControllerName);
		$this->_createTestView($testControllerName);
		$this->_create($testControllerName, $param);
	}

	/**
	 * Controller/Component::xxxxx()のテストコード生成
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
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				array(
					'//テストコントローラ生成',
					'$this->generateNc(\'Test' . $this->plugin . '.' . $testControllerName . '\');',
					'',
					'//ログイン',
					'TestAuthGeneral::login($this);',
					'',
					'//テスト実行',
					'$this->_testGetAction(',
					chr(9) . '\'/' .
						Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/index\',',
					chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\'',
					');',
					'',
					'//チェック',
					'$pattern = \'/\' . preg_quote(\'' . $this->testFile['dir'] . '/' . $testControllerName . '/index\', \'/\') . \'/\';',
					'$this->assertRegExp($pattern, $this->view);',
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
		$this->deleteFile(PLUGIN_TEST_DIR . 'Controller/Component/empty');
	}

}

