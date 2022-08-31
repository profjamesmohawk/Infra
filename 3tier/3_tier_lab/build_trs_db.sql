DROP DATABASE IF EXISTS reservations;
CREATE DATABASE reservations;
USE reservations;

CREATE TABLE users (
	id	INT NOT NULL AUTO_INCREMENT,
	name	VARCHAR(150) NOT NULL,
	passwd	VARCHAR(150) NOT NULL,
	UNIQUE (name),
	PRIMARY KEY (id)
);

CREATE TABLE systems (
	id	INT NOT NULL AUTO_INCREMENT,
	name	VARCHAR(150) NOT NULL,
	UNIQUE (name),
	PRIMARY KEY (id)
);

CREATE TABLE reservations (
	res_date	DATE NOT NULL,
	user_id		INT  NOT NULL,
	system_id	INT  NOT NULL,
	PRIMARY KEY (res_date,user_id,system_id)
);

INSERT INTO users (name,passwd) VALUES
	('alice','pw'),
	('bob','pw'),
	('rocky','pw');

INSERT INTO systems (name) VALUES
	('sut_1'),
	('sut_2'),
	('sut_3'),
	('sut_4'),
	('sut_5');

INSERT INTO reservations ( res_date, user_id, system_id) VALUES
	('2021-10-1',1,2),
	('2021-10-1',1,3),
	('2021-10-2',2,3)
	;
