FROM centos

# add update/clean
RUN yum -y update; yum clean all
RUN yum -y install httpd php php-mysql; yum clean all

COPY ./*php /var/www/html/

EXPOSE 80

ENV DB_HOST="db1" DB_USER="app_user" DB_PASS="app_pass"

ENTRYPOINT ["/usr/sbin/httpd", "-D", "FOREGROUND"]
