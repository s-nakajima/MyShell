<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4WorkflowControllerDelete extends CreateController4Workflow {

	/**
	 * ワークフローに関するXxxxController::delete()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'WorkflowControllerDeleteTest';
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
					'@param string $contentKey キー',
					'@return array',
				),
				'__data($contentKey = null)',
				array(
					'$frameId = \'6\';',
					'$blockId = \'2\';',
					'$blockKey = \'block_1\';',
					'if ($contentKey === \'' . 'content_key_2\') {',
					chr(9) . '$contentId = \'3\';',
					'} elseif ($contentKey === \'' . 'content_key_4\') {',
					chr(9) . '$contentId = \'5\';',
					'} else {',
					chr(9) . '$contentId = \'2\';',
					'}',
					'',
					'$data = array(',
					chr(9) . '\'delete\' => null,',
					chr(9) . '\'Frame\' => array(',
					chr(9) . chr(9) . '\'id\' => $frameId,',
					chr(9) . '),',
					chr(9) . '\'Block\' => array(',
					chr(9) . chr(9) . '\'id\' => $blockId,',
					chr(9) . chr(9) . '\'key\' => $blockKey,',
					chr(9) . '),',
					'',
					chr(9) . '//TODO:必要のデータセットをここに書く',
					chr(9) . '\'\' => array(',
					chr(9) . chr(9) . '\'id\' => $contentId,',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . '),',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			$this->_classMethod(
				'deleteアクションのGETテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - role: ロール' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderDeleteGet()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'// * ログインなし',
					'$results[0] = array(\'role\' => null,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . 'content_key_1\',',
					chr(9) . '),',
					chr(9) . '\'assert\' => null, \'exception\' => \'ForbiddenException\'',
					');',
					'// * 作成権限のみ(自分自身)',
					'array_push($results, Hash::merge($results[0], array(',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . 'content_key_2\',',
					chr(9) . '),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					')));',
					'// * 編集権限、公開権限なし',
					'array_push($results, Hash::merge($results[0], array(',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_EDITOR,',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					')));',
					'// * 公開権限あり',
					'array_push($results, Hash::merge($results[0], array(',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					')));',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'deleteアクションのPOSTテスト用DataProvider' . chr(10) .
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
				'dataProviderDeletePost()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'// * ログインなし',
					'$contentKey = \'' . 'content_key_1\';',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data($contentKey),',
					chr(9) . '\'role\' => null,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'ForbiddenException\'',
					'));',
					'// * 作成権限のみ',
					'// ** 他人の記事',
					'$contentKey = \'' . 'content_key_1\';',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data($contentKey),',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'BadRequestException\'',
					'));',
					'// ** 自分の記事＆一度も公開されていない',
					'$contentKey = \'' . 'content_key_2\';',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data($contentKey),',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey',
					chr(9) . '),',
					'));',
					'// ** 自分の記事＆一度公開している',
					'$contentKey = \'' . 'content_key_4\';',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data($contentKey),',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'BadRequestException\'',
					'));',
					'// * 編集権限あり',
					'// ** 公開していない',
					'$contentKey = \'' . 'content_key_2\';',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data($contentKey),',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_EDITOR,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey',
					chr(9) . '),',
					'));',
					'// ** 公開している',
					'$contentKey = \'' . 'content_key_4\';',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data($contentKey),',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_EDITOR,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'BadRequestException\'',
					'));',
					'// * 公開権限あり',
					'// ** フレームID指定なしテスト',
					'$contentKey = \'' . 'content_key_1\';',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data($contentKey),',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => null,',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey',
					chr(9) . '),',
					'));',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'deleteアクションのExceptionErrorテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - mockModel: Mockのモデル' . chr(10) .
					' *  - mockMethod: Mockのメソッド' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderDeleteExceptionError()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'mockModel\' => \'' . $this->plugin . '.Xxxxxx\', //TODO:Mockモデルをセットする',
					chr(9) . '\'mockMethod\' => \'deleteXxxxxx\', //TODO:Mockメソッドをセットする',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . 'content_key_1\',',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'BadRequestException\',',
					chr(9) . '\'return\' => \'view\'',
					');',
					'',
					'//TODO:必要なデータをここに書く',
					'',
					'return $results;',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}