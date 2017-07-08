<?php
/**
 * 各プラグインの共通クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * 各プラグインの共通クラス
 */
Class Plugin {

	/**
	 * プラグイン名
	 */
	public $plugin = null;

	/**
	 * プラグインの種類
	 */
	public $pluginType = null;

	/**
	 * スキーマ
	 */
	public $schemas = array();

	/**
	 * テスト対象のファイル保管配列
	 */
	public $testFiles = array();

	/**
	 * コンストラクター
	 */
	public function __construct() {
		$this->plugin = getenv('PLUGIN_NAME');
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

		$this->searchFiles('');
	}

	/**
	 * デストラクター.
	 */
	function __destruct() {
		output(chr(10));
		output('UnitTest用ファイル作成が終了しました ' . chr(10));
		output('_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/_/' . chr(10));
	}

	/**
	 * ロード処理
	 *
	 * @return void
	 */
	public function load() {
		if (getenv('PLUGIN_TYPE') && getenv('PLUGIN_TYPE') !== 'All') {
			$this->testFiles = Hash::extract($this->testFiles, '{n}[type=' . getenv('PLUGIN_TYPE') . ']');
		}
		if (getenv('TEST_FILE_NAME')) {
			$this->testFiles = Hash::extract($this->testFiles, '{n}[file=' . getenv('TEST_FILE_NAME') . ']');
		}

		$elementFiles = array();
		foreach ($this->testFiles as $testFile) {
			if ($testFile['type'] === 'View') {
				continue;
			}

			$class = 'Create' . Inflector::camelize(strtr($testFile['type'], '/', ' '));
			if (! class_exists($class)) {
				$class = 'CreateOther';
			}

			(new $class($testFile))->create();
		}
	}

	/**
	 * ファイルのサーチ
	 *
	 * @param string $dirName ディレクトリ名
	 * @return void
	 */
	public function searchFiles($dirName) {
		if (substr($dirName, 0, 1) === '/') {
			$dirName = substr($dirName, 1);
		}
		$dirPath = PLUGIN_ROOT_DIR . $dirName;
		$dir = dir($dirPath);

		while (false !== ($fileName = $dir->read())) {
			if (! in_array($fileName, ['.', '..', '.git', 'Schema', 'Migration', 'Test'], true) &&
					! is_file($dirPath . '/' . $fileName)) {

				$this->searchFiles($dirName . '/' . $fileName);
			}
			if (in_array(substr($fileName, -3), ['ctp', 'php'], true) &&
					is_file($dirPath . '/' . $fileName)) {

				$this->testFiles[] = array(
					'dir' => $dirName,
					'file' => substr($fileName, 0, -4),
					'path' => $dirPath . '/' . $fileName,
					'extension' => substr($fileName, -3),
					'type' => $this->_getTestType($dirName),
					'class' => $this->_getClassName($fileName),
				);
			}
		}
	}

	/**
	 * タイプの取得
	 *
	 * @param string $dirName ディレクトリ名
	 * @return string|null テストType文字列
	 */
	protected function _getTestType($dirName) {
		$types = [
			'Config',
			'Console/Command/Task',
			'Console/Command',
			'Model/Behavior',
			'Controller/Component',
			'View/Helper',
			'View/Elements',
			'Model',
			'Controller',
			'View',
			'TestSuite',
		];
		foreach ($types as $type) {
			if (substr($dirName, 0, strlen($type)) === $type) {
				return $type;
			}
		}
		if (strpos($dirName, '/') !== false) {
			return substr($dirName, 0, strpos($dirName, '/'));
		} else {
			return $dirName;
		}
	}

	/**
	 * クラス名の取得
	 *
	 * @param string $fileName ファイル名
	 * @return string|bool Class名文字列
	 */
	protected function _getClassName($fileName) {
		if (substr($fileName, -3) === 'php') {
			return substr($fileName, 0, -4);
		} else {
			return null;
		}
	}

	/**
	 * テストディレクトリの作成
	 *
	 * @param array $testFile ファイル名
	 * @return bool
	 */
	public function createTestDir($testFile) {
		$createPath = PLUGIN_TEST_DIR . $testFile['dir'] . '/' . Inflector::camelize(ucfirst($testFile['file']));
		if (! file_exists($createPath)) {
			$result = (new Folder())->create($createPath);
			if (! $result) {
				output(sprintf('%sディレクトリの作成に失敗しました。', $createPath));
				exit(1);
			} else {
				output(sprintf('%sディレクトリの作成しました。', $createPath));
			}
			return $result;
		}
		return true;
	}

}

