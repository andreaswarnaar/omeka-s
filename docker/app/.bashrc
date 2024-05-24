export serverName=omekas.local
export PHP_IDE_CONFIG=serverName="omekas.local"
export COLUMNS=400
PS1=$'\[\033[01;36m\]\w\[\033[00m\]\n${debian_chroot:+($debian_chroot)}\[\033[01;36m\]$NAME \D{%T}\xe2\x9e\xa4 \[\033[00m\]'

alias composer='php -d memory_limit=-1 -d xdebug.remote_enable=0 /usr/local/bin/composer'

HISTCONTROL=ignoreboth
shopt -s histappend
PROMPT_COMMAND="history -a;$PROMPT_COMMAND"
HISTSIZE=4000
HISTFILESIZE=8000
HISTTIMEFORMAT="%d/%m/%y %T "

shopt -s checkwinsize
