#!/bin/bash
if [ "$UID" -eq 0 ];then
  echo "Doing..."
else
	echo "Use \"sudo\". Do you want to continue?"
	echo -n "y(es)/n(o) [n]> "
	read ANS
	if [ "$ANS" = "" ]; then
		ANS="n"
	fi
	if [ ! "$ANS" = "y" ]; then
		exit
	fi
fi

. ./nc3profile

CURDIR=`pwd`
#NODEVELOP=1; export NODEVELOP
#LOCALDEV=1; export LOCALDEV
if [ "$1" = "install" ]; then
	NORMALDEV=4; export NORMALDEV
elif [ "$1" = "develop" -o "$1" = "develop2" -o "$1" = "dev" ]; then
	NORMALDEV=0; export NORMALDEV
elif [ "$1" = "composer" -o "$1" = "comp" ]; then
	NORMALDEV=3; export NORMALDEV
else
	NORMALDEV=1; export NORMALDEV
fi
if [ "$NC3DIR" = "" ]; then
	echo "Chage directory /var/www/MyShell/install"
	exit
fi
#if [ ! "$CURDIR" = "/var/www/MyShell/install" ]; then
#	echo "Chage directory /var/www/MyShell/install"
#	exit
#fi

if [ "${NC3URI}" = "http://127.0.0.1:9090" ]; then
	if [ ! "`cat /etc/lsb-release`" = "" ]; then
		NC3URI=http://127.0.0.1:9090; export NC3URI
	elif [ ! "`cat /etc/redhat-release`" = "" ]; then
		NC3URI=http://127.0.0.1:9094; export NC3URI
	fi
fi

if [ "$2" = "docs" ]; then
	SKIPDOCS=0; export SKIPDOCS
else
	SKIPDOCS=1; export SKIPDOCS
fi

CMDCMPOSER="`which composer`"
if [ "${CMDCMPOSER}" = "" ]; then
	CMDCMPOSER="php composer.phar"
fi

ZIPCMD="`which zip`"
if [ "${ZIPCMD}" = "" ]; then
	COMMAND="apt-get -y install zip"
	echo ${COMMAND}
	${COMMAND}
fi


ymdhis=`date +%y%m%d%H%M%S`
BKFILE=all-${ymdhis}
BKDIR=/var/www/backup/${BKFILE}

OWNER=`ls -ld /var/www/app | awk '{ print $3 '}`
GROUP=`ls -ld /var/www/app | awk '{ print $4 '}`

if [ "${NORMALDEV}" = "2" -o "${NORMALDEV}" = "0" ]; then
	echo "ffmpeg -version"
	ret="`ffmpeg -version`"

	COMMAND="apt-get -y install libav-tools"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="apt-get -y install libavcodec-extra-53"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="ffmpeg -version"
	echo ${COMMAND}
	${COMMAND}
fi

################
# バックアップ #
################

if [ ! -d /var/www/backup ]; then
	echo "mkdir /var/www/backup"
	mkdir /var/www/backup
fi
if [ ! -d /vagrant/backup ]; then
	echo "mkdir /vagrant/backup"
	mkdir /vagrant/backup
fi

echo "mkdir ${BKDIR}"
mkdir ${BKDIR}

echo "cd ${BKDIR}"
cd ${BKDIR}

echo "mysqldump -u${DBUSER} -p${DBPASS} ${DBNAME} > ${DBNAME}.sql"
mysqldump -u${DBUSER} -p${DBPASS} ${DBNAME} > ${DBNAME}.sql

echo "mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'"
mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'

echo "mysql -u${DBUSER} -p${DBPASS} -e \"drop database ${DBNAME};\""
mysql -u${DBUSER} -p${DBPASS} -e "drop database ${DBNAME};"

echo "mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'"
mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'

if [ ! "${NORMALDEV}" = "4" ]; then
	echo "cp -Rpf ${NC3DIR} ./"
	cp -Rpf ${NC3DIR} ./

	for file in `ls ${NC3DIR}/`
	do
		case "${file}" in
			"nbproject" ) continue ;;
			"cache" ) continue ;;
			"backup" ) continue ;;
			"logs" ) continue ;;
			"Vagrantfile" ) continue ;;
			"Vagrantfile.win" ) continue ;;
			"Vagrantfile.mac" ) continue ;;
			"vagrant-halt.bat" ) continue ;;
			"vagrant-halt.command" ) continue ;;
			"vagrant-install.bat" ) continue ;;
			"vagrant-install.command" ) continue ;;
			"vagrant-provision.bat" ) continue ;;
			"vagrant-provision.command" ) continue ;;
			"vagrant-up.bat" ) continue ;;
			"vagrant-up.command" ) continue ;;
			* )

			echo "rm -Rf ${NC3DIR}/${file}"
			rm -Rf ${NC3DIR}/${file}
		esac
	done


	echo "rm -Rf ${NC3DIR}/.git"
	rm -Rf ${NC3DIR}/.git

	if [ ! "${SKIPDOCS}" = "1" ]; then
		if [ -d /var/www/docs ]; then
			echo "mv /var/www/docs ./"
			mv /var/www/docs ./
		fi
		if [ -d /var/www/NetCommons3Docs ]; then
			echo "mv /var/www/NetCommons3Docs ./"
			mv /var/www/NetCommons3Docs ./
		fi
	fi

	echo "cd /var/www/backup/"
	cd /var/www/backup/

	echo "tar czf ${BKFILE}.tar.gz ${BKFILE}"
	tar czf ${BKFILE}.tar.gz ${BKFILE}

	echo "mv ${BKFILE}.tar.gz /vagrant/backup/"
	mv ${BKFILE}.tar.gz /vagrant/backup/
