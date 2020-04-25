
FROM webdevops/php-apache-dev:7.2


ENV DISTRO="linux-x64" \
    NODE_VERSION="v12.16.2"

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

ENV PATH=/usr/local/lib/nodejs/node-"$NODE_VERSION-$DISTRO"/bin:$PATH

# Install nodejs
RUN curl -sS "https://nodejs.org/dist/$NODE_VERSION/node-$NODE_VERSION-$DISTRO.tar.xz" > /tmp/node-"$NODE_VERSION-$DISTRO".tar.xz \
    && mkdir -p /usr/local/lib/nodejs \
    && tar -xJf /tmp/node-"$NODE_VERSION-$DISTRO".tar.xz -C /usr/local/lib/nodejs \
    # Install yarn
    && npm install -g yarn@v1.22.4 \
    # Clean apt cache
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure php
COPY dev.php.ini /opt/docker/etc/php/php.ini

COPY entrypoint.sh /opt/docker/provision/entrypoint.d/laravel-permissions.sh
