<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4AppController extends CreateController4 {

	/**
	 * XxxxAppControllerのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param, $action) {
		$testControllerName = 'Test' . $this->testFile['class'] . ucfirst($action);

		$this->_createTestController($testControllerName, $param, $action);
		$this->_createTestView($testControllerName, $param, $action);

		$this->_create($testControllerName, $param, $action);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestController($testControllerName, $param, $action) {
		$function = $param[0];
		$this->createTestPluginDir('Controller');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'' . $this->testFile['class'] . '\', \'' . $this->plugin . '.Controller\')',
				),
				$this->testFile['class'] . '::' . $function . '()テスト用Controller'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\Test' . $this->plugin . '\\Controller',
				$this->testFile['class'] . '::' . $function . '()テスト用Controller'
			) .
			'class ' . $testControllerName . 'Controller extends ' . $this->testFile['class'] . ' {' . chr(10) .
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
	protected function _createTestView($testControllerName, $param, $action) {
		$function = $param[0];
		$this->createTestPluginDir('View/' . $testControllerName . '');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(),
				$this->testFile['class'] . '::' . $function . '()テスト用Viewファイル'
			) .
			'?>' . chr(10) .
			'' . chr(10) .
			$this->testFile['dir'] . '/' . $this->testFile['file'] . '' . chr(10) .
			'';
		$this->createTestPluginFile('View/' . $testControllerName . '/' . $action . '.ctp', $output);
	}

	/**
	 * Controller/Component::xxxxx()のテストコード生成
	 *
	 * @param string $testControllerName Testコントローラ名
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _create($testControllerName, $param, $action) {
		$function = $param[0];
		$this->createNetCommonsTestCaseFile('NetCommonsControllerTestCase');
		$testSuitePlugin = $this->plugin . '.TestSuite';
		$testSuiteTest = $this->plugin . 'ControllerTestCase';
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					//'App::uses(\'UserRole\', \'UserRoles.Model\')',
				),
				$this->testFile['class'] . '::' . $function . '()のテスト'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Controller\\' . Inflector::camelize(ucfirst($this->testFile['file'])),
				$this->testFile['class'] . '::' . $function . '()のテスト'
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
					'$this->generateNc(\'Test' . $this->plugin . '.' . $testControllerName . '\');',
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
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				array(
					'//TODO:テストデータ',
					'',
					'//テスト実行',
					'$this->_testGetAction(\'/' .
						Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/' . $action . '\', null);',
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