fi

############
# 環境構築 #
############

if [ ! "${NORMALDEV}" = "4" ]; then
	echo "rm -Rf ${CURDIR}/NetCommons3"
	rm -Rf ${CURDIR}/NetCommons3

	#if [ -d ${BKDIR}/app/nbproject ]; then
	#	echo "cp -Rpf ${BKDIR}/app/nbproject ${NC3DIR}/"
	#	cp -Rpf ${BKDIR}/app/nbproject ${NC3DIR}/
	#fi
	#if [ -d ${BKDIR}/app/cache ]; then
	#	echo "cp -Rpf ${BKDIR}/app/cache ${NC3DIR}/"
	#	cp -Rpf ${BKDIR}/app/cache ${NC3DIR}/
	#fi

	echo "cd ${CURDIR}"
	cd ${CURDIR}

	#if [ "${NORMALDEV}" = "2" -o "${NORMALDEV}" = "0" ]; then
	#	echo "git clone https://github.com/s-nakajima/NetCommons3.git"
	#	git clone https://github.com/s-nakajima/NetCommons3.git
	#else
		echo "git clone ${GITURL}/NetCommons3.git"
		git clone ${GITURL}/NetCommons3.git
	#fi

	if [ "${NORMALDEV}" = "2" -o "${NORMALDEV}" = "0" ]; then
		echo "rm -Rf ${CURDIR}/Themed"
		rm -Rf ${CURDIR}/Themed

		echo "git clone https://github.com/s-nakajima/Themed.git"
		git clone https://github.com/s-nakajima/Themed.git
	fi

	echo "cd /var/www/"
	cd /var/www/

	if [ ! "${SKIPDOCS}" = "1" ]; then
		echo "git clone ${GITURL}/NetCommons3Docs.git docs"
		git clone ${GITURL}/NetCommons3Docs.git docs
	fi

	echo "cp -apf ${CURDIR}/NetCommons3/. ${NC3DIR}/"
	cp -apf ${CURDIR}/NetCommons3/. ${NC3DIR}/

	if [ -f ${BKDIR}/app/Vagrantfile ]; then
		echo "cp -pf ${BKDIR}/app/Vagrantfile app/"
		cp -pf ${BKDIR}/app/Vagrantfile app/
	fi

	if [ ! "${SKIPDOCS}" = "1" ]; then
		echo "mv NetCommons3Docs docs"
		mv NetCommons3Docs docs
	fi

	if [ "${NORMALDEV}" = "2" -o "${NORMALDEV}" = "0" ]; then
		if [ "$1" = "develop2" ]; then
			echo "cp -Rf ${CURDIR}/Themed/* app/app/View/Themed/"
			cp -Rf ${CURDIR}/Themed/* app/app/View/Themed/
		else
			for theme in `ls ${CURDIR}/Themed/`
			do
				if [ ! "${theme}" = "README.md" ]; then
					echo "ln -s ${CURDIR}/Themed/${theme} app/app/View/Themed/${theme}"
					ln -s ${CURDIR}/Themed/${theme} app/app/View/Themed/${theme}
				fi
			done
		fi
	fi

	echo "chown ${OWNER}:${GROUP} -R app"
	chown ${OWNER}:${GROUP} -R app

	echo "cd ${NC3DIR}/"
	cd ${NC3DIR}/

	echo "git config --global url.'https://'.insteadOf git://"
	git config --global url."https://".insteadOf git://

	if [ -f ${BKDIR}/app/composer.phar ]; then
		echo "cp ${BKDIR}/app/composer.phar ${NC3DIR}/"
		cp ${BKDIR}/app/composer.phar ${NC3DIR}/

		echo "${CMDCMPOSER} self-update"
		${CMDCMPOSER} self-update
	else
		echo "curl -s http://getcomposer.org/installer | php"
		curl -s http://getcomposer.org/installer | php
	fi

	echo "rm -f composer.lock"
	rm -f composer.lock

	echo "cp -f bower.json.dist bower.json"
	cp -f bower.json.dist bower.json

	echo "${CMDCMPOSER} self-update"
	${CMDCMPOSER} self-update

	echo "${CMDCMPOSER} config minimum-stability dev"
	${CMDCMPOSER} config minimum-stability dev

	if [ "${NORMALDEV}" = "2" -o "${NORMALDEV}" = "0" ]; then
		echo "${CMDCMPOSER} config prefer-stable true"
		${CMDCMPOSER} config prefer-stable true
	fi

	#if [ ! "`which hhvm`" = "" ]; then
	#	echo "hhvm -vRepo.Central.Path=/var/run/hhvm/hhvm.hhbc ${CMDCMPOSER} install"
	#	hhvm -vRepo.Central.Path=/var/run/hhvm/hhvm.hhbc ${CMDCMPOSER} install
	#else
		echo "${CMDCMPOSER} install"
		${CMDCMPOSER} install
	#fi

	#Githubから最新取得
	if [ -f ${CURDIR}/.nc3plugins ]; then
		COMMAND="rm -f ${CURDIR}/.nc3plugins"
		echo ${COMMAND}
		${COMMAND}
	fi

	GITURL=https://github.com/NetCommons3; export GITURL

	COMMAND="cd ${CURDIR}"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="`which curl` -O https://raw.githubusercontent.com/s-nakajima/MyShell/master/.nc3plugins"
	echo ${COMMAND}
	${COMMAND}

	. ${CURDIR}/.nc3plugins

	echo "cd ${NC3DIR}/"
	cd ${NC3DIR}/

	#echo "bower --allow-root update"
	#bower --allow-root update
fi

######################
# Githubから最新取得 #
######################
if [ ! "${NORMALDEV}" = "4" ]; then
	for sPlugin in "${NC3PLUGINS[@]}"
	do
		if [ "${NORMALDEV}" = "1" ]; then
			continue
		fi

		aPlugin=(${sPlugin})
		echo "==== ${aPlugin[0]} ===="

		COMMAND="cd ${NC3DIR}/app/Plugin"
		echo ${COMMAND}
		${COMMAND}

		COMMAND="rm -Rf ${aPlugin[0]}"
		echo ${COMMAND}
		${COMMAND}

		if [ "${aPlugin[1]}" = "DELETE" ]; then
			continue
		fi

		case "${aPlugin[0]}" in
			"empty" ) continue ;;
			"BoostCake" ) continue ;;
			"DebugKit" ) continue ;;
			"HtmlPurifier" ) continue ;;
			#"M17n" ) continue ;;
			"Migrations" ) continue ;;
			"MobileDetect" ) continue ;;
			"Sandbox" ) continue ;;
			"TinyMCE" ) continue ;;
			* )
			#NetCommons3プロジェクトから最新取得
			if [ "${aPlugin[2]}" = "" ]; then
				COMMAND="`which git` clone ${aPlugin[1]}/${aPlugin[0]}.git"
			else
				COMMAND="`which git` clone -b ${aPlugin[2]} ${aPlugin[1]}/${aPlugin[0]}.git"
			fi
		esac

		echo ${COMMAND}
		${COMMAND}
	done
fi

################
# インストール #
################
COMMAND="rm ${NC3DIR}/app/Config/application.yml"
echo ${COMMAND}
${COMMAND}

if [ ! "${NORMALDEV}" = "4" ]; then
	COMMAND="rm -Rf ${NC3DIR}/app/Plugin/Install"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="cd ${NC3DIR}/app/Plugin/"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="git clone ${GITURL}/Install.git"
	echo ${COMMAND}
	${COMMAND}
fi

INSTALL_CMD="${NC3DIR}/app/Console/cake install.install "

COMMAND="${INSTALL_CMD}install_start --base-url=${NC3URI} --lang="
echo ${COMMAND}
${COMMAND}

COMMAND="${INSTALL_CMD}create_database --datasource= --host= --port= --database=${DBNAME} --prefix= --login=root --password=root"
echo ${COMMAND}
${COMMAND}

COMMAND="${INSTALL_CMD}install_migrations"
echo ${COMMAND}
${COMMAND}

#COMMAND="${INSTALL_CMD}install_bower"
#echo ${COMMAND}
#${COMMAND}

COMMAND="${INSTALL_CMD}save_administrator --username=admin --password=admin"
echo ${COMMAND}
${COMMAND}

COMMAND="${INSTALL_CMD}install_finish"
echo ${COMMAND}
${COMMAND}

echo "cd ${NC3DIR}/"
cd ${NC3DIR}/

exit

#-- end --
