#!/bin/bash -ex
CURDIR=`pwd`
EXEC_SOURCE=${BASH_SOURCE}; export EXEC_SOURCE

RESULT_LOGFILE=$CURDIR/testResult.log
echo "" > ${RESULT_LOGFILE}
echo "//////////////////////////////////" >> ${RESULT_LOGFILE}
echo "// All Test Results" >> ${RESULT_LOGFILE}
echo "//////////////////////////////////" >> ${RESULT_LOGFILE}
echo "" >> ${RESULT_LOGFILE}
echo "+----------------------------+" >> ${RESULT_LOGFILE}


NOW=$(date +%y%m%d%H%M%S)
LOGFILE=${CURDIR}/AllPluginTest.log

echo "" > ${LOGFILE}
date 1>> ${LOGFILE} 2>&1

#cd ${CURDIR}/../install/
#
#COMMAND="bash install_dirpath.sh develop"
#echo ${COMMAND}
#bash install_dirpath.sh develop 1>> ${LOGFILE} 2>&1

date 1>> ${LOGFILE} 2>&1

cd ${CURDIR}

#if [ -f nc3profile ]; then
#. nc3profile
#fi


#TARGET_DIR=/var/www/app
#TARGET_DIR=/var/www/html/nc3
TARGET_DIR=/var/www/html/NetCommons3

#MAIL_LOG_CMD="scp -i /root/.ssh/id_rsa ${LOGFILE} ${SAKURA_USER}@${SAKURA_HOST}:~/log/nc3-mail.log"
#MAIL_LOG_CMD="scp -i /home/vagrant/.ssh/id_rsa ${LOGFILE} ${SAKURA_USER}@${SAKURA_HOST}:~/log/nc3-mail.log"
#
#SEND_MAIL_CMD="ssh -i /root/.ssh/id_rsa ${SAKURA_USER}@${SAKURA_HOST} ${SAKURA_SHELL}/sakura-mail.sh"
#SEND_MAIL_CMD="ssh -i /home/vagrant/.ssh/id_rsa ${SAKURA_USER}@${SAKURA_HOST} ${SAKURA_SHELL}/sakura-mail.sh"
#
#if [ -f nc3profile ]; then
#	echo ${MAIL_LOG_CMD}
#	${MAIL_LOG_CMD}
#
#	COMMAND="${SEND_MAIL_CMD} 'PUGIN TEST $NOW \"install_dirpath.sh\"'"
#	echo "$COMMAND"
#	${COMMAND}
#fi


PLUGIN_NAME=`ls $TARGET_DIR/app/Plugin`
count=0
testPlugin=""
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
		
		count=$(( $count + 1 ))
		mod=$(( $count % 5 ))

		if [ "$testPlugin" = "" ]; then
			testPlugin="$plugin"
		else
			testPlugin="$testPlugin $plugin"
		fi

		if [ "$mod" = "0" -o "$plugin" = "Questionnaires" -o "$plugin" = "Quizzes" -o "$plugin" = "Registrations" ]; then
			echo "" > ${LOGFILE}
			date 1>> ${LOGFILE} 2>&1
			
			COMMAND="bash ${CURDIR}/nc3PluginTest.sh '$testPlugin'"
			echo ${COMMAND}
			bash ${CURDIR}/nc3PluginTest.sh "$testPlugin" 1>> ${LOGFILE} 2>&1

			date 1>> ${LOGFILE} 2>&1

#			if [ -f nc3profile ]; then
#				echo ${MAIL_LOG_CMD}
#				${MAIL_LOG_CMD}
#
#				COMMAND="${SEND_MAIL_CMD} 'PUGIN TEST $NOW \"$testPlugin\"'"
#				echo "$COMMAND"
#				${COMMAND}
#			fi

			testPlugin=""
			count=0
		fi
	esac
done

if [ ! "$testPlugin" = "" ]; then
	echo "" > ${LOGFILE}
	date 1>> ${LOGFILE} 2>&1

	COMMAND="bash ${CURDIR}/nc3PluginTest.sh $testPlugin"
	echo ${COMMAND}
	bash ${CURDIR}/nc3PluginTest.sh "$testPlugin" 1>> ${LOGFILE} 2>&1

	date 1>> ${LOGFILE} 2>&1
fi

#if [ -f nc3profile ]; then
#	echo ${MAIL_LOG_CMD}
#	${MAIL_LOG_CMD}
#
#	COMMAND="${SEND_MAIL_CMD} 'PUGIN TEST $NOW \"$testPlugin\"'"
#	echo "$COMMAND"
#	${COMMAND}
#
#	cp -pf $RESULT_LOGFILE $LOGFILE
#	echo ${MAIL_LOG_CMD}
#	${MAIL_LOG_CMD}
#
#	COMMAND="${SEND_MAIL_CMD} 'PUGIN TEST $NOW \"Summary results\"'"
#	echo "$COMMAND"
#	${COMMAND}
#fi

#
#-- end of file --
