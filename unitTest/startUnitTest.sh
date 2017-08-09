#!/bin/bash -ex
CURDIR=`pwd`
cd /var/www/app/

if [ "$1" = "--add-all-test" ] ; then
	export ALL_TEST_SUITE=1; export ALL_TEST_SUITE
	export PLUGIN_NAME=$2; export PLUGIN_NAME
	export PLUGIN_TYPE=$3; export PLUGIN_TYPE
	export TEST_FILE_NAME=$4; export TEST_FILE_NAME
	export TEST_METHOD=$5; export TEST_METHOD
else
	export ALL_TEST_SUITE=0; export ALL_TEST_SUITE
	export PLUGIN_NAME=$1; export PLUGIN_NAME
	export PLUGIN_TYPE=$2; export PLUGIN_TYPE
	export TEST_FILE_NAME=$3; export TEST_FILE_NAME
	export TEST_METHOD=$4; export TEST_METHOD
fi

if [ "${PLUGIN_NAME}" = "" ]; then
	echo "エラー：プラグインを入力してください。"
	echo ""
fi
if [ "${PLUGIN_NAME}" = "" -o "${PLUGIN_NAME}" = "-h" -o "${PLUGIN_NAME}" = "?" ]; then
	echo "Usage: bash startUnitTest.sh [--add-all-test] <plugin_name> [<type> [<file> [<method>]]]"
	echo ""
	echo "※ --add-all-test を指定すると、AllXxxxxTest.phpは作成する。"
	echo ""
	echo ""
	echo "Argments: "
	echo "  ≪第一引数(plugin_name  キャメル形式)≫"
	echo "  プラグイン名  ※必須項目です。"
	echo ""
	echo "  ≪第二引数(type)≫"
	echo "  Config                Configのテストファイル(routes.php)を作成する"
	echo "  Console/Command       Console/Commandのテストファイルを作成する"
	echo "  Console/Command/Task  Console/Command/Taskのテストファイルを作成する"
	echo "  Controller            Controllerのテストファイルを作成する"
	echo "  Controller/Component  Controller/Componentのテストファイルを作成する"
	echo "  Model                 Modelのテストファイルを作成する"
	echo "  Model/Behavior        Model/Behaviorのテストファイルを作成する"
	echo "  View/Elements         View/Elementsのテストファイルを作成する"
	echo "  View/Helper           View/Helperのテストファイルを作成する"
	echo "  TestSuite             TestSuiteのテストファイルを作成する"
	echo "  Other                 その他のテストファイルを作成する"
	echo "  All or 省略           全ファイルのテストファイルを作成する"
	echo ""
	echo "  ≪第三引数(file)≫"
	echo "  ファイル名(拡張子含まない)　※省略すると全ファイルが対象となります。"
	echo ""
	echo "  ≪第四引数(method)≫"
	echo "  メソッド名  ※省略すると全メソッドが対象となります。"
	echo ""
	exit 0;
fi
if [ ! -d /var/www/app/app/Plugin/${PLUGIN_NAME} ] ; then
	echo "/var/www/app/app/Plugin/${PLUGIN_NAME}"
	echo "エラー：プラグインがありません。"
	exit 1
fi

export PLUGIN_DIR="Plugin"; export PLUGIN_DIR

#
# 作成者
#
echo "作成者を半角英語で入力してください。[Shohei Nakajima]"
echo -n "> "
read ANS
#ANS=""
if [ "$ANS" = "" ]; then
	ANS="Shohei Nakajima"
fi
AUTHOR_NAME=$ANS; export AUTHOR_NAME
echo "${AUTHOR_NAME}"
echo ""

#
# 作成者メールアドレス
#
echo "作成者のメールアドレスを入力してください。[nakajimashouhei@gmail.com]"
#echo -n "> "
read ANS
#ANS=""
if [ "$ANS" = "" ]; then
	ANS="nakajimashouhei@gmail.com"
fi
AUTHOR_EMAIL=$ANS; export AUTHOR_EMAIL
echo "${AUTHOR_EMAIL}"
echo ""

#
# 既存ファイルに対するふるまい
#
echo "既存ファイルに対してどうしますか。"
echo "[y] 全て上書きする"
echo "[n] 全て上書きしない"
echo "[c] 確認する(デフォルト)"
echo -n "> "
if [ "${TEST_METHOD}" = "" ] ; then
	read ANS
else
	ANS="c"
fi
if [ "$ANS" = "" ]; then
	ANS="c"
fi
EXISTING_FILE=$ANS; export EXISTING_FILE
echo "${EXISTING_FILE}"
echo ""

#
# 全ファイルに対してデフォルトコメントを使用する？
#
echo "全ファイルに対してデフォルトコメントを使用しますか。"
echo "[y] 使用する"
echo "[n] 使用しない(確認する)"
echo -n "> "
#read ANS
ANS="y"
if [ "$ANS" = "" ]; then
	ANS="n"
fi
USE_DEFAULT_COMMENT=$ANS; export USE_DEFAULT_COMMENT
echo "${USE_DEFAULT_COMMENT}"
echo ""

php -c -f ${CURDIR}/startUnitTest.php

#chown www-data:www-data -R /var/www/app/*

#
#-- end of file --
