#!/bin/bash
if [ "$UID" -eq 0 ];then
  echo "Doing..."
else
  echo "Use \"sudo\""
  #exit
fi

. /var/www/MyShell/install/nc3profile

DBNAME=nc3_ins; export DBNAME
DBPREFIX=nc333333_; export DBPREFIX
NC3DIR=/var/www/html/nc3; export NC3DIR

if [ ! "`cat /etc/lsb-release`" = "" ]; then
	NC3URI=html.local:9090; export NC3URI
elif [ ! "`cat /etc/redhat-release`" = "" ]; then
	NC3URI=html.local:9096; export NC3URI
else
	NC3URI=html.local:9090; export NC3URI
fi
NODEV=" --no-dev"

CURDIR=`pwd`
#NODEVELOP=1; export NODEVELOP
#LOCALDEV=1; export LOCALDEV
if [ "$1" = "install" ]; then
	NORMALDEV=4; export NORMALDEV
elif [ "$1" = "develop" -o "$1" = "develop2" -o "$1" = "dev" ]; then
	NORMALDEV=0; export NORMALDEV
else
	NORMALDEV=1; export NORMALDEV
fi
if [ "$NC3DIR" = "" ]; then
	echo "Chage directory /var/www/MyShell/install"
	exit
fi

CMDCMPOSER="`which composer`"
if [ "${CMDCMPOSER}" = "" ]; then
	CMDCMPOSER="php composer.phar"
fi


ymdhis=`date +%y%m%d%H%M%S`
BKFILE=all-${ymdhis}
BKDIR=/var/www/backup/${BKFILE}

OWNER=`ls -ld $TARGET_DIR | awk '{ print $3 '}`
if [ ! "${OWNER}" = "vagrant" ]; then
	OWNER=www-data
fi
GROUP=`ls -ld $TARGET_DIR | awk '{ print $4 '}`
if [ ! "${GROUP}" = "vagrant" ]; then
	GROUP=www-data
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

echo "mysql -u${DBUSER} -p${DBPASS} -e \"drop database ${DBNAME}_test;\""
mysql -u${DBUSER} -p${DBPASS} -e "drop database ${DBNAME}_test;"

echo "mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'"
mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'

if [ ! "${NORMALDEV}" = "4" ]; then
	echo "cp -Rpf ${NC3DIR} ./"
	cp -Rpf ${NC3DIR} ./

	echo "rm -Rf ${NC3DIR}"
	rm -Rf ${NC3DIR}

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
	echo "git clone ${GITURL}/NetCommons3.git ${NC3DIR}"
	git clone ${GITURL}/NetCommons3.git ${NC3DIR}

	echo "cd ${NC3DIR}"
	cd ${NC3DIR}

	if [ -d ${BKDIR}/app/nbproject ]; then
		echo "mv ${BKDIR}/app/nbproject ${NC3DIR}/"
		mv ${BKDIR}/app/nbproject ${NC3DIR}/
	fi
	
	echo "chown ${OWNER}:${GROUP} -R app/tmp"
	chown ${OWNER}:${GROUP} -R app/tmp

	echo "chown ${OWNER}:${GROUP} -R app/Config"
	chown ${OWNER}:${GROUP} -R app/Config

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

	echo "${CMDCMPOSER} config prefer-stable true"
	${CMDCMPOSER} config prefer-stable true

	echo "${CMDCMPOSER} install${NODEV}"
	${CMDCMPOSER} install${NODEV}

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

INSTALL_CMD="${NC3DIR}/app/Console/cake install.install "

COMMAND="${INSTALL_CMD}install_start --base-url=http://${NC3URI} --lang="
echo ${COMMAND}
${COMMAND}

COMMAND="${INSTALL_CMD}create_database --datasource= --host= --port= --database=${DBNAME} --prefix=${DBPREFIX} --login=root --password=root"
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

echo "cd ${NC3DIR}/app/Config"
cd ${NC3DIR}/app/Config	
	 
MATCHES="debug: 0"
REPLACE="debug: 2"

echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" application.yml > application.yml2"
sed -e "s/${MATCHES}$/${REPLACE}/g" application.yml > application.yml2

echo "mv application.yml application.yml.org"
mv application.yml application.yml.org

echo "mv application.yml2 application.yml"
mv application.yml2 application.yml

echo "cd ${NC3DIR}/"
cd ${NC3DIR}/

#########################
# 初期データ作成 #
#########################

#echo "php ${CURDIR}/insert_user.php ${DBNAME} ${DBPREFIX}"
#php ${CURDIR}/insert_user.php ${DBNAME} ${DBPREFIX}

echo "${NC3DIR}/app/Console/cake users.users user_import ${CURDIR}/import_file.csv"
${NC3DIR}/app/Console/cake users.users user_import ${CURDIR}/import_file.csv

#echo "php ${CURDIR}/insert_user_post.php ${DBNAME} ${DBPREFIX}"
#php ${CURDIR}/insert_user_post.php ${DBNAME} ${DBPREFIX}

#echo "${NC3DIR}/app/Console/cake Migrations.migration run down -c master -i master -p Boxes"
#${NC3DIR}/app/Console/cake Migrations.migration run down -c master -i master -p Boxes

#echo "${NC3DIR}/app/Console/cake Migrations.migration run all -c master -i master -p Boxes"
#${NC3DIR}/app/Console/cake Migrations.migration run all -c master -i master -p Boxes

if [ "${OWNER}" = "vagrant" ]; then
	echo "chown ${OWNER}:${GROUP} -R ${NC3DIR}"
	chown ${OWNER}:${GROUP} -R ${NC3DIR}
fi

echo "chown ${OWNER}:${GROUP} -R ${NC3DIR}/app/tmp"
chown ${OWNER}:${GROUP} -R ${NC3DIR}/app/tmp

echo "chown ${OWNER}:${GROUP} -R ${NC3DIR}/app/webroot/files"
chown ${OWNER}:${GROUP} -R ${NC3DIR}/app/webroot/files

plugins="`ls ${NC3DIR}/app/Plugin`"
echo "`which php` ${CURDIR}/create_plugin_data.php"
for plugin in ${plugins}
do
	`which php` ${CURDIR}/create_plugin_data.php ${plugin} $DBNAME $DBPREFIX
done

COMMAND="cd ${NC3DIR}/vendors"
echo ${COMMAND}
${COMMAND}

COMMAND="ln -s /var/www/app/vendors/phpunit"
echo ${COMMAND}
${COMMAND}

exit

#-- end --
