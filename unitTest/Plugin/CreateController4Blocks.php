<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4Blocks extends CreateController4 {

	/**
	 * Controllerのメンバ変数
	 *
	 * @param string $function メソッド名
	 * @return string
	 */
	protected function _getClassVariable($function) {
		return
			parent::_getClassVariable($function) .
			$this->_classVariable(
				'Edit controller name',
				'string',
				'protected',
				'_editController',
				array(
					'\'' . Inflector::underscore(substr($this->testFile['class'], 0, -1 * strlen('Controller'))) . '\';',
				)
			);
	}
}