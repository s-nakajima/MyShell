<?php
/**
 * Console/Commandのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Console/Commandのテストファイル生成クラス
 */
Class CreateConsoleCommand extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## Console/Commandのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * Console/Commandのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions();

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			$this->createNetCommonsTestCaseFile('NetCommonsConsoleTestCase');
			$testSuitePlugin = $this->plugin . '.TestSuite';
			$testSuiteTest = $this->plugin . 'ConsoleTestCase';

			if ($param[0] === 'getOptionParser') {
				$this->_createGetOptionParser($param, $testSuitePlugin, $testSuiteTest);
			} else {
				$this->_create($param, $testSuitePlugin, $testSuiteTest);
			}
		}
	}

	/**
	 * Consoleのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	private function __createCommonHeader($param, $testSuitePlugin, $testSuiteTest) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->plugin . Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) .
						$this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

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
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' .
						str_replace('/', '\\', $this->testFile['dir']) . '\\' .
						Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
					'Fixtures',
					'array',
					'public',
					'fixtures',
					array(
						'array();',
					)
			) .
			$this->_classVariablePlugin();

		$output .=
			$this->_classVariable(
					'Shell name',
					'string',
					'protected',
					'_shellName',
					array(
						'\'' . $this->testFile['class'] . '\';',
					)
			);

		return $output;
	}

	/**
	 * Consoleのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	protected function _create($param, $testSuitePlugin, $testSuiteTest) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->plugin . Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) .
						$this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//出力文字列
		$output = $this->__createCommonHeader($param, $testSuitePlugin, $testSuiteTest);

		$arguments = explode(', ', $argument);
		$processes = array(
			'$shell = $this->_shellName;',
			'$this->$shell = $this->loadShell($shell);',
			'',
			'//チェック',
			'$this->$shell->expects($this->at(0))->method(\'out\')',
			chr(9) . '->with(\'TODO:ここに出力内容を書く\');',
		);

		$methodArg = '';
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/^([\$_0-9a-zA-Z]+)( \= )?(.*)/', $arg, $matches)) {
				if (! $methodArg) {
					$processes[] = '';
					$processes[] = '//データ生成';
				}
				$processes[] = $matches[1] . ' = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', ' . $matches[1];
			}
		}
		$processes[] = '';
		$processes[] = '//テスト実施';
		if (preg_match('/@return void/', $param[2])) {
			$processes[] = '$this->$shell->' . $function . '(' . substr($methodArg, 2) . ');';
		} else {
			$processes[] = '$result = $this->$shell->' . $function . '(' . substr($methodArg, 2) . ');';
			$processes[] = '';
			$processes[] = '//チェック';
			$processes[] = '//TODO:assertを書く';
			$processes[] = 'var_export($result);';
		}

		$output .=
			$this->_classMethod(
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				$processes
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

	/**
	 * Consoleのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	protected function _createGetOptionParser($param, $testSuitePlugin, $testSuiteTest) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->plugin . Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) .
						$this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//出力文字列
		$output = $this->__createCommonHeader($param, $testSuitePlugin, $testSuiteTest);

		$processes = array(
			'$shell = $this->_shellName;',
			'$this->$shell = $this->loadShell($shell);',
			'',
			'//事前準備',
			'////TODO: サブタスクがあれば、Mock作る',
			'//$task = \'TODO:サブタスク名(キャメル形式)\';',
			'//$this->$shell->$task = $this->getMock($task,',
			'//' . chr(9) . chr(9) . 'array(\'getOptionParser\'), array(), \'\', false);',
			'//$this->$shell->$task->expects($this->once())->method(\'getOptionParser\')',
			'//' . chr(9) . '->will($this->returnValue(true));',
			'',
			'//テスト実施',
			'$result = $this->$shell->getOptionParser();',
			'',
			'//チェック',
			'$this->assertEquals(\'ConsoleOptionParser\', get_class($result));',
			'',
			'//' . '//サブタスクヘルプのチェック',
			'//' . '$expected = array();',
			'//' . '$actual = array();',
			'//' . '$subCommands = array(',
			'//' . chr(9) . '\'TODO:サブタスク名(snake形式)\' => \'TODO:サブタスクのヘルプ\',',
			'//' . ');',
			'//' . 'foreach ($subCommands as $subCommand => $helpMessage) {',
			'//' . chr(9) . '$expected[] = $subCommand . \' \' . $helpMessage;',
			'//' . chr(9) . '$actual[] = $result->subcommands()[$subCommand]->help(strlen($subCommand) + 1);',
			'//' . '}',
			'//' . '$this->assertEquals($expected, $actual);',
			'',
			'//' . '//オプションヘルプのテスト',
			'//' . '$expected = array();',
			'//' . '$actual = array();',
			'//' . '$paserOptions = array(',
			'//' . chr(9) . '\'TODO:オプションキー\' => \'TODO:オプションヘルプ\',',
			'//' . ');',
			'//' . 'foreach ($paserOptions as $option => $helpMessage) {',
			'//' . chr(9) . '$expected[] = \'--\' . $option . \' \' . $helpMessage;',
			'//' . chr(9) . '$actual[] = $result->options()[$option]->help(strlen($option) + 3);',
			'//' . '}',
			'//' . '$this->assertEquals($expected, $actual);',
			'',
			'//' . '//引数ヘルプのチェック',
			'//' . '$expected = array();',
			'//' . '$actual = array();',
			'//' . '$arguments = array(',
			'//' . chr(9) . '\'TODO:引数名\' => \'TODO:引数ヘルプ\'',
			'//' . ');',
			'//' . '$index = 0;',
			'//' . 'foreach ($arguments as $arg => $helpMessage) {',
			'//' . chr(9) . '$expected[] = $arg . \' \' . $helpMessage;',
			'//' . chr(9) . '$actual[] = $result->arguments()[$index]->help(strlen($arg) + 1);',
			'//' . '}',
			'//' . '$this->assertEquals($expected, $actual);',
		);

		$output .=
			$this->_classMethod(
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				$processes
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

