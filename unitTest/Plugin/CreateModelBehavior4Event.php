<?php
/**
 * ModelBehaviorのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * ModelBehaviorのテストファイル生成クラス
 */
Class CreateModelBehavior4Event extends CreateModelBehavior4 {

	/**
	 * ModelBehavior::xxxxx()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param array $methodArg メソッドの引数
	 * @return void
	 */
	public function createTest($param, $methodArg = null) {
		$testModelName = 'Test' . $this->testFile['class'] . Inflector::camelize(ucfirst($param[0])) . 'Model';

		//テストModelの作成
		$this->_createTestModel($testModelName, $param[0], true);

		//Fixtureの作成
		$this->_createFixture($testModelName, $param[0]);

		//テストケース作成
		$this->_createTest($testModelName, $param, $methodArg);
	}

	/**
	 * ModelBehavior::xxxxx()のテストコード生成
	 *
	 * @param string $testModelName Testモデル名
	 * @param array $param メソッドデータ配列
	 * @param array $methodArg メソッドの引数
	 * @return void
	 */
	protected function _createTest($testModelName, $param, $methodArg = null) {
		$function = $param[0];

		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$this->createNetCommonsTestCaseFile('NetCommonsModelTestCase');
		$testSuitePlugin = $this->plugin . '.TestSuite';
		$testSuiteTest = $this->plugin . 'ModelTestCase';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
					'App::uses(\'' . $testModelName . 'Fixture\', \'' . $this->plugin . '.Test/Fixture\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\Behavior\\' . Inflector::camelize(ucfirst($this->testFile['class']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
					'Fixtures',
					'array',
					'public',
					'fixtures',
					array(
						'array(',
						chr(9) . '\'plugin.' . Inflector::underscore($this->plugin) . '.' . Inflector::underscore($testModelName) . '\',',
						');',
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
					'$this->TestModel = ClassRegistry::init(\'Test' . $this->plugin . '.' . $testModelName . '\');',
				)
			);

		$processes = array();
		$processes[] = '//テストデータ';
		foreach ($methodArg as $variable => $value) {
			if ($variable === '$data' && $value === 'fixture') {
				$processes[] = $variable . ' = array(';
				$processes[] = chr(9) . '\'' . $testModelName . '\' => (new ' . $testModelName . 'Fixture())->records[0],';
				$processes[] = ');';
			} else {
				$processes[] = $variable . ' = ' . $value . ';';
			}
		}
		$processes[] = '';
		$processes[] = '//テスト実施';
		if ($function === 'validates') {
			$processes[] = '$this->TestModel->set(' . implode(', ', array_keys($methodArg)) . ');';
			$processes[] = '$result = $this->TestModel->' . $function . '();';
		} else {
			$processes[] = '$result = $this->TestModel->' . $function . '(' . implode(', ', array_keys($methodArg)) . ');';
		}
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:Assertを書く';
		if ($function === 'validates') {
			$processes[] = 'var_export($this->TestModel->validationErrors);';
		}
		$processes[] = 'var_export($result);';
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
		$this->deleteFile(PLUGIN_TEST_DIR . 'Model/Behavior/empty');
	}

}

