#!/bin/bash -ex
CURDIR=`pwd`
SCRIPT_DIR=$(cd $(dirname ${BASH_SOURCE:-$0}); pwd)

if [ "$TARGET_DIR" = "" ]; then
	#TARGET_DIR=/var/www/app; export TARGET_DIR
	TARGET_DIR=/var/www/NetCommons3/app; export TARGET_DIR
	#TARGET_DIR=/var/www/html/edumap2_dev; export TARGET_DIR
fi

if [ "$ROOTURL" = "" ]; then
	#ROOTURL=http://127.0.0.1:9094; export ROOTURL
	ROOTURL=http://nc3.local:9096/app export ROOTURL
	#ROOTURL=http://html.local:9097/edumap2_dev; export ROOTURL
fi

OWNER=`ls -ld $TARGET_DIR | awk '{ print $3 '}`
#OWNER=www-data
GROUP=`ls -ld $TARGET_DIR | awk '{ print $4 '}`
#GROUP=www-data

cd $TARGET_DIR
#cd /var/www/NetCommons3/
export PATH=/var/www/app/vendors/bin:${TARGET_DIR}/vendors/bin:$PATH

PLUGIN_NAME=$1
CHECKEXEC=$2
if [ "${PLUGIN_NAME}" = "" ]; then
	echo "please input plugin."
	exit 1
fi
CMDPARAM=$3

if [ "${PLUGIN_DIR}" = "" ]; then
	PLUGIN_DIR="Plugin"; export PLUGIN_DIR
fi

PHPUNIT_VERSION="`phpunit --version`"
check=`echo $PHPUNIT_VERSION | grep "^PHPUnit 3.7.38"`
if [ ! "$check" = "" ]; then
	PHPUNITCMD="$SCRIPT_DIR/bin/cake-unittest"
	PARSE_CAVERAGE="${SCRIPT_DIR}/parse_caverage_tree_3.php"
else
	PHPUNITCMD="$TARGET_DIR/app/Console/cake"
	PARSE_CAVERAGE="${SCRIPT_DIR}/parse_caverage_tree.php"
fi

RESULT_LOGFILE=$CURDIR/testResult.log
if [ "${EXEC_SOURCE}" = "" -o ! -f $RESULT_LOGFILE ]; then
	echo "" > ${RESULT_LOGFILE}
	echo "//////////////////////////////////" >> ${RESULT_LOGFILE}
	echo "// Test Results" >> ${RESULT_LOGFILE}
	echo "//   [${TARGET_DIR}]" >> ${RESULT_LOGFILE}
	echo "//////////////////////////////////" >> ${RESULT_LOGFILE}
	echo "" >> ${RESULT_LOGFILE}
	echo "+----------------------------+" >> ${RESULT_LOGFILE}
fi

if [ "${PLUGIN_NAME}" = "pear_install" ]; then
	#Javascript checker install
	if type "pip" > /dev/null 2>&1; then
		cmdpip=`which pip`
	fi
	if [ "$cmdpip" = "" ]; then
		if type "pip2" > /dev/null 2>&1; then
			cmdpip=`which pip2`
		fi
	fi
	if [ "$cmdpip" = "" ]; then
		if type "pip3" > /dev/null 2>&1; then
			cmdpip=`which pip3`
		fi
	fi

	checkIns=`which gjslint`
	if [ $? -eq 1 ]; then
		#echo "pip install http://closure-linter.googlecode.com/files/closure_linter-latest.tar.gz"
		#pip install http://closure-linter.googlecode.com/files/closure_linter-latest.tar.gz
		echo "$cmdpip install https://github.com/NetCommons3/NetCommons3/raw/master/tools/build/plugins/cakephp/travis/v2.3.19.tar.gz"
		$cmdpip install https://github.com/NetCommons3/NetCommons3/raw/master/tools/build/plugins/cakephp/travis/v2.3.19.tar.gz
	fi

	checkIns=`phpdoc --version`
	if [ $? -eq 1 ]; then
		execCommand="pear channel-discover pear.phpdoc.org"
		echo ${execCommand}
		${execCommand}

		execCommand="pear install phpdoc/phpDocumentor-2.7.0"
		echo ${execCommand}
		${execCommand}
	fi

	exit 0
fi

BINDIR=${TARGET_DIR}/vendors/bin

if [ "${PLUGIN_NAME}" = "All.Plugin" ]; then
	PLUGIN_NAME=`ls app/$PLUGIN_DIR`
