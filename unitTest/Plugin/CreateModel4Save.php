<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel4Save extends CreateModel4 {

	/**
	 * Model::saveXxxx()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	public function createTest($param) {
		$function = $param[0];
		$argument = $param[1];

		if ($this->isWorkflow()) {
			$testSuitePlugin = 'Workflow';
			$testSuiteTest = 'WorkflowSaveTest';
		} else {
			$this->createNetCommonsTestCaseFile('NetCommonsSaveTest');
			$testSuitePlugin = $this->plugin;
			$testSuiteTest = $this->plugin . 'SaveTest';
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

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
			$this->_getClassVariable($function);

		$processes1 = array();
		if ($this->isWorkflow()) {
			$processes1[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[1];';
			$processes1[] = '$data[\'' . $this->testFile['class'] . '\'][\'status\'] = \'1\';';
		} else {
			$processes1[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		}
		$processes1[] = '';
		$processes1[] = '//TODO:テストパタンを書く';
		$processes1[] = '$results = array();';
		$processes1[] = '// * 編集の登録処理';
		$processes1[] = '$results[0] = array($data);';
		$processes1[] = '// * 新規の登録処理';
		$processes1[] = '$results[1] = array($data);';
		$processes1[] = '$results[1] = Hash::insert($results[1], \'0.' . $this->testFile['class'] . '.id\', null);';
		$processes1[] = '$results[1] = Hash::insert($results[1], \'0.' . $this->testFile['class'] . '.key\', null); //TODO:不要なら削除する';
		$processes1[] = '$results[1] = Hash::remove($results[1], \'0.' . $this->testFile['class'] . '.created_user\');';
		$processes1[] = '';
		$processes1[] = 'return $results;';
		$output .=
			$this->_classMethod(
				'Save用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 登録データ',
				array(
					'@return array テストデータ',
				),
				'dataProviderSave()',
				$processes1
			);

		$processes2 = array();
		$processes2[] = '$data = $this->dataProviderSave()[0][0];';
		$processes2[] = '';
		$processes2[] = '//TODO:テストパタンを書く';
		$processes2[] = 'return array(';
		$processes2[] = chr(9) . 'array($data, \'' . $this->plugin . '.' . $this->testFile['class'] . '\', \'save\'),';
		$processes2[] = ');';
		$output .=
			$this->_classMethod(
				'SaveのExceptionError用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 登録データ' . chr(10) .
					' *  - mockModel Mockのモデル' . chr(10) .
					' *  - mockMethod Mockのメソッド',
				array(
					'@return array テストデータ',
				),
				'dataProviderSaveOnExceptionError()',
				$processes2
			);

		$processes3 = array();
		$processes3[] = '$data = $this->dataProviderSave()[0][0];';
		$processes3[] = '';
		$processes3[] = '//TODO:テストパタンを書く';
		$processes3[] = 'return array(';
		$processes3[] = chr(9) . 'array($data, \'' . $this->plugin . '.' . $this->testFile['class'] . '\'),';
		$processes3[] = ');';
		$output .=
			$this->_classMethod(
				'SaveのValidationError用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 登録データ' . chr(10) .
					' *  - mockModel Mockのモデル' . chr(10) .
					' *  - mockMethod Mockのメソッド(省略可：デフォルト validates)',
				array(
					'@return array テストデータ',
				),
				'dataProviderSaveOnValidationError()',
				$processes3
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

