# the latest mariadb
FROM mariadb

# make sure 3306 is exposed
EXPOSE 3306

# set ENV Vars
ENV MYSQL_ROOT_PASSWORD=badpass

# copy in the DB build script
COPY animals.sql /tmp

# place db build script where the containers init scripts can find it
COPY build_db.sh /docker-entrypoint-initdb.d

