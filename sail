#!/usr/bin/env sh
BASE_DIR="$(cd "$(dirname "$0")"; pwd)";
SAIL_EXECUTABLE="vendor/bin/sail";
SAIL_ENV_FILE=".env.sail"

if [ ! -f "$BASE_DIR/$SAIL_EXECUTABLE" ]; then
    printf "$SAIL_EXECUTABLE was not found. I would now spin up a container to install PHP dependencies. Okay (y/n)? "
    read ANSWER

    if [ "$ANSWER" != "${ANSWER#[Yy]}" ] ;then
        docker run --rm \
            -u "$(id -u):$(id -g)" \
            -v $BASE_DIR:/var/www/html \
            -w /var/www/html \
            laravelsail/php74-composer:latest \
            composer install --ignore-platform-reqs
    fi
fi

if [ ! -f "$BASE_DIR/.env" ]; then
    printf "No .env file found. I would install .env.sail. Okay (y/n)? "
    read ANSWER

    if [ "$ANSWER" != "${ANSWER#[Yy]}" ] ;then
        cp "$BASE_DIR/$SAIL_ENV_FILE" "$BASE_DIR/.env"
    fi
fi

"$BASE_DIR/$SAIL_EXECUTABLE" $@