#!/bin/bash -ex

export CURDIR=`pwd`
export TRAVIS_DIR=$CURDIR/travis
export TRAVIS_SCRIPT_DIR=$TRAVIS_DIR/script
export VENDORS_DIR=$CURDIR/vendors
export NETCOMMONS_VERSION=master

#export PACKAGE_DIR=NetCommons3/$1
export PACKAGE_DIR=$1
export TRAVIS_BUILD_DIR=$TRAVIS_DIR/$PACKAGE_DIR
export PLUGIN_NAME=`basename $TRAVIS_BUILD_DIR`
export NETCOMMONS_BUILD_DIR=`dirname $TRAVIS_BUILD_DIR`/NetCommons3
#if [ "$2" = "" ]; then
	export DB=mysql
#else
#	export DB=$2
#fi
#if [ "$3" = "" ]; then
if [ "$2" = "github" ]; then
	export GET_GITHUB=1
else
	export GET_GITHUB=0
fi

if [ ! -d ${VENDORS_DIR} ]; then
	COMMAND="mkdir ${VENDORS_DIR}"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="cd ${VENDORS_DIR}"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="git clone https://github.com/mustangostang/spyc.git"
	echo ${COMMAND}
	${COMMAND}
fi

if [ ! -d $TRAVIS_DIR ]; then
	COMMAND="mkdir $TRAVIS_DIR"
	echo ${COMMAND}
	${COMMAND}
fi

if [ -d $TRAVIS_BUILD_DIR ]; then
	if [ "$GET_GITHUB" = "1" ]; then
		COMMAND="rm -Rf $TRAVIS_BUILD_DIR"
		echo ${COMMAND}
		${COMMAND}
	fi
else
	COMMAND="rm -Rf $TRAVIS_DIR/*"
	echo ${COMMAND}
	${COMMAND}

	export GET_GITHUB=1
fi

COMMAND="mkdir ${TRAVIS_SCRIPT_DIR}"
echo ${COMMAND}
${COMMAND}

if [ ! -d ${VENDORS_DIR} ]; then
	COMMAND="mkdir ${VENDORS_DIR}"
	echo ${COMMAND}
	${COMMAND}
fi

if [ ! -d ${VENDORS_DIR}/spyc ]; then
	COMMAND="cd ${VENDORS_DIR}"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="git clone https://github.com/mustangostang/spyc.git"
	echo ${COMMAND}
	${COMMAND}
fi

COMMAND="cd $TRAVIS_DIR"
echo ${COMMAND}
${COMMAND}

if [ "$GET_GITHUB" = "1" ]; then
	COMMAND="git clone --depth=50 --branch=master git://github.com/${PACKAGE_DIR}.git ${PACKAGE_DIR}"
	echo ${COMMAND}
	${COMMAND}
fi


if [ ! -f ${TRAVIS_BUILD_DIR}/composer.json ]; then
	echo "Not found composer.json"
	exit 1
fi

if [ ! -f ${TRAVIS_BUILD_DIR}/.travis.yml ]; then
	echo "Not found .travis.yml"
	exit 1
fi

COMMAND="cd $TRAVIS_SCRIPT_DIR"
echo ${COMMAND}
${COMMAND}

SCRIPTS="before_script script after_script"
for shellScript in $SCRIPTS
do

php -q << _EOF_ > "${shellScript}.sh"
<?php
error_reporting(E_ALL);
require '$VENDORS_DIR/spyc/Spyc.php';
\$travis = Spyc::YAMLLoad('$TRAVIS_BUILD_DIR/.travis.yml');
\$ret = 'cd $NETCOMMONS_BUILD_DIR' . chr(10);
foreach (\$travis['$shellScript'] as \$script) {
	\$script = str_replace(['travis_wait '], [''], \$script);
	\$ret .= \$script . chr(10);
}
echo \$ret;
_EOF_

	COMMAND="chmod a+x ${shellScript}.sh"
	echo ${COMMAND}
	${COMMAND}

done

COMMAND="ls -la"
echo ${COMMAND}
${COMMAND}


#
#-- end of file --
