#!/bin/bash

##
# Testファイルを作成
##
TEST_PATH=${PUGLIN_PATH}/Test

for path1 in `ls ${TEST_PATH}/`; do
	if [ -f ${TEST_PATH}/$path1 ]; then
		funcCleanPHPDoc ${TEST_PATH}/$path1
		continue
	fi
	for path2 in `ls ${TEST_PATH}/$path1/`; do
		if [ -f ${TEST_PATH}/$path1/$path2 ]; then
			funcCleanPHPDoc ${TEST_PATH}/$path1/$path2
			continue
		fi
		for path3 in `ls ${TEST_PATH}/$path1/$path2`; do
			if [ -f ${TEST_PATH}/$path1/$path2/$path3 ]; then
				funcCleanPHPDoc ${TEST_PATH}/$path1/$path2/$path3
				continue
			fi
			for path4 in `ls ${TEST_PATH}/$path1/$path2/$path3`; do
				if [ -f ${TEST_PATH}/$path1/$path2/$path3/$path4 ]; then
					funcCleanPHPDoc ${TEST_PATH}/$path1/$path2/$path3/$path4
					continue
				fi
				for path5 in `ls ${TEST_PATH}/$path1/$path2/$path3/$path4`; do
					if [ -f ${TEST_PATH}/$path1/$path2/$path3/$path4/$path5 ]; then
						funcCleanPHPDoc ${TEST_PATH}/$path1/$path2/$path3/$path4/$path5
						continue
					fi
				done
			done
		done
	done
done


controllerFile=${PUGLIN_PATH}/Test/Case/All${PLUGIN_NAME}Test.php
echo "create ${controllerFile}"
cat << _EOF_ > $controllerFile
<?php
/**
 * ${PLUGIN_NAME} All Test Suite
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author ${AUTHOR_NAME} <${AUTHOR_EMAIL}>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsTestSuite', 'NetCommons.TestSuite');

/**
 * ${PLUGIN_NAME} All Test Suite
 *
 * @author ${AUTHOR_NAME} <${AUTHOR_EMAIL}>
 * @package ${PHPDOC_PROJECT}\\${PLUGIN_NAME}\\Test\\Case
 * @codeCoverageIgnore
 */
class All${PLUGIN_NAME}Test extends NetCommonsTestSuite {

/**
 * All test suite
 *
 * @return NetCommonsTestSuite
 */
	public static function suite() {
		\$plugin = preg_replace('/^All([\w]+)Test\$/', '\$1', __CLASS__);
		\$suite = new NetCommonsTestSuite(sprintf('All %s Plugin tests', \$plugin));
		//\$suite->addTestDirectoryRecursive(CakePlugin::path(\$plugin) . 'Test' . DS . 'Case');
		return \$suite;
	}
}
_EOF_
