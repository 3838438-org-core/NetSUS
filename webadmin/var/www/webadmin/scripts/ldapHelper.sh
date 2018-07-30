#!/bin/bash

case $1 in

getldapproxystatus)
SERVICE=slapd
if ps acx | grep -v grep | grep -q $SERVICE ; then
	echo "true"
else
	echo "false"
fi
;;

disableproxy)
if [ "$(which update-rc.d 2>&-)" != '' ]; then
	update-rc.d slapd disable > /dev/null 2>&1
elif [ "$(which chkconfig 2>&-)" != '' ]; then
	chkconfig slapd off > /dev/null 2>&1
fi
service slapd stop 2>&-
;;

enableproxy)
if [ "$(which update-rc.d 2>&-)" != '' ]; then
	update-rc.d slapd enable > /dev/null 2>&1
elif [ "$(which chkconfig 2>&-)" != '' ]; then
	chkconfig slapd on > /dev/null 2>&1
fi
service slapd start 2>&-
;;

touchconf)
conf="$2"
if [ "$(getent passwd www-data)" != '' ]; then
	www_user=www-data
elif [ "$(getent passwd apache)" != '' ]; then
	www_user=apache
fi
touch "$conf"
chown $www_user "$conf"
chmod u+w "$conf"
;;

installslapdconf)
if [ -d "/etc/ldap" ]; then
	mv /etc/ldap/slapd.conf /etc/ldap/slapd.conf.bak
	mv /var/appliance/conf/slapd.conf.new /etc/ldap/slapd.conf
fi
if [ -d "/etc/openldap" ]; then
	mv /etc/openldap/slapd.conf /etc/openldap/slapd.conf.bak
	mv /var/appliance/conf/slapd.conf.new /etc/openldap/slapd.conf
fi
;;

esac