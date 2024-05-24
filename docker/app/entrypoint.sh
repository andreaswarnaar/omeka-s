#!/usr/bin/env bash
set -e
HOST='docker-host'
REMOTE_HOST=`/sbin/ip route|awk '/default/ { print $3 }'`
# Docker handles the hosts file someway. so it cannot be altered directly
# Us copy as a workaround
if grep -q $HOST "/etc/hosts";
then
echo 'Host exists in file'
else
echo "127.0.0.1   $HOST" >> /etc/hosts
fi
cp /etc/hosts /etc/hosts.update
sed -i "/$HOST/ s/.*/$REMOTE_HOST\t$HOST/g" /etc/hosts.update
cp -f /etc/hosts.update /etc/hosts

# Patch php symlink. There should be a better way. But this does the job for now
cp /20-xdebug-fpm.ini /etc/php/8.2/fpm/conf.d/20-xdebug.ini
rm /etc/php/8.2/cli/conf.d/20-xdebug.ini
cp /20-xdebug-cli.ini /etc/php/8.2/cli/conf.d/20-xdebug.ini

/usr/bin/supervisord
