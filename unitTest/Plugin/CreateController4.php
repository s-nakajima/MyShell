<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4 extends CreateObject {

	/**
	 * デストラクター.
	 */
	function __destruct() {
		//親クラスの__destruct()を実行させないため
	}

	/**
	 * Controllerのメンバ変数
	 *
	 * @param string $function メソッド名
	 * @return string
	 */
	protected function _getClassVariable($function) {
		$addFixtures = array();
		if ($this->isPackage('"netcommons/workflow"')) {
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
					'Controller name',
					'string',
					'protected',
					'_controller',
					array(
						'\'' . Inflector::underscore(substr($this->testFile['class'], 0, -1 * strlen('Controller'))) . '\';',
					)
			);
	}

}