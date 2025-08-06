FROM php:8.2-fpm

# Добавляем установщик PHP расширений
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libicu-dev \
    zip \
    unzip \
    vim \
    nano \
    htop \
    supervisor \
    cron \
    qpdf \
    default-mysql-client \
    postgresql-client \
    sqlite3 \
    && rm -rf /var/lib/apt/lists/*

# Копируем конфигурацию PHP
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Устанавливаем PHP расширения
RUN install-php-extensions \
    @composer \
    mbstring \
    exif \
    intl \
    gd \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    bcmath \
    opcache \
    pcntl \
    zip \
    curl \
    redis \
    imagick \
    xdebug \
    soap \
    sockets \
    calendar \
    gettext \
    imap \
    ldap

# Создаем пользователя и группу
ARG DOCKER_HOST_USER=www-data
ARG DOCKER_HOST_UID=1000
ARG DOCKER_HOST_GROUP=www-data
ARG DOCKER_HOST_GID=1000

RUN if [ "$DOCKER_HOST_UID" != "33" ] || [ "$DOCKER_HOST_GID" != "33" ]; then \
        groupmod -g $DOCKER_HOST_GID www-data && \
        usermod -u $DOCKER_HOST_UID -g $DOCKER_HOST_GID www-data; \
    fi

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Создаем директории для логов
RUN mkdir -p /var/log/supervisor /var/log/php

# Создаем базовую конфигурацию supervisor (запускаем от root)
RUN echo '[supervisord]\n\
nodaemon=true\n\
user=root\n\
logfile=/var/log/supervisor/supervisord.log\n\
pidfile=/var/run/supervisord.pid\n\
\n\
[program:php-fpm]\n\
command=php-fpm\n\
autostart=true\n\
autorestart=true\n\
stderr_logfile=/var/log/supervisor/php-fpm.err.log\n\
stdout_logfile=/var/log/supervisor/php-fpm.out.log\n\
user=www-data\n\
priority=100\n\
\n\
[unix_http_server]\n\
file=/var/run/supervisor.sock\n\
chmod=0700\n\
\n\
[supervisorctl]\n\
serverurl=unix:///var/run/supervisor.sock\n\
\n\
[rpcinterface:supervisor]\n\
supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface' > /etc/supervisor/conf.d/supervisord.conf

# Меняем владельца только рабочей директории, логи остаются root
RUN chown -R www-data:www-data /var/www

# НЕ переключаемся на www-data, остаемся root для supervisor
# USER www-data

EXPOSE 9000

CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
