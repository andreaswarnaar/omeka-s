FROM debian:bookworm-slim
MAINTAINER Andreas Warnaar

ARG local_user_id=1000
ARG local_user_group_id=1000

ENV DEBIAN_FRONTEND noninteractive
ENV TERM linux
ENV NODE_MAJOR=20

# Create Project user assuming the host users is 1000:1000
RUN groupadd --gid $local_user_group_id omekas && \
    useradd -u $local_user_id -g $local_user_group_id omekas -s /bin/bash -m && \
    usermod -a -G www-data omekas

RUN echo Europe/Amsterdam > /etc/timezone && \
ln -sf /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime && \
dpkg-reconfigure -f noninteractive tzdata


RUN apt-get update \
    && apt-get install apt-utils -y  \
    && apt-get -o Dpkg::Options::="--force-confdef" -y install  \
          gnupg \
          curl \
          wget \
          ssl-cert \
          lsb-release \
          ca-certificates \
          less \
          zip \
          libzip-dev \
          unzip \
          nano \
          git \
          apache2 \
          supervisor \
          inetutils-syslogd \
          iproute2 \
          unzip
RUN  apt-get install -y \
              php \
              php-dom \
              php-zip \
              php-soap \
              php-gd \
              php-curl \
              php-xsl \
              php-intl \
              php-xdebug \
              php-mbstring \
              php-fpm \
              php8.2-mysql
# Install NODE JS
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install nodejs

# Install Composer
RUN curl -sS -o /usr/local/bin/composer https://getcomposer.org/composer-2.phar \
    && chmod +x /usr/local/bin/composer

# Install php security checker
RUN curl -L https://github.com/fabpot/local-php-security-checker/releases/download/v2.1.3/local-php-security-checker_2.1.3_linux_amd64 --output /usr/local/bin/php-security-checker && \
    chmod +x /usr/local/bin/php-security-checker

# Cleanup cache
RUN apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Always change user to host
RUN echo 'su omekas' > /root/.bashrc

#Configure Apache #####
RUN a2enmod ssl \
        && a2enmod headers \
        && a2enmod rewrite \
        && a2enmod deflate \
        && a2enmod proxy_fcgi

# logs should go to stdout / stderr
ENV APACHE_LOG_DIR /var/log/apache2
RUN	ln -sfT /dev/stderr "$APACHE_LOG_DIR/error.log"; \
	ln -sfT /dev/stdout "$APACHE_LOG_DIR/access.log"; \
	ln -sfT /dev/stdout "$APACHE_LOG_DIR/other_vhosts_access.log"; \
	chown -R --no-dereference "$APACHE_RUN_USER:$APACHE_RUN_GROUP" "$APACHE_LOG_DIR"

COPY apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY apache/envvars /etc/apache2/envvars
COPY apache/supervisor-apache2-foreground /supervisor-apache2-foreground

# Configure PHP
RUN ls -als /etc/php/

COPY php/20-xdebug-cli.ini /
COPY php/20-xdebug-fpm.ini /
COPY php/www.conf /etc/php/8.2/fpm/pool.d/www.conf
# Configure FPM to run properly on docker
RUN sed -i "/listen = .*/c\listen = [::]:9100" /etc/php/8.2/fpm/pool.d/www.conf && \
    mkdir -p /run/php && touch /run/php/php8.2-fpm.sock && touch /run/php/php8.2-fpm.pid

# Configure profile and main process
COPY .bashrc /home/omekas
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80 443
WORKDIR /var/www
CMD ["/entrypoint.sh"]
