<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4BlocksControllerEdit extends CreateController4Blocks {

	/**
	 * ブロック設定に関するXxxxController::add()とedit()とdelete()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'BlocksControllerEditTest';
		$testSuitePlugin = 'Blocks';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
				),
				$this->testFile['class'] . '::add(),edit(),delete()'
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Controller\\' . Inflector::camelize(ucfirst($this->testFile['file'])),
				$this->testFile['class'] . '::add(),edit(),delete()'
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_getClassVariable($function) .
			$this->_classMethod(
				'テストDataの取得',
				array(
					'@param bool $isEdit 編集かどうか',
					'@return array',
				),
				'__data($isEdit)',
				array(
					'$frameId = \'6\';',
					'//$frameKey = \'frame_3\';',
					'if ($isEdit) {',
					chr(9) . '$blockId = \'4\';',
					chr(9) . '$blockKey = \'block_2\';',
					chr(9) . '$' . $this->pluginVariable . 'Id = \'3\';',
					chr(9) . '$' . $this->pluginVariable . 'Key = \'' . $this->pluginSingularizeUnderscore . '_key_2\';',
					'} else {',
					chr(9) . '$blockId = null;',
					chr(9) . '$blockKey = null;',
					chr(9) . '$' . $this->pluginVariable . 'Id = null;',
					chr(9) . '$' . $this->pluginVariable . 'Key = null;',
					'}',
					'',
					'$data = array(',
					chr(9) . '\'Frame\' => array(',
					chr(9) . chr(9) . '\'id\' => $frameId',
					chr(9) . '),',
					chr(9) . '\'Block\' => array(',
					chr(9) . chr(9) . '\'id\' => $blockId,',
					chr(9) . chr(9) . '\'key\' => $blockKey,',
					chr(9) . chr(9) . '\'language_id\' => \'2\',',
					chr(9) . chr(9) . '\'room_id\' => \'1\',',
					chr(9) . chr(9) . '\'plugin_key\' => $this->plugin,',
					chr(9) . chr(9) . '\'public_type\' => \'1\',',
					chr(9) . chr(9) . '\'from\' => null,',
					chr(9) . chr(9) . '\'to\' => null,',
					chr(9) . '),',
					chr(9) . '//TODO:必要のデータセットをここに書く',
					chr(9) . '\'' . Inflector::classify($this->plugin) . '\' => array(',
					chr(9) . chr(9) . '\'id\' => $' . $this->pluginVariable . 'Id,',
					chr(9) . chr(9) . '\'key\' => $' . $this->pluginVariable . 'Key,',
					chr(9) . chr(9) . '\'block_id\' => $blockId,',
					chr(9) . chr(9) . '\'name\' => \'' . Inflector::classify($this->plugin) . ' name\',',
					chr(9) . '),',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			$this->_classMethod(
				'add()アクションDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - method: リクエストメソッド（get or post or put）' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - validationError: バリデーションエラー',
				array(
					'@return array',
				),
				'dataProviderAdd()',
				array(
					'$data = $this->__data(false);',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(\'method\' => \'get\');',
					'$results[1] = array(\'method\' => \'put\');',
					'$results[2] = array(\'method\' => \'post\', \'data\' => $data, \'validationError\' => false);',
					'$results[3] = array(\'method\' => \'post\', \'data\' => $data,',
					chr(9) . '\'validationError\' => array(',
					chr(9) . chr(9) . '\'field\' => \'' . Inflector::classify($this->plugin) . '.name\', //TODO:エラーにするフィールド指定',
					chr(9) . chr(9) . '\'value\' => \'\',',
					chr(9) . chr(9) . '\'message\' => sprintf(__d(\'net_commons\', \'Please input %s.\'), \'TODO:メッセージ\'),',
					chr(9) . ')',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'edit()アクションDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - method: リクエストメソッド（get or post or put）' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - validationError: バリデーションエラー',
				array(
					'@return array',
				),
				'dataProviderEdit()',
				array(
					'$data = $this->__data(true);',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(\'method\' => \'get\');',
					'$results[1] = array(\'method\' => \'post\');',
					'$results[2] = array(\'method\' => \'put\', \'data\' => $data, \'validationError\' => false);',
					'$results[3] = array(\'method\' => \'put\', \'data\' => $data,',
					chr(9) . '\'validationError\' => array(',
					chr(9) . chr(9) . '\'field\' => \'' . Inflector::classify($this->plugin) . '.name\', //TODO:エラーにするフィールド指定',
					chr(9) . chr(9) . '\'value\' => \'\',',
					chr(9) . chr(9) . '\'message\' => sprintf(__d(\'net_commons\', \'Please input %s.\'), \'TODO:メッセージ\'),',
					chr(9) . ')',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'delete()アクションDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 削除データ',
				array(
					'@return array',
				),
				'dataProviderDelete()',
				array(
					'$data = array(',
					chr(9) . '\'Block\' => array(',
					chr(9) . chr(9) . '\'id\' => \'4\',',
					chr(9) . chr(9) . '\'key\' => \'block_2\',',
					chr(9) . '),',
					chr(9) . '//TODO:必要に応じてパラメータ変更する',
					chr(9) . '\'' . Inflector::classify($this->plugin) . '\' => array(',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_key_2\',',
					chr(9) . '),',
					');',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(\'data\' => $data);',
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