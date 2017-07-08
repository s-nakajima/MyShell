#!/bin/bash

echo "cd ${SET_DIR}/app/Console/"
cd ${SET_DIR}/app/Console/

#table=`grep -e "public \\$.\+ = array" ${PUGLIN_PATH}/Config/Schema/schema.php`
table=${CREATE_MODELS}
for tbl in ${table}; do
	case "${tbl}" in
		"public" ) continue ;;
		"=" ) continue ;;
		"array(" ) continue ;;
		* )

		#CAKE_BAKE="bake model ${PLUGIN_NAME}.`echo $tbl | cut -b 2-` -c master"
		CAKE_BAKE="bake model ${PLUGIN_NAME}.${tbl} -c master"
		echo "./cake ${CAKE_BAKE}"
		./cake ${CAKE_BAKE}
	esac
done


for model in `ls ${PUGLIN_PATH}/Model/`; do
	modelFile=${PUGLIN_PATH}/Model/${model}

	if [ -f $modelFile ]; then
		funcCleanPHPDoc ${modelFile}
	fi
done


modelFile=${PUGLIN_PATH}/Model/${PLUGIN_NAME}AppModel.php
appUsesValue=`grep "App::uses" ${modelFile}`
classValue=`grep "class .* {" ${modelFile}`

echo "create ${modelFile}"
cat << _EOF_ > $modelFile
<?php
/**
 * ${PLUGIN_NAME}App Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author ${AUTHOR_NAME} <${AUTHOR_EMAIL}>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

${appUsesValue}

/**
 * ${PLUGIN_NAME}App Model
 *
 * @author ${AUTHOR_NAME} <${AUTHOR_EMAIL}>
 * @package ${PHPDOC_PROJECT}\\${PLUGIN_NAME}\\Model
 */
${classValue}

}
_EOF_
