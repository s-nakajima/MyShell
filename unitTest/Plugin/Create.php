<?php
/**
 * Createする共通クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Createする共通クラス
 */
Class CreateObject {

	/**
	 * ファイルデータ
	 */
	public $testFile = null;

	/**
	 * プラグイン名
	 */
	public $plugin = null;

	/**
	 * プラグイン名
	 */
	public $pluginSingularize = null;

	/**
	 * プラグイン名
	 */
	public $pluginSingularizeUnderscore = null;

	/**
	 * プラグイン名
	 */
	public $pluginVariable = null;

	/**
	 * プラグインの種類
	 */
	public $pluginType = null;

	/**
	 * スキーマ
	 */
	public $schemas = array();

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null, $isCreate = true) {
		$this->testFile = $testFile;

		$this->plugin = getenv('PLUGIN_NAME');
		$this->pluginSingularize = Inflector::singularize($this->plugin);
		$this->pluginSingularizeUnderscore = Inflector::underscore($this->pluginSingularize);
		$this->pluginVariable = Inflector::variable($this->pluginSingularize);

		$this->pluginType = getenv('PLUGIN_TYPE');
		$this->authorName = getenv('AUTHOR_NAME');
		$this->authorEmail = getenv('AUTHOR_EMAIL');

		if (file_exists(PLUGIN_ROOT_DIR . 'Config/Schema/schema.php')) {
			require_once PLUGIN_ROOT_DIR . 'Config/Schema/schema.php';

			if (class_exists($this->plugin . 'Schema')) {
				$class = $this->plugin . 'Schema';
				$Schema = new $class;

				$this->schemas = get_class_vars(get_class($Schema));
				unset($this->schemas['connection']);
			}
		}

		if (! $isCreate) {
			return;
		}

		//テストディレクトリ作成
		if (! $this->createTestDir()) {
			return false;
		}
		//emptyファイル作成
		if (! $this->createFile('empty')) {
			return false;
		}
		//AllTestSuiteファイル作成
		if (! $this->createAllTestSuiteFile('', $this->testFile['type'])) {
			return false;
		}
		if ($this->testFile['class']) {
			if (! $this->createAllTestSuiteFile($this->testFile['dir'] . '/', $this->testFile['file'])) {
				return false;
			}
		}
	}

	/**
	 * デストラクター.
	 */
	function __destruct() {
		if (isset($this->testFile['file']) && $this->testFile['type'] !== 'View/Elements') {
			$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file'])) . '/empty';
		} else {
			$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/empty';
		}
		if (file_exists($filePath)) {
			if (! $this->deleteTestDir()) {
				return false;
			}
		}
	}

	/**
	 * AllTestSuiteファイルの作成
	 *
	 * @return bool
	 */
	public function createAllTestSuiteFile($dir, $file) {
		if (! getenv('ALL_TEST_SUITE')) {
			return true;
		}

		$className = 'All' . $this->plugin . Inflector::camelize(strtr($dir . $file, '/', '_')) . 'Test';
		$filePath = PLUGIN_TEST_DIR . $dir . $className . '.php';

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'NetCommonsTestSuite\', \'NetCommons.TestSuite\')',
				),
				'All ' . $file . ' Test suite'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' . str_replace('/', '\\', $file),
				'All ' . $file . ' Test suite'
			) .
			'class ' . $className . ' extends NetCommonsTestSuite {' . chr(10) .
			'' . chr(10) .
			$this->_classMethod(
				'All ' . $file . ' Test suite',
				array(
					'@return NetCommonsTestSuite',
					'@codeCoverageIgnore'
				),
				'suite()',
				array(
					'$name = preg_replace(\'/^All([\\w]+)Test$/\', \'$1\', __CLASS__);',
					'$suite = new NetCommonsTestSuite(sprintf(\'All %s tests\', $name));',
					'$suite->addTestDirectoryRecursive(__DIR__ . DS . \'' . str_replace('/', '\' . DS . \'', $file) . '\');',
					'return $suite;',
				),
				'public static'
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile($filePath, $output);
		$this->deleteFile('empty');

		return true;
	}

	/**
	 * 共通TestCaseファイルの作成
	 *
	 * @return bool
	 */
	public function createNetCommonsTestCaseFile($testCase) {
		if (substr($testCase, 0, strlen('NetCommons')) !== 'NetCommons') {
			return true;
		}

		$testSuitePath = PLUGIN_ROOT_DIR . 'TestSuite/';
		if (! file_exists($testSuitePath)) {
			$result = (new Folder())->create($testSuitePath);
			if (! $result) {
				output('ディレクトリの作成に失敗しました。');
				output(sprintf('(%s)', $testSuitePath) . chr(10));
				exit(1);
			} else {
				output('ディレクトリを作成しました。');
				output(sprintf('(%s)', $testSuitePath) . chr(10));
			}
		}

		$className = $this->plugin . substr($testCase, strlen('NetCommons'));
		$filePath = $testSuitePath . $className . '.php';

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'//@codeCoverageIgnoreStart',
					'App::uses(\'' . $testCase . '\', \'NetCommons.TestSuite\')',
					'//@codeCoverageIgnoreEnd',
				),
				$className . ' TestCase'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\TestSuite',
				$className . ' TestCase',
				array(
					'@codeCoverageIgnore'
				)
			) .
			'abstract class ' . $className . ' extends ' . $testCase . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
				'Fixtures',
				'array',
				'private',
				'__fixtures',
				array(
					'array();',
				)
			) .
			$this->_classVariablePlugin() .
			$this->_classMethod(
				'Fixtures load',
				array(
					'@param string $name The name parameter on PHPUnit_Framework_TestCase::__construct()',
					'@param array  $data The data parameter on PHPUnit_Framework_TestCase::__construct()',
					'@param string $dataName The dataName parameter on PHPUnit_Framework_TestCase::__construct()',
					'@return void',
				),
				'__construct($name = null, array $data = array(), $dataName = \'\')',
				array(
					'if (! isset($this->fixtures)) {',
					chr(9) . '$this->fixtures = array();',
					'}',
					'$this->fixtures = array_merge($this->__fixtures, $this->fixtures);',
					'parent::__construct($name, $data, $dataName);',
				)
			) .
		'}';

		if (! file_exists($filePath)) {
			$result = (new File($filePath))->write($output);
			if (! $result) {
				output('ファイルの作成に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを作成しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
			}
		}

		return true;
	}

	/**
	 * テストディレクトリの作成
	 *
	 * @return bool
	 */
	public function createTestDir() {
		if (isset($this->testFile['file']) &&
				! in_array($this->testFile['type'], ['View/Elements', 'Config'], true)) {
			$dirPath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file']));
		} else {
			$dirPath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/';
		}
		if (! file_exists($dirPath)) {
			$result = (new Folder())->create($dirPath);
			if (! $result) {
				output('ディレクトリの作成に失敗しました。');
				output(sprintf('(%s)', $dirPath) . chr(10));
				exit(1);
			} else {
				output('ディレクトリを作成しました。');
				output(sprintf('(%s)', $dirPath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * テストディレクトリの削除
	 *
	 * @return bool
	 */
	public function deleteTestDir() {
		if (isset($this->testFile['file']) &&
				! in_array($this->testFile['type'], ['View/Elements', 'Config'], true)) {
			$dirPath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file']));
		} else {
			$dirPath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/';
		}
		if (file_exists($dirPath)) {
			$result = (new Folder())->delete($dirPath);
			if (! $result) {
				output('ディレクトリの削除に失敗しました。');
				output(sprintf('(%s)', $dirPath) . chr(10));
				exit(1);
			} else {
				output('ディレクトリを削除しました。');
				output(sprintf('(%s)', $dirPath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * テストディレクトリの作成
	 *
	 * @return bool
	 */
	public function createTestPluginDir($dirName) {
		$dirPath = PLUGIN_TEST_PLUGIN . $dirName;
		if (! file_exists($dirPath)) {
			$result = (new Folder())->create($dirPath);
			if (! $result) {
				output('ディレクトリの作成に失敗しました。');
				output(sprintf('(%s)', $dirPath) . chr(10));
				exit(1);
			} else {
				output('ディレクトリを作成しました。');
				output(sprintf('(%s)', $dirPath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * ファイルの作成
	 *
	 * @param string $fileName ファイル名
	 * @return bool
	 */
	public function createFile($fileName, $output = '') {
		if (substr($fileName, 0, strlen(PLUGIN_ROOT_DIR)) === PLUGIN_ROOT_DIR) {
			$filePath = $fileName;
		} elseif (isset($this->testFile['file']) &&
				! in_array($this->testFile['type'], ['View/Elements', 'Config'], true)) {
			$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file'])) . '/' . $fileName;
		} else {
			$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . $fileName;
		}

		if ($fileName !== 'empty' && file_exists($filePath)) {
			if (getenv('EXISTING_FILE') === 'y') {
				$this->deleteFile($fileName);
			} elseif (getenv('EXISTING_FILE') === 'c') {
				output('ファイルが既に存在します。上書きしますか。');
				output(sprintf('(%s)', $filePath));
				echo '(y)es|(n)o> ';
				if (trim(fgets(STDIN)) === 'y') {
					$this->deleteFile($fileName);
				}
			}
		}

		if (! file_exists($filePath)) {
			$result = (new File($filePath))->write($output);
			if (! $result) {
				output('ファイルの作成に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを作成しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * ファイルの作成
	 *
	 * @param string $fileName ファイル名
	 * @return bool
	 */
	public function createTestPluginFile($fileName, $output = '') {
		$filePath = PLUGIN_TEST_PLUGIN . $fileName;
		if ($fileName !== 'empty' && file_exists($filePath)) {
			if (getenv('EXISTING_FILE') === 'y') {
				$this->deleteTestPluginFile($fileName);
			} elseif (getenv('EXISTING_FILE') === 'c') {
				output('ファイルが既に存在します。上書きしますか。');
				output(sprintf('(%s)', $filePath));
				echo '(y)es|(n)o> ';
				if (trim(fgets(STDIN)) === 'y') {
					$this->deleteTestPluginFile($fileName);
				}
			}
		}

		if (! file_exists($filePath)) {
			$result = (new File($filePath))->write($output);
			if (! $result) {
				output('ファイルの作成に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを作成しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * ファイルの削除
	 *
	 * @param string $fileName ファイル名
	 * @return bool
	 */
	public function deleteFile($fileName) {
		if (substr($fileName, 0, strlen(PLUGIN_ROOT_DIR)) === PLUGIN_ROOT_DIR) {
			$filePath = $fileName;
		} elseif (isset($this->testFile['file']) &&
				! in_array($this->testFile['type'], ['View/Elements', 'Config'], true)) {
			$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file'])) . '/' . $fileName;
		} else {
			$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . $fileName;
		}

		if (file_exists($filePath)) {
			$result = (new File($filePath))->delete();
			if (! $result) {
				output('ファイルの削除に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを削除しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * ファイルの削除
	 *
	 * @param string $fileName ファイル名
	 * @return bool
	 */
	public function deleteTestPluginFile($fileName) {
		$filePath = PLUGIN_TEST_PLUGIN . $fileName;
		if (file_exists($filePath)) {
			$result = (new File($filePath))->delete();
			if (! $result) {
				output('ファイルの削除に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを削除しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * メソッド一覧の取得
	 *
	 * @return array メソッドリスト
	 */
	public function getFunctions($dispScope = false) {
		if (! file_exists($this->testFile['path'])) {
			return true;
		}

		$file = file_get_contents($this->testFile['path']);
		$matches = array();
		$result = array();
		if (preg_match_all('/.*? function ([_a-zA-Z][_a-zA-Z0-9]+?)\((.*)?\)/', $file, $matches)) {
			foreach (array_keys($matches[0]) as $i) {
				if (substr(trim($matches[0][$i]), 0, 1) === '*') {
					continue;
				}
				if (preg_match('/\//', $matches[0][$i]) || ! $dispScope && $matches[1][$i] === '__construct') {
					continue;
				}
				if (getenv('TEST_METHOD') && getenv('TEST_METHOD') !== $matches[1][$i]) {
					continue;
				}

				if (! $dispScope && substr($matches[1][$i], 0, 1) === '_') {
					continue;
				}

				if (substr($matches[1][$i], 0, 2) === '__') {
					$scope = 'Private';
					$scopeFile = '__';
				} elseif (substr($matches[1][$i], 0, 1) === '_') {
					$scope = 'Protected';
					$scopeFile = '_';
				} else {
					$scope = ''; //publicは空値とする
					$scopeFile = '';
				}
				$result[$i] = array(
					$matches[1][$i],
					$matches[2][$i],
					$this->getFunctionComment($matches[1][$i]),
					$this->getFunctionProcess($matches[1][$i]),
					$scope,
					$scopeFile
				);
			}
		}

		return $result;
	}

	/**
	 * メソッドのコメント取得
	 *
	 * @return array メソッドリスト
	 */
	public function getFunctionComment($function) {
		if (! file_exists($this->testFile['path'])) {
			return true;
		}

		$file = file_get_contents($this->testFile['path']);
		$fileAsArray = explode(chr(10), $file);

		$result = '';
		$funcCheck = false;
		foreach ($fileAsArray as $line) {
			if ($funcCheck) {
				if (preg_match('/^(.+)?' . $function . '(.+)/', $line)) {
					break;
				}
				$funcCheck = false;
			}

			if ($line === '/**') {
				$result = $line;
			} else {
				$result .= chr(10) . $line;
			}
			if ($line === ' */') {
				$funcCheck = true;
			} else {
				$funcCheck = false;
			}
		}

		return $result;
	}

	/**
	 * メソッドの処理取得
	 *
	 * @return array メソッドリスト
	 */
	public function getFunctionProcess($function) {
		if (! file_exists($this->testFile['path'])) {
			return true;
		}

		$file = file_get_contents($this->testFile['path']);
		$fileAsArray = explode(chr(10), $file);

		$result = '';
		$funcCheck = false;
		foreach ($fileAsArray as $line) {
			if ($funcCheck) {
				if (preg_match('/^' . chr(9) . '}$/', $line)) {
					$result .= chr(10) . $line;
					break;
				}
			}

			if (preg_match('/^' . chr(9) . '.+?' . $function . '.+{' . '$/', $line)) {
				$result = $line;
				$funcCheck = true;
			} elseif ($funcCheck) {
				$result .= chr(10) . $line;
			}
		}

		return $result;
	}

	/**
	 * Phpdoc FileHeader
	 *
	 * @return string
	 */
	protected function _phpdocFileHeader($function, $appUses = array(), $headerDescription = null) {
		if (! $headerDescription) {
			if (getenv('USE_DEFAULT_COMMENT') !== 'y') {
				output('ファイルの説明文の入力して下さい。');
				output('[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']');
				echo '> ';
				$headerDescription = trim(fgets(STDIN));
			} else {
				$headerDescription = null;
			}
			if (! $headerDescription) {
				$headerDescription = $this->testFile['class'] . '::' . $function . '()のテスト';
			}
		}

		return
			'/**' . chr(10) .
			' * ' . $headerDescription . chr(10) .
			' *' . chr(10) .
			' * @author Noriko Arai <arai@nii.ac.jp>' . chr(10) .
			' * @author ' . $this->authorName . ' <' . $this->authorEmail . '>' . chr(10) .
			' * @link http://www.netcommons.org NetCommons Project' . chr(10) .
			' * @license http://www.netcommons.org/license.txt NetCommons License' . chr(10) .
			' * @copyright Copyright 2014, NetCommons Project' . chr(10) .
			' */' . chr(10) .
			($appUses ? chr(10) . implode(';' .chr(10), $appUses) . ';' .chr(10) . chr(10) : chr(10)) .
			'';
	}

	/**
	 * Phpdoc ClassHeader
	 *
	 * @return string
	 */
	protected function _phpdocClassHeader($function, $package, $headerDescription = null, $params = array()) {
		if (! $headerDescription) {
			if (getenv('USE_DEFAULT_COMMENT') !== 'y') {
				output('クラスの説明文の入力して下さい。');
				output('[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']');
				echo '> ';
				$headerDescription = trim(fgets(STDIN));
			} else {
				$headerDescription = null;
			}
			if (! $headerDescription) {
				$headerDescription = $this->testFile['class'] . '::' . $function . '()のテスト';
			}
		}

		$params = array_merge(array(
			'@author ' . $this->authorName . ' <' . $this->authorEmail . '>',
			'@package ' . $package,
		), $params);
		$outputParams = '';
		foreach ($params as $value) {
			$outputParams .= ' * ' . $value . chr(10);
		}

		return
			'/**' . chr(10) .
			' * ' . $headerDescription . chr(10) .
			' *' . chr(10) .
			$outputParams .
			' */' . chr(10) .
			'';
	}

	/**
	 * Phpdoc ClassVariable
	 *
	 * @return string
	 */
	protected function _classVariable($phpdoc, $type, $scope, $variable, $values) {
		if (! $phpdoc) {
			output(sprintf('%s変数の説明の入力して下さい。', $variable));
			echo '> ';
			$phpdoc = trim(fgets(STDIN));
		}

		$outputValue = '';
		foreach ($values as $value) {
			if (! $outputValue) {
				$outputValue .= $value . chr(10);
			} else {
				$outputValue .= chr(9) . $value . chr(10);
			}
		}

		return
			'/**' . chr(10) .
			' * ' . $phpdoc . chr(10) .
			' *' . chr(10) .
			' * @var ' . $type . chr(10) .
			' */' . chr(10) .
			'' . chr(9) . $scope . ' $' . $variable . ' = ' . $outputValue.
			'' . chr(10) .
			'';
	}

	/**
	 * BlockRolePermissionかどうかチェック
	 *
	 * @return bool
	 */
	public function isBlockRolePermission($file) {
		if (! file_exists($file['path'])) {
			return false;
		}

		$result = file_get_contents($file['path']);
		if (preg_match('/' . preg_quote('element(\'Blocks.block_creatable_setting\'', '/') . '/', $result) &&
				preg_match('/' . preg_quote('element(\'Blocks.block_approval_setting\'', '/') . '/', $result)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * composer.jsonからpackage名があるかチェックする
	 *
	 * @param string $package package名(netcommons/categoriesみたいな感じ)
	 * @return bool
	 */
	public function isPackage($package) {
		if (! file_exists(PLUGIN_ROOT_DIR . 'composer.json')) {
			return false;
		}

		$file = file_get_contents(PLUGIN_ROOT_DIR . 'composer.json');
		return (bool)preg_match('/' . preg_quote($package, '/') . '/', $file);
	}

	/**
	 * Fixtures
	 *
	 * @return string
	 */
	public function _classVariableFixtures($params = array()) {
		$fixtures = array();
		foreach (array_keys($this->schemas) as $fixture) {
			$table = Inflector::underscore($this->plugin) . '.' . Inflector::singularize($fixture);
			if (isset($params[$table])) {
				$fixtures[] = $params[$table];
				unset($params[$table]);
			} else {
				$fixtures[] = $table;
			}
		}
		$fixtures = Hash::merge($fixtures, array_keys($params));
		sort($fixtures);

		$values = array(
			'array('
		);
		foreach ($fixtures as $fixture) {
			$values[] = chr(9) . '\'plugin.' . $fixture . '\',';
		}
		$values[] = ');';

		return
			$this->_classVariable(
				'Fixtures',
				'array',
				'public',
				'fixtures',
				$values
			);
	}

	/**
	 * _classVariablePlugin
	 *
	 * @return string
	 */
	public function _classVariablePlugin() {
		return
			$this->_classVariable(
					'Plugin name',
					'string',
					'public',
					'plugin',
					array(
						'\'' . Inflector::underscore($this->plugin). '\';',
					)
			);
	}

	/**
	 * Phpdoc ClassVariable
	 *
	 * @return string
	 */
	protected function _classMethod($phpdoc, $params, $method, $processes, $scope = 'public') {
		if (! $phpdoc) {
			output(sprintf('%sメソッドの説明の入力して下さい。', $method));
			echo '> ';
			$phpdoc = trim(fgets(STDIN));
		}

		$outputProcess = '';
		foreach ($processes as $process) {
			if ($process) {
				$outputProcess .= chr(9) . chr(9) . $process . chr(10);
			} else {
				$outputProcess .= chr(10);
			}
		}

		$outputParams = '';
		foreach ($params as $value) {
			$outputParams .= ' * ' . $value . chr(10);
		}

		return
			'/**' . chr(10) .
			' * ' . $phpdoc . chr(10) .
			' *' . chr(10) .
			$outputParams .
			' */' . chr(10) .
			'' . chr(9) . $scope . ' function ' . $method . ' {' . chr(10) .
			'' . $outputProcess .
			'' . chr(9) . '}' . chr(10) .
			chr(10) .
			'';
	}

}

