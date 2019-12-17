# DEV: Use `python:slim` instead of an `alpine` image to support installing wheels from PyPI
#      this drastically improves test execution time since python dependencies don't all
#      have to be built from source all the time (grpcio takes forever to install)
FROM python:slim

RUN \
  # Install system dependencies
  echo 'deb http://deb.debian.org/debian stretch main' >> /etc/apt/sources.list \
  && apt-get update \
  && apt-get install -y --no-install-recommends \
      build-essential \
      ca-certificates \
      curl \
      git \
      jq \
      libbz2-dev \
      libffi-dev \
      liblzma-dev \
      libmariadb-dev \
      libmariadb-dev-compat \
      libmemcached-dev \
      libmemcached-dev \
      libncurses5-dev \
      libncursesw5-dev \
      # psycopg2 versions <2.7 do not support pg versions >10 and there are
      # no plans to backport: https://github.com/psycopg/psycopg2/issues/594
      # This restriction can be removed once we drop support for psycopg2<2.7
      libpq5=9.6.15-0+deb9u1 \
      libpq-dev=9.6.15-0+deb9u1 \
      libreadline-dev \
      libsasl2-dev \
      libsqlite3-dev \
      # Needed to support Python 3.4
      libssl1.0-dev \
      # Can be re-enabled once we drop 3.4
      # libssh-dev \
      # libssl-dev \
      patch \
      python-openssl\
      python-dev\
      python3-dev\
      wget \
      zlib1g-dev \
  # Cleaning up apt cache space
  && rm -rf /var/lib/apt/lists/*


# Configure PATH environment for pyenv
ENV PYENV_ROOT /root/.pyenv
ENV PATH ${PYENV_ROOT}/shims:${PYENV_ROOT}/bin:$PATH

# Install pyenv
RUN git clone git://github.com/yyuu/pyenv.git "${PYENV_ROOT}"

# Install all required python versions
RUN \
  pyenv install 2.7.15 \
  && pyenv install 3.4.9 \
  && pyenv install 3.5.6 \
  && pyenv install 3.6.8 \
  && pyenv install 3.7.2 \
  && pyenv install 3.8.0 \
  # include system here to use for pip and tox
  && pyenv global system 2.7.15 3.4.9 3.5.6 3.6.8 3.7.2 3.8.0 \
  && pip install --upgrade pip

# Install Python dependencies
# DEV: `tox==3.7` introduced parallel execution mode
#      https://tox.readthedocs.io/en/3.7.0/example/basic.html#parallel-mode
RUN pip install "tox>=3.7,<4.0"

CMD ["/bin/bash"]

# To install MySQL-python need to add this hack
# https://github.com/DefectDojo/django-DefectDojo/issues/407#issuecomment-415862064
RUN sed '/st_mysql_options options;/a unsigned int reconnect;' /usr/include/mysql/mysql.h -i.bkp
