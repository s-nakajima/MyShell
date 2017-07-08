<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## Controllerのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * Workflowのコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isWorkflow() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/Workflow\.Workflow/', $file);
	}

	/**
	 * BlockPermissionのコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isBlockPermission() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/Blocks\.BlockRolePermissionForm/', $file);
	}

	/**
	 * Blockのコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isBlock() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/Blocks\.BlockForm/', $file);
	}

	/**
	 * FrameSettingコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isFrameSetting() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/class ' . Inflector::classify($this->plugin) . 'FrameSettingsController extends/', $file);
	}

	/**
	 * MailSettingコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isMailSetting() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/ extends MailSettingsController/', $file);
	}

	/**
	 * AppControllerにNetCommons.Permissionの有無チェック
	 *
	 * @return bool
	 */
	public function isUserRolePermission() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}
		//if (substr($this->testFile['class'], -1 * strlen('AppController') !== 'AppController')) {
		//	return false;
		//}

		$file = file_get_contents($this->testFile['path']);
		if (! preg_match('/NetCommons\.Permission/', $file)) {
			return false;
		}
		return (bool)(preg_match('/PermissionComponent::CHECK_TYEP_SYSTEM_PLUGIN/', $file) ||
				preg_match('/PermissionComponent::CHECK_TYEP_CONTROL_PANEL/', $file));
	}

	/**
	 * Controllerのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions();

		if ($this->isMailSetting() && ! $functions) {
			$class = 'CreateController4MailSettingsController';

			$param = array('edit', '');
			(new $class($this->testFile, false))->createTest($param);
		}

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if (substr($this->testFile['file'], -1 * strlen('AppController')) === 'AppController') {
				if (substr($param[0], 0, strlen('beforeFilter')) === 'beforeFilter') {
					$action = 'index';
				} else {
					$action = $param[0];
				}
				(new CreateController4AppController($this->testFile, false))->createTest($param, $action);
				continue;
			}

			if ($param[0] === 'beforeFilter') {
				(new CreateController4OtherController($this->testFile, false))->createTest($param, 'index');
				continue;
			}

			if ($this->isWorkflow()) {
				if (class_exists('CreateController4WorkflowController' . ucfirst($param[0]))) {
					$class = 'CreateController4WorkflowController' . ucfirst($param[0]);
				} else {
					output(sprintf('%sは、どのタイプのテストですか。', $param[0] . '()'));
					output('[0 or 省略] 下記以外');
					output('[1] Index');
					output('[2] View');
					output('[3] Add');
					output('[4] Edit');
					output('[5] Delete');
					echo '> ';
					$input = trim(fgets(STDIN));
					if ($input === '1') {
						$class = 'CreateController4WorkflowControllerIndex';
					}  elseif ($input === '2') {
						$class = 'CreateController4WorkflowControllerView';
					}  elseif ($input === '3') {
						$class = 'CreateController4WorkflowControllerAdd';
					}  elseif ($input === '4') {
						$class = 'CreateController4WorkflowControllerEdit';
					}  elseif ($input === '5') {
						$class = 'CreateController4WorkflowControllerDelete';
					} else {
						$class = 'CreateController4OtherController';
					}
				}

			} elseif ($this->isMailSetting()) {
				$class = 'CreateController4MailSettingsController';

			} elseif ($this->isFrameSetting()) {
				$class = 'CreateController4FrameSettingsController';

			} elseif ($this->isBlock()) {
				if (substr($param[0], 0, strlen('index')) === 'index') {
					$class = 'CreateController4BlocksController';
					(new $class($this->testFile, false))->createTest($param);

					$param[0] = 'indexPaginator';
					$class = 'CreateController4BlocksPaginatorController';

				} elseif (substr($param[0], 0, strlen('add')) === 'add' ||
						substr($param[0], 0, strlen('edit')) === 'edit' ||
						substr($param[0], 0, strlen('delete')) === 'delete') {
					$param[0] = 'edit';
					$class = 'CreateController4BlocksControllerEdit';
				} else {
					$class = 'CreateController4OtherController';
				}

			} elseif ($this->isBlockPermission()) {
				$param[0] = 'edit';
				$class = 'CreateController4BlockPermissionControllerEdit';
			} elseif (substr($param[0], 0, strlen('add')) === 'add') {
				$class = 'CreateController4AddController';
			} elseif (substr($param[0], 0, strlen('edit')) === 'edit') {
				$class = 'CreateController4EditController';
			} else {
				$class = 'CreateController4OtherController';
			}

			(new $class($this->testFile, false))->createTest($param);
		}
		if ($this->isUserRolePermission()) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s', 'permission()') . chr(10));
			$class = 'CreateController4UserRolePermission';
			(new $class($this->testFile, false))->createTest();
		}
	}

}