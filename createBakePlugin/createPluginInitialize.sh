#!/bin/bash

#設置場所チェック
SET_DIR="/var/www/app"
if [ ! -e $SET_DIR ]; then
	echo 'Installation location is different';
	exit 0
fi

#プラグイン名
echo "プラグイン名称を半角のキャメル記法で入力してください。[$PLUGIN_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_NAME=${ANS}
fi
if [ "$PLUGIN_NAME" = "" ]; then
	echo "引数が足りません"
	exit 0
fi
echo "${PLUGIN_NAME}"
echo ""

## プラグイン名称(単数形)
cat << _EOF_ > buffer
<?php
include 'common/cake_common.php';
echo Inflector::singularize(getenv('PLUGIN_NAME'));
_EOF_
PLUGIN_SINGULAR_NAME=`php buffer`; export PLUGIN_SINGULAR_NAME
rm buffer

echo "プラグイン名称(単数形)を半角のキャメル記法で入力してください。[$PLUGIN_SINGULAR_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_SINGULAR_NAME=${ANS}; export PLUGIN_SINGULAR_NAME
fi
if [ "$PLUGIN_SINGULAR_NAME" = "" ]; then
	echo "引数が足りません"
	exit 0
fi
echo "${PLUGIN_SINGULAR_NAME}"
echo ""

## スネーク記法
cat << _EOF_ > buffer
<?php
include 'common/cake_common.php';
echo Inflector::underscore(getenv('PLUGIN_NAME'));
_EOF_
PLUGIN_SNAKE_NAME=`php buffer`; export PLUGIN_SNAKE_NAME
rm buffer

echo "プラグイン名称を半角小文字のスネーク記法で入力してください。[$PLUGIN_SNAKE_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_SNAKE_NAME=${ANS}; export PLUGIN_SNAKE_NAME
fi
if [ "$PLUGIN_SNAKE_NAME" = "" ]; then
	echo '引数が足りません'
	exit 0
fi
echo "${PLUGIN_SNAKE_NAME}"
echo ""

## ハイフン記法
cat << _EOF_ > buffer
<?php
include 'common/cake_common.php';
echo preg_replace('/_/', '-', getenv('PLUGIN_SNAKE_NAME'));
_EOF_
PLUGIN_HAIFUN_NAME=`php buffer`; export PLUGIN_HAIFUN_NAME
rm buffer

echo "パッケージ名称を半角英字小文字のハイフン記法で入力してください。[$PLUGIN_HAIFUN_NAME] "
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_HAIFUN_NAME=${ANS}; export PLUGIN_HAIFUN_NAME
fi
if [ "$PLUGIN_HAIFUN_NAME" = "" ]; then
	echo '引数が足りません'
	exit 0
fi
echo "${PLUGIN_HAIFUN_NAME}"
echo ""


#モデル
if [ "$CREATE_MODELS" = "" ]; then
	CREATE_MODELS=$PLUGIN_SINGULAR_NAME;  export CREATE_MODELS
fi
echo "作成するモデルを入力してください。"
echo "複数指定する場合は、半角スペースで区切ってください。[$CREATE_MODELS]"
echo -n "> "
read ANS
#if [ ! "$ANS" = "" ]; then
	CREATE_MODELS=${ANS}
#fi
#if [ "$CREATE_MODELS" = " " ]; then
#	CREATE_MODELS=""
#fi
echo "${CREATE_MODELS}"
echo ""


#プラグインパス
PUGLIN_PATH=${SET_DIR}/app/Plugin/${PLUGIN_NAME}

#作成者
echo "作成者を半角英語で入力してください。[$AUTHOR_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	AUTHOR_NAME=${ANS}
fi
if [ "$AUTHOR_NAME" = "" ]; then
	echo "引数が足りません"
	exit 0
fi
echo "${AUTHOR_NAME}"
echo ""

echo "作成者のメールアドレスを入力してください。[$AUTHOR_EMAIL]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	AUTHOR_EMAIL=${ANS}
fi
if [ "$AUTHOR_EMAIL" = "" ]; then
	echo "引数が足りません"
	exit 0
fi
echo "${AUTHOR_EMAIL}"
echo ""

echo "CREATE TABLEを実行してテーブルを作成していますか？ "
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
	echo ${ANS}
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
echo ""
