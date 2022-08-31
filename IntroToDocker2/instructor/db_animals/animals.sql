CREATE DATABASE animals;
USE animals;

CREATE TABLE votes (
	animal	VARCHAR(50)  NOT NULL,
	votes	INT  NOT NULL,
	PRIMARY KEY (animal)
);

INSERT INTO votes (animal,votes) VALUES
	('dog',0),
	('cat',0);

GRANT ALL PRIVILEGES ON *.* TO 'app_user'@'%' IDENTIFIED BY 'app_pass' WITH GRANT OPTION;
FLUSH PRIVILEGES;
