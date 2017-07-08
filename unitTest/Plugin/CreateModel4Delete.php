<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel4Delete extends CreateModel4 {

	/**
	 * Model::deleteXxxx()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	public function createTest($param) {
		$function = $param[0];
		$argument = $param[1];

		if ($this->isWorkflow()) {
			$testSuitePlugin = 'Workflow';
			$testSuiteTest = 'WorkflowDeleteTest';
		} else {
			$this->createNetCommonsTestCaseFile('NetCommonsDeleteTest');
			$testSuitePlugin = $this->plugin;
			$testSuiteTest = $this->plugin . 'DeleteTest';
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes1 = array();
		$processes1[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes1[] = '$association = array();';
		$processes1[] = '';
		$processes1[] = '//TODO:テストパタンを書く';
		$processes1[] = '$results = array();';
		$processes1[] = '$results[0] = array($data, $association);';
		$processes1[] = '';
		$processes1[] = 'return $results;';

		$processes2 = array();
		$processes2[] = '$data = $this->dataProviderDelete()[0][0];';
		$processes2[] = '';
		$processes2[] = '//TODO:テストパタンを書く';
		$processes2[] = 'return array(';
		$processes2[] = chr(9) . 'array($data, \'' . $this->plugin . '.' . $this->testFile['class'] . '\', \'deleteAll\'),';
		$processes2[] = ');';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . $this->plugin . '.Test/Fixture\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_getClassVariable($function) .
			$this->_classMethod(
				'Delete用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data: 削除データ' . chr(10) .
					' *  - associationModels: 削除確認の関連モデル array(model => conditions)',
				array(
					'@return array テストデータ',
				),
				'dataProviderDelete()',
				$processes1
			) .
			$this->_classMethod(
				'ExceptionError用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 登録データ' . chr(10) .
					' *  - mockModel Mockのモデル' . chr(10) .
					' *  - mockMethod Mockのメソッド',
				array(
					'@return array テストデータ',
				),
				'dataProviderDeleteOnExceptionError()',
				$processes2
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

