DROP TABLE IF EXISTS Board;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS People;
DROP TABLE IF EXISTS Groups;
DROP TABLE IF EXISTS UserGroup;
DROP TABLE IF EXISTS Folder;
DROP TABLE IF EXISTS Thread;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS File;
DROP TABLE IF EXISTS UnreadMessage;
DROP TABLE IF EXISTS EditedMessage;
DROP TABLE IF EXISTS Session;

CREATE TABLE Board (
	id		VARCHAR(8) NOT NULL,
	name		VARCHAR(50),
	webroot		VARCHAR(50),
	docroot		VARCHAR(50),
	codedir		VARCHAR(20),
	themedir	VARCHAR(20),
	timeout		INT,
	admin		VARCHAR(8),
	PRIMARY KEY (id)
);

CREATE TABLE User (
	id		VARCHAR(8) NOT NULL,
	password	CHAR(16),
	board_id	VARCHAR(8) NOT NULL,
	lastaccess	DATETIME,
	person		INTEGER NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE People (
	id		INTEGER AUTO_INCREMENT NOT NULL,
	fullname	VARCHAR(30),
	email		VARCHAR(30),
	nickname	VARCHAR(20),
	phone		VARCHAR(20),
	PRIMARY KEY (id)
);

CREATE TABLE Groups (
	id		VARCHAR(8) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE UserGroup (
	user_id		VARCHAR(8) NOT NULL,
	group_id	VARCHAR(8) NOT NULL,
	PRIMARY KEY (user_id, group_id)
);

CREATE TABLE Folder (
	id		INTEGER AUTO_INCREMENT NOT NULL,
	parent		INTEGER,
	board		VARCHAR(8),
	name		VARCHAR(50),
	PRIMARY KEY (id)
);

CREATE TABLE Thread (
	id		INTEGER AUTO_INCREMENT NOT NULL,
	folder		INTEGER,
	board		VARCHAR(8),
	name		VARCHAR(40),
	created		DATETIME,
	owner		INTEGER,
	PRIMARY KEY (id)
);

CREATE TABLE Message (
	id		INTEGER AUTO_INCREMENT NOT NULL,
	thread		INTEGER,
	author		INTEGER,
	created		DATETIME,
	content		TEXT,
	PRIMARY KEY (id)
);

CREATE TABLE File (
	name		VARCHAR(30) NOT NULL,
	message		INTEGER,
	desription	VARCHAR(30),
	PRIMARY KEY (name)
);

CREATE TABLE UnreadMessage (
	message_id	INTEGER NOT NULL,
	user_id		VARCHAR(8) NOT NULL,
	PRIMARY KEY (message_id, user_id)
);

CREATE TABLE EditedMessage (
	message_id	INTEGER NOT NULL,
	user_id		VARCHAR(8) NOT NULL,
	altered		DATETIME,
	PRIMARY KEY (message_id, user_id)
);

CREATE TABLE Session (
	id		INTEGER AUTO_INCREMENT NOT NULL,
	user_id		VARCHAR(8),
	board_id	VARCHAR(8),
	expiry		DATETIME,
	PRIMARY KEY (id)
);
