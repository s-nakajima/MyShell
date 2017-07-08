<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## Modelのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * SaveFieldかどうかチェック
	 *
	 * @return bool
	 */
	public function isSaveField($param) {
		if (preg_match('/' . preg_quote('->save(') . '/', $param[3])) {
			return false;
		}

		if (preg_match('/' . preg_quote('->saveField(') . '/', $param[3]) ||
				preg_match('/' . preg_quote('->updateAll(') . '/', $param[3])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Modelのテストコード生成
	 *
	 * @return void
	 */
	public function create() {
		$functions = $this->getFunctions(true);

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if (substr($param[0], 0, strlen('get')) === 'get') {
				(new CreateModel4Get($this->testFile))->createTest($param);

			} elseif (substr($param[0], 0, strlen('save')) === 'save') {
				if ($this->isSaveField($param)) {
					(new CreateModel4Other($this->testFile))->createTest($param);
				} else {
					(new CreateModel4Save($this->testFile))->createTest($param);
				}

			} elseif (substr($param[0], 0, strlen('delete')) === 'delete') {
				(new CreateModel4Delete($this->testFile))->createTest($param);

			} elseif (substr($param[0], 0, strlen('update')) === 'update' ||
					substr($param[0], 0, strlen('validate')) === 'validate' ||
					substr($param[0], 0, strlen('create')) === 'create') {
				(new CreateModel4Other($this->testFile))->createTest($param);

			} elseif ($param[0] === 'beforeSave' || $param[0] === 'afterSave') {
				(new CreateModel4Event($this->testFile))->createTest(array('save', ''));

			} elseif ($param[0] === 'beforeDelete' || $param[0] === 'afterDelete') {
				(new CreateModel4Event($this->testFile))->createTest(array('delete', ''));

			} elseif ($param[0] === 'beforeFind' || $param[0] === 'afterFind') {
				(new CreateModel4Event($this->testFile))->createTest(array('find', ''));

			} elseif ($param[0] === 'beforeValidate' || $param[0] === 'afterValidate') {
				(new CreateModel4Validate($this->testFile))->createTest(array('validate', ''));

			} elseif ($param[5]) {
				(new CreateModel4Scope($this->testFile))->createTest($param);

			} else {
				output(sprintf('%sは、どのタイプのテストですか。', $param[0] . '()'));
				output('[0 or 省略] 下記以外');
				output('[1] Get');
				output('[2] Save');
				output('[3] Delete');
				output('[4] Validate');
				output('[5] Event');
				output('[s] 無視する');
				echo '> ';
				$input = trim(fgets(STDIN));
				if ($input === '1') {
					(new CreateModel4Get($this->testFile))->createTest($param);
				} elseif ($input === '2') {
					(new CreateModel4Save($this->testFile))->createTest($param);
				} elseif ($input === '3') {
					(new CreateModel4Delete($this->testFile))->createTest($param);
				} elseif ($input === '4') {
					(new CreateModel4Validate($this->testFile))->createTest($param);
				} elseif ($input === '5') {
					(new CreateModel4Event($this->testFile))->createTest($param);
				} elseif ($input === 's') {
				} else {
					(new CreateModel4Other($this->testFile))->createTest($param);
				}
			}
		}
	}

}

