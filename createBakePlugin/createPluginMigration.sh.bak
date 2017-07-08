#!/bin/bash

##
# configファイルを作成
##
#if [ ! -f ${CONFIG_FILE} ]; then
#	exit 0
#fi

##
# Migrationファイルを作成
##
echo "cd ${SET_DIR}/app/Console/"
cd ${SET_DIR}/app/Console/

#CAKE_MIGRATION="Migrations.migration run -f -c master -p ${PLUGIN_NAME}"
#echo "./cake ${CAKE_MIGRATION}"
#./cake ${CAKE_MIGRATION}

CAKE_MIGRATION="Migrations.migration generate -f -c master -p ${PLUGIN_NAME}"
echo "./cake ${CAKE_MIGRATION}"
./cake ${CAKE_MIGRATION}

#PATTERN="class .\+Schema extends CakeSchema {"
#REPLACE="class AppSchema extends CakeSchema {"
#schemaFile=${PUGLIN_PATH}/Config/Schema/schema.php

#echo "sed ${schemaFile}"
#sed -i -e "s/${PATTERN}/${REPLACE}/g" ${schemaFile}

echo "Schemaファイルを修正してください。(${schemaFile})"
echo -n "y(es)/q(uit)/ [y]> "
read ANS
if [ "$ANS" = "q" ]; then
	exit 0
fi
#loop=0
#while [ $loop -ne 60 ]
#do
#	echo "Schemaファイルを修正してください。(${schemaFile})"
#	grepCount=`grep -c "\\\$users" ${schemaFile}`
#	echo "if [ $grepCount -eq 0 ]"
#	if [ $grepCount -eq 0 ]; then
#		echo "修正されました。"
#		break
#	fi
#	echo "修正しましたか。"
#	echo -n "y(es)/q(uit)/ [y]> "
#	read ANS
#	if [ "$ANS" = "q" ]; then
#		break
#	fi
#	loop=`expr $loop + 1`
#done

echo "Migrationファイルを修正してください。(${migrationFile})"
echo -n "y(es)/q(uit)/ [y]> "
read ANS
if [ "$ANS" = "q" ]; then
	exit 0
fi
#loop=0
#for file in `ls ${PUGLIN_PATH}/Config/Migration/`; do
#	migrationFile=${PUGLIN_PATH}/Config/Migration/${file}
#	while [ $loop -ne 60 ]
#	do
#		grepCount=`grep -c "'users'" ${migrationFile}`
#
#		echo "Migrationファイルを修正してください。(${migrationFile})"
#		echo "if [ $grepCount -eq 0 ]"
#		if [ $grepCount -eq 0 ]; then
#			echo "修正されました。"
#			break 2
#		fi
#		echo "修正しましたか。"
#		echo -n "y(es)/q(uit)/ [y]> "
#		read ANS
#		if [ "$ANS" = "q" ]; then
#			break 2
#		fi
#		loop=`expr $loop + 1`
#	done
#done
