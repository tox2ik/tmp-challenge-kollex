FROM ubuntu:bionic-20191202
ENV DEBIAN_FRONTEND="noninteractive" INITRD="no"
RUN >/dev/null apt-get -qq update >/dev/null
RUN >/dev/null apt-get install -qq -y --no-install-recommends curl ca-certificates make jq \
  git unzip xz-utils sqlite3 \
  php7.2-cli php7.2-xml php7.2-mbstring php7.2-pdo php7.2-sqlite php-xdebug

COPY usr/local/bin/install-composer.sh /usr/local/bin/install-composer.sh
RUN cd /usr/local/bin && \
  sh install-composer.sh && \
  mv composer.phar composer
COPY usr/local/bin/kollex.sh /kollex.sh
COPY vendor.tar.xz /tmp
RUN mkdir /code
ENTRYPOINT /kollex.sh




# vim: expandtab shiftwidth=2 tabstop=2
