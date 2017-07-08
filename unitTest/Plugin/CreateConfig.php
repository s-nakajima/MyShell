<?php
/**
 * その他のテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * その他のテストファイル生成クラス
 */
Class CreateConfig extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		if ($testFile['file'] === 'routes') {
			output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
			output(sprintf('## Configのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
			output(print_r($testFile, true));
			parent::__construct($testFile);
		}
	}

	/**
	 * テストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		if ($this->testFile['file'] === 'routes') {
			$this->_createRoutes();
		}
	}

	/**
	 * Config/routesのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	protected function _createRoutes() {
		$testSuitePlugin = 'NetCommons.TestSuite';
		$testSuiteTest = 'NetCommonsRoutesTestCase';

		$className = Inflector::camelize($this->testFile['file']) . 'Test';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				null,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
				),
				$this->testFile['dir'] . '/' . $this->testFile['file'] . 'のテスト'
			) .
			$this->_phpdocClassHeader(
				null,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' . str_replace('/', '\\', $this->testFile['dir']),
				$this->testFile['dir'] . '/' . $this->testFile['file'] . 'のテスト'
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariablePlugin() .
			$this->_classMethod(
				'DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - url URL' . chr(10) .
					' *  - expected 期待値' . chr(10) .
					' *  - settingMode セッティングモード',
				array(
					'@return array',
				),
				'dataProvider()',
				array(
					'//テストデータ',
					'return array(',
					chr(9) . 'array(',
					chr(9) . chr(9) . '\'url\' => \'/' . Inflector::underscore($this->plugin) . '/(TODO:ここにcontroller,actionを書く)\',',
					chr(9) . chr(9) . '\'expected\' => array(',
					chr(9) . chr(9) . chr(9) . '\'plugin\' => \'' . Inflector::underscore($this->plugin) . '\', ',
					chr(9) . chr(9) . chr(9) . '\'controller\' => \'(TODO:ここにcontrollerを書く)\', ',
					chr(9) . chr(9) . chr(9) . '\'action\' => \'(TODO:ここにactionを書く)\',',
					chr(9) . chr(9) . '),',
					chr(9) . chr(9) . '\'settingMode\' => false',
					chr(9) . '),',
					');',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($this->testFile['file'])) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

