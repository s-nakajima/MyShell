<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4WorkflowControllerAdd extends CreateController4Workflow {

	/**
	 * ワークフローに関するXxxxController::add()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'WorkflowControllerAddTest';
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
					'$blockKey = \'block_1\';',
					'',
					'$data = array(',
					chr(9) . '\'save_\' . WorkflowComponent::STATUS_IN_DRAFT => null,',
					chr(9) . '\'Frame\' => array(',
					chr(9) . chr(9) . '\'id\' => $frameId,',
					chr(9) . '),',
					chr(9) . '\'Block\' => array(',
					chr(9) . chr(9) . '\'id\' => $blockId,',
					chr(9) . chr(9) . '\'key\' => $blockKey,',
					chr(9) . chr(9) . '\'language_id\' => \'2\',',
					chr(9) . chr(9) . '\'room_id\' => \'1\',',
					chr(9) . chr(9) . '\'plugin_key\' => $this->plugin,',
					chr(9) . '),',
					'',
					chr(9) . '//TODO:必要のデータセットをここに書く',
					chr(9) . '\'\' => array(',
					chr(9) . chr(9) . '\'id\' => null,',
					chr(9) . chr(9) . '\'key\' => null,',
					chr(9) . chr(9) . '\'language_id\' => \'2\',',
					chr(9) . chr(9) . '\'status\' => null,',
					chr(9) . '),',
					'',
					chr(9) . '\'WorkflowComment\' => array(',
					chr(9) . chr(9) .  '\'comment\' => \'WorkflowComment save test\',',
					chr(9) . '),',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			$this->_classMethod(
				'addアクションのGETテスト(ログインなし)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderAddGet()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'// * ログインなし',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\']',
					chr(9) . '),',
					chr(9) . '\'assert\' => null, \'exception\' => \'ForbiddenException\',',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'addアクションのGETテスト(作成権限あり)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderAddGetByCreatable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . '),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'',
					'// * フレームID指定なしテスト',
					'array_push($results, Hash::merge($results[0], array(',
					chr(9) . '\'urlOptions\' => array(\'frame_id\' => null, \'block_id\' => $data[\'Block\'][\'id\']),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					')));',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'addアクションのPOSTテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - role: ロール' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderAddPost()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'// * ログインなし',
					'$results[0] = array(',
					chr(9) . '\'data\' => $data, \'role\' => null,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\']',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'ForbiddenException\'',
					');',
					'// * 作成権限あり',
					'$results[1] = array(',
					chr(9) . '\'data\' => $data, \'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\']',
					chr(9) . '),',
					');',
					'// * フレームID指定なしテスト',
					'$results[2] = array(',
					chr(9) . '\'data\' => $data, \'role\' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => null,',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\']),',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'addアクションのValidationErrorテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - validationError: バリデーションエラー',
				array(
					'@return array',
				),
				'dataProviderAddValidationError()',
				array(
					'$data = $this->__data();',
					'$result = array(',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\']',
					chr(9) . '),',
					chr(9) . '\'validationError\' => array(),',
					');',
					'',
					'//テストデータ',
					'$results = array();',
					'array_push($results, Hash::merge($result, array(',
					chr(9) . '\'validationError\' => array(',
					chr(9) . chr(9) . '\'field\' => \'\', //TODO:エラーにするフィールド指定',
					chr(9) . chr(9) . '\'value\' => \'\',',
					chr(9) . chr(9) . '\'message\' => \'\' //TODO:エラーメッセージ指定',
					chr(9) . ')',
					')));',
					'',
					'//TODO:必要なテストデータ追加',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'Viewのアサーション',
				array(
					'@param array $data テストデータ',
					'@return void',
				),
				'__assertAddGet($data)',
				array(
					'$this->assertInput(',
					chr(9) . '\'input\', \'data[Frame][id]\', $data[\'Frame\'][\'id\'], $this->view',
					');',
					'$this->assertInput(',
					chr(9) . '\'input\', \'data[Block][id]\', $data[\'Block\'][\'id\'], $this->view',
					');',
					'',
					'//TODO:上記以外に必要なassert追加',
				),
				'private'
			) .
			$this->_classMethod(
				'view(ctp)ファイルのテスト(公開権限なし)',
				array(
					'@return void',
				),
				'testViewFileByCreatable()',
				array(
					'TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_GENERAL_USER);',
					'',
					'//テスト実行',
					'$data = $this->__data();',
					'$this->_testGetAction(',
					chr(9) . 'array(',
					chr(9) . chr(9) . '\'action\' => \'add\',',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . '),',
					chr(9) . 'array(\'method\' => \'assertNotEmpty\')',
					');',
					'',
					'//チェック',
					'$this->__assertAddGet($data);',
					'$this->assertInput(\'button\', \'save_\' . WorkflowComponent::STATUS_IN_DRAFT, null, $this->view);',
					'$this->assertInput(\'button\', \'save_\' . WorkflowComponent::STATUS_APPROVED, null, $this->view);',
					'',
					'//TODO:上記以外に必要なassert追加',
					'var_export($this->view);',
					'',
					'TestAuthGeneral::logout($this);',
				)
			) .
			$this->_classMethod(
				'view(ctp)ファイルのテスト(公開権限あり)',
				array(
					'@return void',
				),
				'testViewFileByPublishable()',
				array(
					'//ログイン',
					'TestAuthGeneral::login($this);',
					'',
					'//テスト実行',
					'$data = $this->__data();',
					'$this->_testGetAction(',
					chr(9) . 'array(',
					chr(9) . chr(9) . '\'action\' => \'add\',',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . '),',
					chr(9) . 'array(\'method\' => \'assertNotEmpty\')',
					');',
					'',
					'//チェック',
					'$this->__assertAddGet($data);',
					'$this->assertInput(\'button\', \'save_\' . WorkflowComponent::STATUS_IN_DRAFT, null, $this->view);',
					'$this->assertInput(\'button\', \'save_\' . WorkflowComponent::STATUS_PUBLISHED, null, $this->view);',
					'',
					'//TODO:上記以外に必要なassert追加',
					'var_export($this->view);',
					'',
					'//ログアウト',
					'TestAuthGeneral::logout($this);',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}