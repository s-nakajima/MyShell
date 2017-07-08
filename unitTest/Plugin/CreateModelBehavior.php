<?php
/**
 * ModelBehaviorのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * ModelBehaviorのテストファイル生成クラス
 */
Class CreateModelBehavior extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## ModelBehaviorのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * ModelBehaviorのテストコード生成
	 *
	 * @return void
	 */
	public function create() {
		$functions = $this->getFunctions(true);
		$eventSave = $eventDelete = $eventFind = $eventValidate = false;

		foreach ($functions as $param) {
			if (substr($param[0], 0, strlen('setup')) === 'setup') {
				continue;
			}

			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if ($param[0] === 'beforeSave' || $param[0] === 'afterSave') {
				if (! $eventSave) {
					(new CreateModelBehavior4Event($this->testFile))->createTest(array('save'), array('$data' => 'fixture'));
					$eventSave = true;
				}

			} elseif ($param[0] === 'beforeDelete' || $param[0] === 'afterDelete') {
				if (! $eventDelete) {
					(new CreateModelBehavior4Event($this->testFile))->createTest(array('delete'), array('$data' => 'fixture'));
					$eventDelete = true;
				}

			} elseif ($param[0] === 'beforeFind' || $param[0] === 'afterFind') {
				if (! $eventFind) {
					(new CreateModelBehavior4Event($this->testFile))->createTest(
						array('find'), array('$type' => '\'first\'', '$query' => 'array()')
					);
					$eventFind = true;
				}

			} elseif ($param[0] === 'beforeValidate' || $param[0] === 'afterValidate') {
				if (! $eventValidate) {
					(new CreateModelBehavior4Event($this->testFile))->createTest(array('validates'), array('$data' => 'fixture'));
					$eventValidate = true;
				}

			} elseif ($param[5]) {
				(new CreateModelBehavior4Scope($this->testFile))->createTest($param);

			} else {
				(new CreateModelBehavior4Other($this->testFile))->createTest($param);
			}
		}
	}

	/**
	 * private、protectedメソッドテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function createScope($param) {
		$function = $param[0];
		$argument = $param[1];
		$scope = $param[4];
		$scopeFile = $param[5];


	}
}

