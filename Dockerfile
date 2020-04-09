
FROM webdevops/php-apache-dev:7.2



ENV DISTRO="linux-x64" \
    NODE_VERSION="v12.16.2"

# Install nodejs
RUN curl -sS https://nodejs.org/dist/"$NODE_VERSION"/node-"$NODE_VERSION-$DISTRO".tar.xz > /tmp/node-"$NODE_VERSION-$DISTRO".tar.xz \
    && mkdir -p /usr/local/lib/nodejs \
    && tar -xJf /tmp/node-"$NODE_VERSION-$DISTRO".tar.xz -C /usr/local/lib/nodejs \
    # Install yarn
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -yqq --no-install-recommends yarn

ENV PATH=/usr/local/lib/nodejs/node-"$NODE_VERSION-$DISTRO"/bin:$PATH

# Configure php
COPY etc/dev.php.ini /opt/docker/etc/php/php.ini

COPY etc/entrypoint.sh /opt/docker/provision/entrypoint.d/laravel-permissions.sh
