<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4WorkflowControllerView extends CreateController4Workflow {

	/**
	 * ワークフローに関するXxxxController::view()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'WorkflowControllerViewTest';
		$testSuitePlugin = 'Workflow';

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
				'テストDataの取得',
				array(
					'@return array',
				),
				'__data()',
				array(
					'$frameId = \'6\';',
					'$blockId = \'2\';',
					'$contentKey = \'' . 'content_key_1\';',
					'',
					'$data = array(',
					chr(9) . '\'action\' => \'view\',',
					chr(9) . '\'frame_id\' => $frameId,',
					chr(9) . '\'block_id\' => $blockId,',
					chr(9) . '\'key\' => $contentKey,',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			$this->_classMethod(
				'viewアクションのテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderView()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_1\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[1] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_2\'),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					');',
					'$results[2] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_3\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[3] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_4\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[4] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_5\'),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					');',
					'$results[5] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_999\'),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'viewアクションのテスト',
				array(
					'@param array $urlOptions URLオプション',
					'@param array $assert テストの期待値',
					'@param string|null $exception Exception',
					'@param string $return testActionの実行後の結果',
					'@dataProvider dataProviderView',
					'@return void',
				),
				'testView($urlOptions, $assert, $exception = null, $return = \'view\')',
				array(
					'//テスト実行',
					'parent::testView($urlOptions, $assert, $exception, $return);',
					'if ($exception) {',
					chr(9) . 'return;',
					'}',
					'',
					'//チェック',
					'$this->__assertView($urlOptions[\'key\'], false);',
				)
			) .
			$this->_classMethod(
				'viewアクションのテスト(作成権限のみ)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderViewByCreatable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_1\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[1] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_2\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[2] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_3\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[3] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_4\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[4] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_5\'),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					');',
					'$results[5] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_999\'),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'viewアクションのテスト(作成権限のみ)',
				array(
					'@param array $urlOptions URLオプション',
					'@param array $assert テストの期待値',
					'@param string|null $exception Exception',
					'@param string $return testActionの実行後の結果',
					'@dataProvider dataProviderViewByCreatable',
					'@return void',
				),
				'testViewByCreatable($urlOptions, $assert, $exception = null, $return = \'view\')',
				array(
					'//テスト実行',
					'parent::testViewByCreatable($urlOptions, $assert, $exception, $return);',
					'if ($exception) {',
					chr(9) . 'return;',
					'}',
					'',
					'//チェック',
					'if ($urlOptions[\'key\'] === \'content_key_1\') {',
					chr(9) . '$this->__assertView($urlOptions[\'key\'], false);',
					'',
					'} elseif ($urlOptions[\'key\'] === \'content_key_3\') {',
					chr(9) . '$this->__assertView($urlOptions[\'key\'], true);',
					'',
					'} elseif ($urlOptions[\'key\'] === \'content_key_4\') {',
					chr(9) . '$this->__assertView($urlOptions[\'key\'], false);',
					'',
					'} else {',
					chr(9) . '$this->__assertView($urlOptions[\'key\'], false);',
					'}',
				)
			) .
			$this->_classMethod(
				'viewアクションのテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderViewByEditable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_1\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[1] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_2\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[2] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_3\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[3] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_4\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[4] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_5\'),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'$results[5] = array(',
					chr(9) . '\'urlOptions\' => Hash::insert($data, \'key\', \'content_key_999\'),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'viewアクションのテスト(編集権限あり)',
				array(
					'@param array $urlOptions URLオプション',
					'@param array $assert テストの期待値',
					'@param string|null $exception Exception',
					'@param string $return testActionの実行後の結果',
					'@dataProvider dataProviderViewByEditable',
					'@return void',
				),
				'testViewByEditable($urlOptions, $assert, $exception = null, $return = \'view\')',
				array(
					'//テスト実行',
					'parent::testViewByEditable($urlOptions, $assert, $exception, $return);',
					'if ($exception) {',
					chr(9) . 'return;',
					'}',
					'',
					'//チェック',
					'$this->__assertView($urlOptions[\'key\'], true);',
				)
			) .
			$this->_classMethod(
				'view()のassert',
				array(
					'@param string $contentKey コンテンツキー',
					'@param bool $isLatest 最終コンテンツかどうか',
					'@return void',
					'@SuppressWarnings(PHPMD.BooleanArgumentFlag)',
				),
				'__assertView($contentKey, $isLatest = false)',
				array(
					'//TODO:view(ctp)ファイルに対するassert追加',
					'//var_export($this->view);',
					'',
					'if ($contentKey === \'content_key_1\') {',
					chr(9) . 'if ($isLatest) {',
					chr(9) . chr(9) . '//TODO: コンテンツのデータ(id=2, key=content_key_1)に対する期待値',
					chr(9) . chr(9) . '$this->assertTextContains(\'Title 2\', $this->view);',
					chr(9) . '} else {',
					chr(9) . chr(9) . '//TODO: コンテンツのデータ(id=1, key=content_key_1)に対する期待値',
					chr(9) . chr(9) . '$this->assertTextContains(\'Title 1\', $this->view);',
					chr(9) . '}',
					'',
					'} elseif ($contentKey === \'content_key_2\') {',
					chr(9) . '//TODO: コンテンツのデータ(id=3, key=content_key_2)に対する期待値',
					chr(9) . '$this->assertTextContains(\'Title 3\', $this->view);',
					'',
					'} elseif ($contentKey === \'content_key_3\') {',
					chr(9) . 'if ($isLatest) {',
					chr(9) . chr(9) . '//TODO: コンテンツのデータ(id=5, key=content_key_3)に対する期待値',
					chr(9) . chr(9) . '$this->assertTextContains(\'\', $this->view);',
					chr(9) . '} else {',
					chr(9) . chr(9) . '//TODO: コンテンツのデータ(id=4, key=content_key_3)に対する期待値',
					chr(9) . chr(9) . '$this->assertTextContains(\'\', $this->view);',
					chr(9) . '}',
					'',
					'} elseif ($contentKey === \'content_key_4\') {',
					chr(9) . 'if ($isLatest) {',
					chr(9) . chr(9) . '//TODO: コンテンツのデータ(id=7, key=content_key_4)に対する期待値',
					chr(9) . chr(9) . '$this->assertTextContains(\'\', $this->view);',
					chr(9) . '} else {',
					chr(9) . chr(9) . '//TODO: コンテンツのデータ(id=6, key=content_key_4)に対する期待値',
					chr(9) . chr(9) . '$this->assertTextContains(\'\', $this->view);',
					chr(9) . '}',
					'',
					'} elseif ($contentKey === \'content_key_5\') {',
					chr(9) . '//TODO: コンテンツのデータ(id=8, key=content_key_5)に対する期待値',
					chr(9) . '$this->assertTextContains(\'\', $this->view);',
					'}',
			),
				'private'
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}