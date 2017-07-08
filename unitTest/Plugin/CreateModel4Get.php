<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel4Get extends CreateModel4 {

	/**
	 * Model::getXxxx()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	public function createTest($param) {
		if ($this->isWorkflow()) {
			$testSuitePlugin = 'Workflow';
			$testSuiteTest = 'WorkflowGetTest';
		} else {
			$this->createNetCommonsTestCaseFile('NetCommonsGetTest');
			$testSuitePlugin = $this->plugin;
			$testSuiteTest = $this->plugin . 'GetTest';
		}

		//出力文字列
		(new CreateModel4Other($this->testFile))->createTest($param, $testSuiteTest, $testSuitePlugin);
	}

}

