<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel4Event extends CreateModel4 {

	/**
	 * テストファイル生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuiteTest テストSuite
	 * @param string $testSuitePlugin プラグイン
	 * @return void
	 */
	public function createTest($param, $testSuiteTest = null, $testSuitePlugin = null) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		if (! $testSuiteTest) {
			$this->createNetCommonsTestCaseFile('NetCommonsModelTestCase');
			$testSuitePlugin = $this->plugin;
			$testSuiteTest = $this->plugin . 'ModelTestCase';
		}

		//メソッドの内容
		$processes = array();
		$processes[] = '$model = $this->_modelName;';
		$processes[] = '$methodName = $this->_methodName;';
		$processes[] = '';
		if ($function === 'delete') {
			$processes[] = '//テスト実施';
			$processes[] = '$id = (new ' . $this->testFile['class'] . 'Fixture())->records[0][\'id\'];';
			$processes[] = '$result = $this->$model->$methodName($id);';
		} else {
			$processes[] = '//データ生成';
			$processes[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
			$processes[] = '';
			$processes[] = '//テスト実施';
			$processes[] = '$result = $this->$model->$methodName($data);';
		}
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:Assertを書く';
		$processes[] = 'var_export($result);';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . $this->plugin . '.Test/Fixture\')',
				),
				$this->testFile['class'] . '::' . 'before' . ucfirst($function) . '()とafter' . ucfirst($function) . '()のテスト'
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . Inflector::camelize(ucfirst($this->testFile['file'])),
				$this->testFile['class'] . '::' . 'before' . ucfirst($function) . '()とafter' . ucfirst($function) . '()のテスト'
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_getClassVariable($function) .
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

}

