ARG CENTOS_VERSION=6
FROM centos:${CENTOS_VERSION}
ARG CENTOS_VERSION=6
ARG CENTOS_MINOR_VERSION=1
RUN set -xe; \
  rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-${CENTOS_VERSION}.noarch.rpm; \
  rpm -Uvh https://rpms.remirepo.net/enterprise/remi-release-${CENTOS_VERSION}.rpm; \
  rpm -Uvh http://opensource.wandisco.com/centos/${CENTOS_VERSION}/git/x86_64/wandisco-git-release-${CENTOS_VERSION}-${CENTOS_MINOR_VERSION}.noarch.rpm; \
  yum install -y yum-utils git libcurl-devel; \
  yum -y install centos-release-scl; \
  yum -y install devtoolset-7; \
  scl enable devtoolset-7 bash; \
  echo source scl_source enable devtoolset-7 | tee /etc/profile.d/enable-gcc7.sh
ARG PHP_VERSION=54
RUN set -eux; \
  yum-config-manager --enable remi-php${PHP_VERSION}; \
  yum install -y php php-cli php-mcrypt php-mbstring php-mysqlnd php-pdo php-devel php-xml; \
  yum clean all; \
  curl -q https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer | php -- php -- --filename=composer --install-dir=/usr/local/bin

# Install PHP-FPM
RUN set -eux; \
    yum install -y php-fpm; \
    mkdir -p /run/php-fpm; \
    # Allow any IP address to listen to PHP-FPM & don't clear the env \
    sed -e '/listen\.allowed_clients/d' -e 's/listen = .*/listen = 9000/g' -e 's/;clear_env = .*/clear_env = no/g' -i /etc/php-fpm.d/www.conf; \
    # This line generates errors (no ext/soap) so delete it \
    sed -e '/php_value\[soap\.wsdl_cache_dir\].*/d' -i /etc/php-fpm.d/www.conf; \
    # Catch worker output and send error log to proper place \
    sed -e 's/php_admin_value\[error_log\].*/php_admin_value[error_log] = \/proc\/self\/fd\/2/g' -e 's/;catch_workers_output = .*/catch_workers_output = yes/g' -i /etc/php-fpm.d/www.conf; \
    sed -e 's/error_log = .*/error_log = \/proc\/self\/fd\/2/g' -i /etc/php-fpm.conf
