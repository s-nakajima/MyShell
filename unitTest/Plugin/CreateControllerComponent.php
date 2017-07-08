<?php
/**
 * Controller/Componentのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controller/Componentのテストファイル生成クラス
 */
Class CreateControllerComponent extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## Controller/Componentのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * Controller/Componentのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions();
		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if (substr($param[0], 0, strlen('initialize')) === 'initialize' ||
					substr($param[0], 0, strlen('startup')) === 'startup' ||
					substr($param[0], 0, strlen('beforeRender')) === 'beforeRender') {
				(new CreateControllerComponent4Event($this->testFile))->createTest($param);

			} else {
				(new CreateControllerComponent4Other($this->testFile))->createTest($param);
			}
		}
	}

}

