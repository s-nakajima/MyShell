<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4EditController extends CreateController4 {

	/**
	 * XxxxControllerのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param atring $action アクション
	 * @return bool 成功・失敗
	 */
	public function createTest($param, $action = null) {
		$function = $param[0];
		if (! $action) {
			$action = $function;
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		$this->createNetCommonsTestCaseFile('NetCommonsControllerTestCase');
		$testSuitePlugin = $this->plugin . '.TestSuite';
		$testSuiteTest = $this->plugin . 'ControllerTestCase';

		$controller = Inflector::underscore(substr($this->testFile['class'], 0, -1 * strlen('Controller')));

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
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

		//Getリクエストテスト
		if ($this->isManagerPlugin()) {
			$process = array(
				'//テスト実行',
				'$this->_testGetAction(',
				chr(9) . 'array(\'action\' => \'edit\'),',
				chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\'',
				');',
				'',
				'//チェック',
				'$this->__assertEditGet();',
			);
		} else {
			$process = array(
				'//テストデータ',
				'$frameId = \'6\';',
				'$blockId = \'2\';',
				'$blockKey = \'block_1\';',
				'',
				'//テスト実行',
				'$this->_testGetAction(',
				chr(9) . 'array(\'action\' => \'edit\', \'block_id\' => $blockId, \'frame_id\' => $frameId),',
				chr(9) . 'array(\'method\' => \'assertNotEmpty\'), null, \'view\'',
				');',
				'',
				'//チェック',
				'$this->__assertEditGet($frameId, $blockId, $blockKey);',
			);
		}
		$output .=
			$this->_classMethod(
				$action . '()アクションのGetリクエストテスト',
				array('@return void'), 'test' . ucfirst($function) . 'Get()', $process
			);

		//Inputのチェック
		if ($this->isManagerPlugin()) {
			$output .=
				$this->_classMethod(
					$action . '()のチェック',
					array(
						'@return void'
					),
					'__assertEditGet()',
					array(
						'$result = $this->_parseView($this->view);',
						'',
						'$this->assertInput(\'form\', null, \'/' . Inflector::underscore($this->plugin) . '/' . $controller . '/edit\', $result);',
						'$this->assertInput(\'input\', \'_method\', \'PUT\', $result);',
						'',
						'//TODO:必要に応じてassert書く',
					),
					'private'
				);
		} else {
			$output .=
				$this->_classMethod(
					$action . '()のチェック',
					array(
						'@param int $frameId フレームID',
						'@param int $blockId ブロックID',
						'@param string $blockKey ブロックKey',
						'@return void'
					),
					'__assertEditGet($frameId, $blockId, $blockKey)',
					array(
						'$result = $this->_parseView($this->view);',
						'',
						'//TODO:必要に応じてassert書く',
						'var_export($result);',
						'var_export($this->controller->request->data);',
						'',
						'$this->assertInput(\'form\', null, \'' . Inflector::underscore($this->plugin) . '/' . $controller . '/edit/\' . $blockId, $result);',
						'$this->assertInput(\'input\', \'_method\', \'PUT\', $result);',
						'$this->assertInput(\'input\', \'data[Frame][id]\', $frameId, $result);',
						'$this->assertInput(\'input\', \'data[Block][id]\', $blockId, $result);',
						'$this->assertInput(\'input\', \'data[Block][key]\', $blockKey, $result);',
						'',
						'$this->assertEquals($frameId, Hash::get($this->controller->request->data, \'Frame.id\'));',
						'$this->assertEquals($blockId, Hash::get($this->controller->request->data, \'Block.id\'));',
						'$this->assertEquals($blockKey, Hash::get($this->controller->request->data, \'Block.key\'));',
						'',
						'//TODO:必要に応じてassert書く',
					),
					'private'
				);
		}

		//POSTリクエストデータ生成
		if ($this->isManagerPlugin()) {
			$process = array(
				'$data = array(',
				chr(9) . '//TODO:必要に応じて、assertを追加する',
				');',
				'',
				'return $data;'
			);
		} else {
			$process = array(
				'$data = array(',
				chr(9) . '\'Frame\' => array(',
				chr(9) . chr(9) . '\'id\' => \'6\'',
				chr(9) . '),',
				chr(9) . '\'Block\' => array(',
				chr(9) . chr(9) . '\'id\' => \'2\', \'key\' => \'block_1\'',
				chr(9) . '),',
				chr(9) . '//TODO:必要に応じて、assertを追加する',
				');',
				'',
				'return $data;'
			);
		}
		$output .=
			$this->_classMethod(
				'POSTリクエストデータ生成',
				array(
					'@return array リクエストデータ'
				),
				'__data()', $process, 'private'
			);

		//POSTリクエストテスト
		if ($this->isManagerPlugin()) {
			$process = array(
				'//テスト実行',
				'$this->_testPostAction(',
				chr(9) . '\'put\', $this->__data(),',
				chr(9) . 'array(\'action\' => \'edit\'),',
				chr(9) . 'null, \'view\'',
				');',
				'',
				'//チェック',
				'$header = $this->controller->response->header();',
				'$pattern = \'' . $this->pluginSingularizeUnderscore . '/' . $this->pluginSingularizeUnderscore . '/index/\' . $blockId;',
				'$this->assertTextContains($pattern, $header[\'Location\']);',
			);
		} else {
			$process = array(
				'//テストデータ',
				'$frameId = \'6\';',
				'$blockId = \'2\';',
				'',
				'//テスト実行',
				'$this->_testPostAction(',
				chr(9) . '\'put\', $this->__data(),',
				chr(9) . 'array(\'action\' => \'edit\', \'block_id\' => $blockId, \'frame_id\' => $frameId),',
				chr(9) . 'null, \'view\'',
				');',
				'',
				'//チェック',
				'$header = $this->controller->response->header();',
				'$pattern = \'' . $this->pluginSingularizeUnderscore . '/' . $this->pluginSingularizeUnderscore . '/index/\' . $blockId;',
				'$this->assertTextContains($pattern, $header[\'Location\']);',
			);
		}
		$output .=
			$this->_classMethod(
				$action . '()アクションのPOSTリクエストテスト',
				array('@return void'), 'test' . ucfirst($function) . 'Post()', $process
			);

		//POSTリクエストのValidationErrorテスト
		if ($this->isManagerPlugin()) {
			$process = array(
				'$this->_mockForReturnFalse(\'TODO:MockにするModel名書く\', \'TODO:Mockにするメソッド名書く\');',
				'',
				'//テスト実行',
				'$this->_testPostAction(',
				chr(9) . '\'put\', $this->__data(),',
				chr(9) . 'array(\'action\' => \'edit\'),',
				chr(9) . 'null, \'view\'',
				');',
				'//' . '$this->_testPostAction(',
				'//' . chr(9) . '\'put\', $this->__data(),',
				'//' . chr(9) . 'array(\'action\' => \'edit\'),',
				'//' . chr(9) . '\'BadRequestException\', \'view\'',
				'//' . ');',
				'',
				'//TODO:必要に応じてassert書く',
			);
		} else {
			$process = array(
				'$this->_mockForReturnCallback(\'TODO:MockにするModel名書く\', \'TODO:Mockにするメソッド名書く\', function () {',
				chr(9) . '$model = \'TODO:ValidationErrorのModel\';',
				chr(9) . '$message = \'TODO:ValidationErrorのメッセージ\';',
				chr(9) . '$this->controller->$model->invalidate(\'TODO:ValidationErrorのField\', $message);',
				chr(9) . 'return false;',
				'});',
				'',
				'//テストデータ',
				'$frameId = \'6\';',
				'$blockId = \'2\';',
				'',
				'//テスト実行',
				'//TODO:処理によって必要な方を有効にする',
				'//テスト実行',
				'$this->_testPostAction(',
				chr(9) . '\'put\', $this->__data(),',
				chr(9) . 'array(\'action\' => \'edit\', \'block_id\' => $blockId, \'frame_id\' => $frameId),',
				chr(9) . 'null, \'view\'',
				');',
				'//' . '$this->_testPostAction(',
				'//' . chr(9) . '\'put\', $this->__data(),',
				'//' . chr(9) . 'array(\'action\' => \'edit\', \'block_id\' => $blockId, \'frame_id\' => $frameId),',
				'//' . chr(9) . '\'BadRequestException\', \'view\'',
				'//' . ');',
				'',
				'//チェック',
				'$message = \'TODO:ValidationErrorのメッセージ\';',
				'$this->assertTextContains($message, $this->view);',
				'',
				'//TODO:必要に応じてassert書く',
			);
		}
		$output .=
			$this->_classMethod(
				'ValidationErrorテスト',
				array('@return void'), 'test' . ucfirst($function) . 'PostValidationError()', $process
			);

		$output .=
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

	/**
	 * Blockのコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isManagerPlugin() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		if (preg_match('/page_editable/', $file) ||
				preg_match('/block_editable/', $file) ||
				preg_match('/content_publishable/', $file) ||
				preg_match('/content_editable/', $file) ||
				preg_match('/content_creatable/', $file) ||
				preg_match('/content_readable/', $file)) {
			return false;
		} else {
			return true;
		}
	}

}