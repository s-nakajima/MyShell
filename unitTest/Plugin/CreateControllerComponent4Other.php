<?php
/**
 * Controller/Componentのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controller/Componentのテストファイル生成クラス
 */
Class CreateControllerComponent4Other extends CreateControllerComponent4 {

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
		$argument = $param[1];
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
			);

		$processes = array();
		$processes[] = '//テストコントローラ生成';
		$processes[] = '$this->generateNc(\'Test' . $this->plugin . '.' . $testControllerName . '\');';
		$processes[] = '';
		$processes[] = '//ログイン';
		$processes[] = 'TestAuthGeneral::login($this);';
		$processes[] = '';
		$processes[] = '//テストアクション実行';
		$processes[] = '$this->_testGetAction(';
		$processes[] = chr(9) . '\'/' .Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/index\',';
		$processes[] = chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\'';
		$processes[] = ');';
		$processes[] = '$pattern = \'/\' . preg_quote(\'' . $this->testFile['dir'] . '/' . $testControllerName . '\', \'/\') . \'/\';';
		$processes[] = '$this->assertRegExp($pattern, $this->view);';

		$arguments = explode(', ', $argument);
		$methodArg = '';
		$hasController = false;
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/Controller \$controller/', $arg, $matches)) {
				$hasController = true;
				continue;
			}
			if (preg_match('/^([\$_0-9a-zA-Z]+)( \= )(.*)/', $arg, $matches)) {
				if ($methodArg === '') {
					$processes[] = '';
					$processes[] = '//テストデータ生成';
				}
				$processes[] = $matches[1] . ' = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', ' . $matches[1];
			}
		}
		if ($hasController) {
			$methodArg .= ', $this->controller';
		}

		$component = substr($this->testFile['class'], 0, -1 * strlen('Component'));
		$processes[] = '';
		$processes[] = '//テスト実行';
		if (preg_match('/@return void/', $param[2])) {
			$processes[] = '$this->controller->' . $component . '->' . $function . '(' . substr($methodArg, 2) . ');';
			$processes[] = '';
			$processes[] = '//TODO:必要に応じてassert追加する';
			$processes[] = 'var_export($this->controller->viewVars);';
		} else {
			$processes[] = '$result = $this->controller->' . $component . '->' . $function . '(' . substr($methodArg, 2) . ');';
			$processes[] = '';
			$processes[] = '//TODO:必要に応じてassert追加する';
			$processes[] = 'var_export($result);';
		}

		$output .=
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
		$this->deleteFile(PLUGIN_TEST_DIR . 'Controller/Component/empty');
	}

}

