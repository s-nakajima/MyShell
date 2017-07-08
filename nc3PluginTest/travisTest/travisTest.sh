#!/bin/bash -ex

if [ "$1" = "" ]; then
	echo "Please input package."
	exit 1
fi

COMMAND=". travisTestPre.sh"
echo ${COMMAND}
${COMMAND}


COMMAND="cd $TRAVIS_BUILD_DIR"
echo ${COMMAND}
${COMMAND}

if [ ! -d $NETCOMMONS_BUILD_DIR ]; then
	COMMAND="bash ${TRAVIS_SCRIPT_DIR}/before_script.sh"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="cd $NETCOMMONS_BUILD_DIR"
	echo ${COMMAND}
	${COMMAND}

	if [ "$PLUGIN_NAME" != "Install" ]; then
		#COMMAND="cp app/Config/database.php.$DB app/Config/database.php"
		COMMAND="cp ${CURDIR}/database.php app/Config/database.php"
		echo ${COMMAND}
		${COMMAND}
	fi
else
	COMMAND="cd $NETCOMMONS_BUILD_DIR"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="cp -rf $TRAVIS_BUILD_DIR app/Plugin/"
	echo ${COMMAND}
	${COMMAND}

	COMMAND=". tools/build/plugins/cakephp/travis/environment.sh"
	echo ${COMMAND}
	${COMMAND}
fi


COMMAND="bash ${TRAVIS_SCRIPT_DIR}/script.sh"
echo ${COMMAND}
${COMMAND}

#COMMAND="cd $NETCOMMONS_BUILD_DIR"
#echo ${COMMAND}
#${COMMAND}

#COMMAND="bash ${TRAVIS_SCRIPT_DIR}/after_script.sh"
#echo ${COMMAND}
#${COMMAND}

#
#-- end of file --