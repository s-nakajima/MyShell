#!/bin/bash

##############
# デフォルト #
##############
PROJECT="NetCommons3"; export PROJECT
PRO_PACKAGE="$PROJECT"; export PRO_PACKAGE
GIT_PROJECT="NetCommons3"; export GIT_PROJECT
PROJECT_NAMESPACE="netcommons"; export PROJECT_NAMESPACE
PHPDOC_PROJECT="NetCommons"; export PHPDOC_PROJECT

if [ "$1" = "" ]; then
	PLUGIN_NAME="AccessCounters"; export PLUGIN_NAME
	CREATE_MODELS="AccessCounterSettings AccessCounters"; export CREATE_MODELS
else
	PLUGIN_NAME=$1; export PLUGIN_NAME
	CREATE_MODELS=""; export CREATE_MODELS
fi

AUTHOR_NAME="Shohei Nakajima"
AUTHOR_EMAIL="nakajimashouhei@gmail.com"
BOOTSTRAP=/var/www/app/app/Config/bootstrap.php

CURDIR=`pwd`

########
# 関数 #
########

funcCleanPHPDoc() {
	sedFile=$1

	PATTERN="^* @author.\\+"
	REPLACE=" * @author ${AUTHOR_NAME} <${AUTHOR_EMAIL}>"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="^* @link \\+"
	REPLACE=" * @link "
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="^* @license \\+"
	REPLACE=" * @license "
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="${PLUGIN_SINGULAR_NAME}Block"
	REPLACE="${PLUGIN_NAME}Block"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN=" \* @author"
	REPLACE=" \* @author Noriko Arai <arai@nii.ac.jp>\n \* @author"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN=" \* @license.\\+"
	REPLACE=" \* @license http:\/\/www.netcommons.org\/license.txt NetCommons License\n \* @copyright Copyright 2014, NetCommons Project"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="Summary for "
	REPLACE=""
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}
}


##
# イニシャライズ
##

. ${CURDIR}/createPluginInitialize.sh

cd /var/www/app/


##
# bake pluginの実行
##
echo "bake pluginの実行します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginBakePlugin.sh
fi
echo ""


##
# Modelファイルを作成
##
echo "Modelファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginModel.sh
fi
echo ""


##
# configファイルを作成
##
##
# configファイルを作成
##
#configRemove=0
#CONFIG_FILE=${PUGLIN_PATH}/Config/config.php
#if [ ! -f ${CONFIG_FILE} ]; then
#	echo "${CONFIG_FILE}を作成します。"
#	echo "cat $CONFIG_FILE"
#	cat << _EOF_ > $CONFIG_FILE
#<?php
#\$config = array();
#return \$config;
#_EOF_
#
#	configRemove=1
#fi

##
# Migrationファイルを作成
##
echo "Migrationファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginMigration.sh
fi
echo ""


##
# Controllerファイルを作成
##
echo "Controllerファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginController.sh
fi
echo ""

##
# Viewファイルを作成
##
echo "Viewファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginView.sh
fi
echo ""

##
# Testファイルを作成
##
echo "Testファイルを整形します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginTest.sh
fi
echo ""

#cd ${CURDIR}
#
#if [ $configRemove -eq 1 -a -f ${CONFIG_FILE} ]; then
#	echo "rm ${CONFIG_FILE}"
#	rm ${CONFIG_FILE}
#fi

if [ ! "${RMCONTROLLER}" = "" ] ; then
	echo "rm ${RMCONTROLLER}"
	rm ${RMCONTROLLER}
fi

cd ${CURDIR}

echo ""
echo "${PLUGIN_NAME}プラグインを作成しました。"
echo ""

#-- end --