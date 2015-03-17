<?php

/*
	OpenDataQuiz
	Copyright (C) 2015  Roberto Guido <bob@linux.it>

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU Affero General Public License as
	published by the Free Software Foundation, either version 3 of the
	License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Affero General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('conf.php');

mysql_connect($dbhost, $dbuser, $dbpassword);
mysql_select_db($dbname);

$query = '
CREATE TABLE questions (
	id int primary key auto_increment,
	title varchar(200),
	type varchar(50),
	question varchar(200),
	source varchar(200),
	frequency int default 0,
	rights int default 0
)';
mysql_query($query);

$query = '
CREATE TABLE answers (
	id int primary key auto_increment,
	question_id int,
	label varchar(100),
	value float,
	selected int default 0
)';
mysql_query($query);

$query = '
CREATE TABLE tries (
	id int primary key auto_increment,
	completed int default 0,
	rights int default 0
)';
mysql_query($query);
