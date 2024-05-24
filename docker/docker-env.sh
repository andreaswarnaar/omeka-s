#!/usr/bin/env bash

export PROJECT_NAME="omekas"

export HOST_IP_ADDRESS=${HOST_IP_ADDRESS:-$(hostname -I | cut -f1 -d ' ')}
export COMPOSE_PROJECT_NAME="${PROJECT_NAME}"
if [ ! -f ./docker/app/.bash_history ]; then
    touch ./docker/app/.bash_history
    echo -e "New empty bash_history file created"
fi

service_status() {
    local service_name="$1" # The first argument to the function is the service name

    # Checking if the service is running
    if docker compose ps | grep "$service_name" | grep -q "Up"; then
        return 0
    else
        return 1
    fi
}

COMPOSE="docker compose -f docker/docker-compose.yml"

if [ $# -gt 0 ]; then
    if [ "$1" == "start" ]; then
        shift 1
        $COMPOSE up -d
        $COMPOSE exec app bash
    elif [ "$1" == "exec" ]; then
        shift 1
        if service_status "app"; then
           $COMPOSE up -d
        fi

        $COMPOSE exec --user omekas app "$@"
    else
        $COMPOSE "$@"
    fi
else
    $COMPOSE ps
fi
