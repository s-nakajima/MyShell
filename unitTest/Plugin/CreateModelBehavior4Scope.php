<?php
/**
 * ModelBehaviorのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * ModelBehaviorのテストファイル生成クラス
 */
Class CreateModelBehavior4Scope extends CreateModelBehavior4 {

	/**
	 * ModelBehavior::xxxxx()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param array $methodArg メソッドの引数
	 * @return void
	 */
	public function createTest($param, $methodArg = null) {
		$scope = $param[4];
		$testModelName = 'Test' . $this->testFile['class'] . $scope . 'Model';

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
		$argument = $param[1];
		$scope = $param[4];
		$scopeFile = $param[5];

		$matches = array();
		$commentParam = array();
		if (preg_match_all('/' . preg_quote(' * ', '/') . '(@param [^Model].*)' . '/', $param[2], $matches)) {
			$commentParam = $matches[1];
		}
		$dataProviderComment = '';
		foreach ($commentParam as $i => $comment) {
			$dataProviderComment .=  chr(10) . ' *  - ' . preg_replace('/^@param (.*)?\$/', '', $comment);
		}

		$className = $this->testFile['class'] . $scope . Inflector::camelize(ucfirst($function)) . 'Test';
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
					'App::uses(\'' . $this->testFile['class'] . '\', \'' . $this->plugin . '.' . $this->testFile['type'] . '\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\Behavior\\' . Inflector::camelize(ucfirst($this->testFile['file']))
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
					'$this->TestModel = ClassRegistry::init(\'Test' . $this->plugin . '.' . $testModelName . '\');',
				)
			);

		$processes1 = array();
		$processes1[] = '//TODO:テストパタンを書く。不要だったらdataProvider削除。';
		$processes1[] = '$result[0] = array();';
		$arguments = explode(', ', $argument);
		$methodArg = '';
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/^\$([_0-9a-zA-Z]+)( \= )?(.*)/', $arg, $matches)) {
				$processes1[] = '$result[0][\'' . $matches[1] . '\'] = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', $' . $matches[1];
			}
		}
		$processes1[] = '';
		$processes1[] = 'return $result;';
		$output .=
			$this->_classMethod(
				$function . '()テストのDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' .
					$dataProviderComment,
				array(
					'@return array データ',
				),
				'dataProvider()',
				$processes1
			);

		$processes2 = array();
		$processes2[] = '$behavior = new ' . $this->testFile['class'] . '();';
		$processes2[] = '';
		$processes2[] = '//テストデータ';
		$processes2[] = '';
		$processes2[] = '//テスト実施';
		$processes2[] = '$result = $this->_testReflectionMethod(';
		if ($methodArg) {
			$processes2[] = '' . chr(9) . '$behavior, \'' . $function . '\', array($this->TestModel, ' . substr($methodArg, 2) . ')';
		} else {
			$processes2[] = '' . chr(9) . '$behavior, \'' . $function . '\', array($this->TestModel)';
		}
		$processes2[] = ');';
		$processes2[] = '';
		$processes2[] = '//チェック';
		$processes2[] = '//TODO:Assertを書く';
		$processes2[] = 'var_export($result);';
		$output .=
			$this->_classMethod(
				$function . '()のテスト',
				array_merge($commentParam, array(
					'@dataProvider dataProvider',
					'@return void',
				)),
				'test' . Inflector::camelize(ucfirst($function)) . '(' .  substr($methodArg, 2) .')',
				$processes2
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile($scope . Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
		$this->deleteFile(PLUGIN_TEST_DIR . 'Model/Behavior/empty');
	}

}

