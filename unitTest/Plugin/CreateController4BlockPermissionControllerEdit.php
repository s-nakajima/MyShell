<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4BlockPermissionControllerEdit extends CreateController4 {

	/**
	 * 権限設定に関するXxxxController::edit()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$argument = $param[1];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'BlockRolePermissionsControllerEditTest';
		$testSuitePlugin = 'Blocks';

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
				'権限設定で使用するFieldsの取得',
				array(
					'@return array',
				),
				'__approvalFields()',
				array(
					'$data = array(',
					chr(9) . '\'' . Inflector::classify($this->plugin) . 'Setting\' => array(',
					chr(9) . chr(9) . '//TODO:権限設定で使用するFieldsをここに書く',
					chr(9) . chr(9) . '\'use_workflow\',',
					chr(9) . chr(9) . '\'use_comment_approval\',',
					chr(9) . chr(9) . '\'approval_type\',',
					chr(9) . ')',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			$this->_classMethod(
				'テストDataの取得',
				array(
					'@return array',
				),
				'__data()',
				array(
					'$data = array(',
					chr(9) . '\'' . Inflector::classify($this->plugin) . 'Setting\' => array(',
					chr(9) . chr(9) . '\'id\' => 2,',
					chr(9) . chr(9) . '\'' . $this->pluginSingularizeUnderscore . '_key\' => \'' . $this->pluginSingularizeUnderscore . '_key_2\',',
					chr(9) . chr(9) . '//TODO:必要なデータセットをここに書く',
					chr(9) . chr(9) . '\'use_workflow\' => true,',
					chr(9) . chr(9) . '\'use_comment_approval\' => true,',
					chr(9) . chr(9) . '\'approval_type\' => true,',
					chr(9) . ')',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			$this->_classMethod(
				'edit()アクションDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - approvalFields コンテンツ承認の利用有無のフィールド' . chr(10) .
					' *  - exception Exception' . chr(10) .
					' *  - return testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderEditGet()',
				array(
					'return array(',
					chr(9) . 'array(\'approvalFields\' => $this->__approvalFields())',
					');',
				)
			) .
			$this->_classMethod(
				'edit()アクションDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data POSTデータ' . chr(10) .
					' *  - exception Exception' . chr(10) .
					' *  - return testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderEditPost()',
				array(
					'return array(',
					chr(9) . 'array(\'data\' => $this->__data())',
					');',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}