<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel4Validate extends CreateModel4 {

	/**
	 * Model::validates()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	public function createTest($param) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$this->createNetCommonsTestCaseFile('NetCommonsValidateTest');
		$testSuitePlugin = $this->plugin . '.TestSuite';
		$testSuiteTest = $this->plugin . 'ValidateTest';

		//メソッドの内容
		$processes = array();
		$processes[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes[] = '';
		$processes[] = '//TODO:テストパタンを書く';
		$processes[] = 'var_export($data);';
		$processes[] = 'return array(';
		$processes[] = chr(9) . 'array(\'data\' => $data, \'field\' => \'\', \'value\' => \'\',';
		$processes[] = chr(9) . chr(9) . '\'message\' => __d(\'net_commons\', \'Invalid request.\')),';
		$processes[] = ');';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . $this->plugin . '.Test/Fixture\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_getClassVariable('validates') .
			$this->_classMethod(
				'ValidationErrorのDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 登録データ' . chr(10) .
					' *  - field フィールド名' . chr(10) .
					' *  - value セットする値' . chr(10) .
					' *  - message エラーメッセージ' . chr(10) .
					' *  - overwrite 上書きするデータ(省略可)',
				array(
					'@return array テストデータ',
				),
				'dataProviderValidationError()',
				$processes
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

