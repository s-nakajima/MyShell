<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel4 extends CreateObject {

	/**
	 * デストラクター.
	 */
	function __destruct() {
		//親クラスの__destruct()を実行させないため
	}

	/**
	 * Workflowのモデルかどうかチェック
	 *
	 * @return bool
	 */
	public function isWorkflow() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/' . chr(9) . '\'Workflow\.Workflow/', $file);
	}

	/**
	 * Modelのメンバ変数
	 *
	 * @param string $function メソッド名
	 * @return string
	 */
	protected function _getClassVariable($function) {
		$addFixtures = array();
		if ($this->isWorkflow() || $this->isPackage('"netcommons/workflow"')) {
			$addFixtures['workflow.workflow_comment'] = 'workflow.workflow_comment';
		}
		if ($this->isPackage('"netcommons/categories"')) {
			$addFixtures['categories.category'] = 'categories.category';
			$addFixtures['categories.category_order'] = 'categories.category_order';
			$addFixtures['categories.categories_language'] = 'categories.categories_language';
		}

		return
			$this->_classVariableFixtures($addFixtures) .
			$this->_classVariablePlugin() .
			$this->_classVariable(
					'Model name',
					'string',
					'protected',
					'_modelName',
					array(
						'\'' . $this->testFile['class'] . '\';',
					)
			) .
			$this->_classVariable(
					'Method name',
					'string',
					'protected',
					'_methodName',
					array(
						'\'' . $function . '\';',
					)
			);
	}

}

