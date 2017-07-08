<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4MailSettingsController extends CreateController4 {

	/**
	 * ブロック設定に関するXxxxController::add()とedit()とdelete()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		//$testSuitePlugin = 'Mails';
		$testSuitePlugin = 'NetCommons';
		//$testSuiteTest = 'MailSettingsControllerTest';
		$testSuiteTest = 'NetCommonsControllerTestCase';

		$controller = Inflector::underscore(substr($this->testFile['class'], 0, -1 * strlen('Controller')));

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
				'setUp method',
				array(
					'@return void',
				),
				'setUp()',
				array(
					'parent::setUp();',
					'',
					'//ログイン',
					'TestAuthGeneral::login($this);',
				)
			) .
			$this->_classMethod(
				'tearDown method',
				array(
					'@return void',
				),
				'tearDown()',
				array(
					'//ログアウト',
					'TestAuthGeneral::logout($this);',
					'',
					'parent::tearDown();',
				)
			);

		$process = array(
			'//テストデータ',
			'$frameId = \'6\';',
			'$blockId = \'2\';',
			'',
			'//テスト実行',
			'$this->_testGetAction(array(\'action\' => \'edit\', \'block_id\' => $blockId, \'frame_id\' => $frameId),',
			chr(9) . chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\');',
			'',
			'//チェック',
			'$this->assertInput(\'form\', null, \'' . Inflector::underscore($this->plugin) . '/' . $controller . '/edit/\' . $blockId, $this->view);',
		);

		$output .=
			$this->_classMethod(
				$function . '()アクションのGetリクエストテスト',
				array('@return void'), 'test' . ucfirst($function) . 'Get()', $process
			);

		$output .=
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}