<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4FrameSettingsController extends CreateController4 {

	/**
	 * ブロック設定に関するXxxxController::add()とedit()とdelete()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'FrameSettingsControllerTest';
		$testSuitePlugin = 'Frames';

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
					'$frameKey = \'frame_3\';',
					'$' . $this->pluginVariable . 'FrameId = \'3\';',
					'',
					'$data = array(',
					chr(9) . '\'Frame\' => array(',
					chr(9) . chr(9) . '\'id\' => $frameId,',
					chr(9) . chr(9) . '\'key\' => $frameKey,',
					chr(9) . '),',
					chr(9) . '\'' . Inflector::classify($this->plugin) . 'FrameSetting\' => array(',
					chr(9) . chr(9) . '\'id\' => $' . $this->pluginVariable . 'FrameId,',
					chr(9) . chr(9) . '\'frame_key\' => $frameKey,',
					chr(9) . chr(9) . '//TODO: その他のフィールド記述',
					chr(9) . '),',
					chr(9) . '//TODO:その他、必要なデータセットをここに書く',
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
					' *  - method: リクエストメソッド（get or post or put）' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - validationError: バリデーションエラー(省略可)' . chr(10) .
					' *  - exception: Exception Error(省略可)',
				array(
					'@return array',
				),
				'dataProviderEdit()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(\'method\' => \'get\');',
					'$results[1] = array(\'method\' => \'post\', \'data\' => $data, \'validationError\' => false);',
					'$results[2] = array(\'method\' => \'put\', \'data\' => $data, \'validationError\' => false);',
					'$results[3] = array(\'method\' => \'put\', \'data\' => $data,',
					chr(9) . '\'validationError\' => array(',
					chr(9) . chr(9) . '\'field\' => \'' . Inflector::classify($this->plugin) . 'FrameSetting.frame_key\', //TODO:エラーにするフィールド指定',
					chr(9) . chr(9) . '\'value\' => null, \'message\' => false',
					chr(9) . '),',
					chr(9) . '\'BadRequestException\'',
					');',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'viewファイルのチェック',
				array(
					'@return void',
				),
				'testGetEdit()',
				array(
					'//ログイン',
					'TestAuthGeneral::login($this);',
					'',
					'//テストデータ',
					'$data = $this->__data();',
					'',
					'//アクション実行',
					'$url = NetCommonsUrl::actionUrl(array(',
					chr(9) . '\'plugin\' => $this->plugin,',
					chr(9) . '\'controller\' => $this->_controller,',
					chr(9) . '\'action\' => \'edit\',',
					chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					'));',
					'$this->testAction($url, array(\'method\' => \'get\', \'return\' => \'view\'));',
					'',
					'//チェック',
					'$this->assertInput(\'form\', null, $url, $this->view);',
					'//$this->assertInput(\'input\', \'_method\', \'PUT\', $this->view);',
					'$this->assertInput(\'input\', \'data[Frame][id]\', $data[\'Frame\'][\'id\'], $this->view);',
					'$this->assertInput(\'input\', \'data[Frame][key]\', $data[\'Frame\'][\'key\'], $this->view);',
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