else
	if [ "${CHECKEXEC}" = "remove" ]; then
		echo ""
		echo "##################################"
		echo "Line feed remove"
		echo "##################################"

		cd $TARGET_DIR/app/$PLUGIN_DIR/${PLUGIN_NAME}

		find . -name "*.php" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.ctp" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.js" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.json" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.css" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.yml" | xargs perl -i.bak -pe "s/\r\n/\n/"

		find . -name "*.bak" -type f -exec rm -f {} \;

		cd $TARGET_DIR
	fi
fi
if [ "${CHECKEXEC}" = "" ]; then
	CHECKEXEC=all
fi

for plugin in ${PLUGIN_NAME}
do
	case "${plugin}" in
		"empty" ) continue ;;
		"BoostCake" ) continue ;;
		"DebugKit" ) continue ;;
		"HtmlPurifier" ) continue ;;
		#"M17n" ) continue ;;
		"Migrations" ) continue ;;
		"MobileDetect" ) continue ;;
		"Sandbox" ) continue ;;
		#"Install" ) continue ;;
		"TinyMCE" ) continue ;;
		"Upload" ) continue ;;
		* )

		echo "  ${plugin}" >> ${RESULT_LOGFILE}


		echo ""
		echo ""
		echo "//////////////////////////////////"
		echo "// Checked ${plugin}"
		echo "//   [${TARGET_DIR}]"
		echo "//////////////////////////////////"
		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpcs" ]; then
			echo ""
			echo "##################################"
			echo "PHP CodeSniffer(phpcs)"
			echo "##################################"
			#if [ -f ${BINDIR}/phpcs ]; then
			#	CMD_PHPCS=${BINDIR}/phpcs
			#else
				CMD_PHPCS=`which phpcs`
			#fi
			if [ -d ${TARGET_DIR}/vendors/cakephp/cakephp-codesniffer/CakePHP ]; then
			    STANDARD_PHPCS="${TARGET_DIR}/vendors/cakephp/cakephp-codesniffer/CakePHP"
			else
			    STANDARD_PHPCS="/var/www/app/vendors/cakephp/cakephp-codesniffer/CakePHP"
			fi
			if [ -d "${TARGET_DIR}/tools/build/app/phpcs/NetCommons" ]; then
				STANDARD_PHPCS="${STANDARD_PHPCS},${TARGET_DIR}/tools/build/app/phpcs/NetCommons"
			fi
			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="${CMD_PHPCS} -p --extensions=php,ctp --standard=${STANDARD_PHPCS} app"
			else
				execCommand="${CMD_PHPCS} -p --extensions=php,ctp --standard=${STANDARD_PHPCS} app/$PLUGIN_DIR/${plugin}"
			fi

			echo ${execCommand}
			${execCommand}

			result=$?
			if [ $result -eq 0 ]; then
				echo "     phpcs   : Success" >> ${RESULT_LOGFILE}
			else
				echo "     phpcs   : Failure" >> ${RESULT_LOGFILE}
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpmd" ]; then
			echo ""
			echo "##################################"
			echo "PHP Mess Detector(phpmd)"
			echo "##################################"
			if [ -f /var/www/MyShell/vendor/bin/phpmd ]; then
				CMD_PHPMD=/var/www/MyShell/vendor/bin/phpmd
			elif [ -f ${BINDIR}/phpmd ]; then
				CMD_PHPMD=${BINDIR}/phpmd
			else
				CMD_PHPMD=`which phpmd`
			fi

			if [ "${plugin}" = "NetCommons3" ]; then
				if [ -f /etc/phpmd.xml ]; then
					execCommand="${CMD_PHPMD} app text /etc/phpmd.xml"
				else
					execCommand="${CMD_PHPMD} app text /etc/phpmd/rules.xml"
				fi
			else
				if [ -f /etc/phpmd.xml ]; then
					execCommand="${CMD_PHPMD} app/$PLUGIN_DIR/${plugin} text /etc/phpmd.xml"
				else
					execCommand="${CMD_PHPMD} app/$PLUGIN_DIR/${plugin} text /etc/phpmd/rules.xml"
				fi
			fi
			echo ${execCommand}
			${execCommand}

			result=$?
			if [ $result -eq 0 ]; then
				echo "     phpmd   : Success" >> ${RESULT_LOGFILE}
			else
				echo "     phpmd   : Failure" >> ${RESULT_LOGFILE}
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpcpd" ]; then
			echo ""
			echo "##################################"
			echo "PHP Copy/Paste Detector (phpcpd)"
			echo "##################################"
			if [ -f ${BINDIR}/phpcpd ]; then
				CMD_PHPCPD=${BINDIR}/phpcpd
			else
				CMD_PHPCPD=`which phpcpd`
			fi

			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="${CMD_PHPCPD} --exclude Test --exclude Config app"
			else
				execCommand="${CMD_PHPCPD} --exclude Test --exclude Config app/$PLUGIN_DIR/${plugin}"
			fi
			echo ${execCommand}
			${execCommand}

			result=$?
			if [ $result -eq 0 ]; then
				echo "    phpcpd   : Success" >> ${RESULT_LOGFILE}
			else
				echo "    phpcpd   : Failure" >> ${RESULT_LOGFILE}
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "gjslint" ]; then
			echo ""
			echo "##################################"
			echo "JavaScript Style Check(gjslint)"
			echo "##################################"
			if [ -f ${BINDIR}/gjslint ]; then
				CMD_GJSLINT=${BINDIR}/gjslint
			else
				CMD_GJSLINT=`which gjslint`
			fi

			if [ -d app/$PLUGIN_DIR/${plugin}/JavascriptTest/coverage ]; then
				execCommand="rm -Rf app/$PLUGIN_DIR/${plugin}/JavascriptTest/coverage"
				echo ${execCommand}
				${execCommand}
			fi

			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="${CMD_GJSLINT} --strict --max_line_length 100 -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer -r app"
			else
				#if [ "${plugin}" = "NetCommons" ]; then
				#	execCommand="${CMD_GJSLINT} --strict -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer -r app/Plugin/NetCommons/webroot/base"
				#else
					execCommand="${CMD_GJSLINT} --strict --max_line_length 100 -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer,webroot/js/langs -r app/$PLUGIN_DIR/${plugin}"
				#fi
			fi
			echo ${execCommand}
			${execCommand}

			result=$?
			if [ $result -eq 0 ]; then
				echo "    gjslint  : Success" >> ${RESULT_LOGFILE}
			else
				echo "    gjslint  : Failure" >> ${RESULT_LOGFILE}
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "jsunit" ]; then
			if [ -d app/$PLUGIN_DIR/${plugin}/JavascriptTest/ ]; then
				echo ""
				echo "##################################"
				echo "JavScript unit test Karma(jsunit)"
				echo "##################################"

				if [ -f app/$PLUGIN_DIR/${plugin}/JavascriptTest/my.karma.conf.js ]; then
					execCommand="/usr/lib/node_modules/karma/bin/karma start app/$PLUGIN_DIR/${plugin}/JavascriptTest/my.karma.conf.js --single-run --browsers PhantomJS"
				else
					execCommand="/usr/lib/node_modules/karma/bin/karma start app/$PLUGIN_DIR/${plugin}/JavascriptTest/travis.karma.conf.js --single-run --browsers PhantomJS"
				fi
				echo ${execCommand}
				${execCommand}

				#execCommand="rm -Rf app/$PLUGIN_DIR/${plugin}/JavascriptTest/coverage"
				#echo ${execCommand}
				#${execCommand}
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpdoc" ]; then
			echo ""
			echo "##################################"
			echo "PHP Documentor(phpdoc)"
			echo "##################################"
			if [ -f ${BINDIR}/phpdoc ]; then
				CMD_PHPDOC=${BINDIR}/phpdoc
			else
				CMD_PHPDOC=`which phpdoc`
			fi

			echo ""
			execCommand="rm -Rf app/webroot/phpdoc/${plugin}"
			echo ${execCommand}
			${execCommand}

			if [ "${plugin}" = "NetCommons3" ]; then
				PHPDOCPARAM="-d app -t app/webroot/phpdoc/${plugin} --force --ansi"
			else
				PHPDOCPARAM="-d app/$PLUGIN_DIR/${plugin} -i app/$PLUGIN_DIR/${plugin}/Vendors/,app/$PLUGIN_DIR/${plugin}/webroot/ -t app/webroot/phpdoc/${plugin} --force --ansi"
			fi

			execCommand="${CMD_PHPDOC} run ${PHPDOCPARAM}"
			echo ${execCommand}
			${execCommand} > $TARGET_DIR/app/tmp/logs/phpdoc.log

			phpdocerr=`grep -c "\[37;41m" $TARGET_DIR/app/tmp/logs/phpdoc.log`
			if [ $phpdocerr -ne 0 ]; then
				cat $TARGET_DIR/app/tmp/logs/phpdoc.log
				echo ""
				echo "$phpdocerr errors."
				echo ""

				echo "    phpdoc   : Failure" >> ${RESULT_LOGFILE}
			else
				echo ""
				echo "phpdoc no error."
				echo ""

				echo "    phpdoc   : Success" >> ${RESULT_LOGFILE}
			fi
		fi

		if [ "${CHECKEXEC}" = "test.mysql" ]; then
			CHECKEXEC=phpunit
		fi
		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
			echo ""
			echo "##################################"
			echo "PHP UnitTest (phpunit)"
			echo "##################################"

			execCommand="rm -Rf app/webroot/coverage/${plugin}"
			echo ${execCommand}
			${execCommand}

			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="sh app/Console/cake test AllTest --coverage-html app/webroot/coverage/${plugin} --stderr"
				echo ${execCommand}
				${execCommand}
			else
				if [ -f app/$PLUGIN_DIR/${plugin}/phpunit.xml.dist ]; then
					execCommand="cp -pf app/$PLUGIN_DIR/${plugin}/phpunit.xml.dist ./${plugin}-phpunit.xml.dist"
					echo ${execCommand}
					${execCommand}

					execOption="--coverage-html app/webroot/coverage/${plugin} --configuration ${plugin}-phpunit.xml.dist"
				else
					execOption="--coverage-html app/webroot/coverage/${plugin}"
				fi

				if [ "${CHECKEXEC}" = "phpunit" ]; then
					if [ "${CMDPARAM}" = "list" -o "${CMDPARAM}" = "list.caverageAll" ]; then
						execCommand="$SCRIPT_DIR/bin/cake-unittest test ${plugin} ${execOption}"
					elif [ ! "${CMDPARAM}" = "" -a ! "${CMDPARAM}" = "caverageAll" ]; then
						execCommand="$PHPUNITCMD test ${plugin} ${CMDPARAM} ${execOption}"
					else
						execCommand="$PHPUNITCMD test ${plugin} All${plugin} ${execOption}"
					fi
				else
					execCommand="$PHPUNITCMD test ${plugin} All${plugin} ${execOption}"
				fi
				echo ${execCommand}
				NC3_LOCAL_TEST=1 ${execCommand}

				result=$?
				if [ $result -eq 0 ]; then
					echo "    phpunit  : Success" >> ${RESULT_LOGFILE}
				else
					echo "    phpunit  : Failure" >> ${RESULT_LOGFILE}
				fi

				if [ -f app/$PLUGIN_DIR/${plugin}/phpunit.xml.dist ]; then
					execCommand="rm -f ./${plugin}-phpunit.xml.dist"
					echo ${execCommand}
					${execCommand}
				fi
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
			echo ""
			echo "##################################"
			echo "Coverage report"
			echo "##################################"
			if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
				echo ""
				echo "[MySQL coverage report]"
				#if [ -f app/$PLUGIN_DIR/${plugin}/phpunit.xml.dist ]; then
				#	echo "$ROOTURL/coverage/${plugin}/index.html"
				#else
				#	echo "$ROOTURL/coverage/${plugin}/Plugin.html"
				#fi
				#echo ""

				if [ "${CMDPARAM}" = "caverageAll" -o "${CMDPARAM}" = "list.caverageAll" ]; then
					echo "php ${PARSE_CAVERAGE} ${plugin} 1"
					php ${PARSE_CAVERAGE} ${plugin} 1
				else
					echo "php ${PARSE_CAVERAGE} ${plugin} 0"
					php ${PARSE_CAVERAGE} ${plugin} 0
					#echo "php ${CURDIR}/parse_caverage.php ${plugin} Plugin_${plugin}.html 0"
					#php ${CURDIR}/parse_caverage.php ${plugin} Plugin_${plugin}.html 0
				fi
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpdoc" ]; then
			echo ""
			echo "##################################"
			echo "PHP Documentor report"
			echo "##################################"
			echo ""
			if [ $phpdocerr -ne 0 ]; then
				echo "$ROOTURL/phpdoc/${plugin}/reports/errors.html"
			else
				echo "$ROOTURL/phpdoc/${plugin}/index.html"
			fi
			echo ""
		fi

		echo "+----------------------------+" >> ${RESULT_LOGFILE}
	esac
done

#echo "chown ${OWNER}:${GROUP} -R $TARGET_DIR"
#chown ${OWNER}:${GROUP} -R $TARGET_DIR

#echo "chown ${OWNER}:${GROUP} -R ${TARGET_DIR}/app/tmp"
chown ${OWNER}:${GROUP} -R ${TARGET_DIR}/app/tmp

#echo "chown ${OWNER}:${GROUP} -R ${TARGET_DIR}/app/webroot/files/"
chown ${OWNER}:${GROUP} -R ${TARGET_DIR}/app/webroot/files/

#
#-- end of file --
