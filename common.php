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

/** ********************************************************************************************************/

function doheader($title = '') {
?>
<html>
<head>
	<meta charset=utf-8 />
	<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
	<title>OpenDataQuiz: <?php echo $title ?></title>
	<script src="js/jquery.min.js"></script>
	<script src="js/chartist.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/odq.css" />
</head>

<body>
	<br />
	<div class="container">
<?php
}

/** ********************************************************************************************************/

function dofooter() {
?>
	</div>
</body>
</html>

<?php
}

function dbconnect() {
	global $dbhost, $dbuser, $dbpassword, $dbname;
	mysql_connect($dbhost, $dbuser, $dbpassword);
	mysql_select_db($dbname);
	mysql_query('SET NAMES utf8');
}

/** ********************************************************************************************************/

function getsession($reset = false) {
	dbconnect();

	if (session_status() == PHP_SESSION_NONE)
		session_start();

	if (isset ($_SESSION['index']) == false || $reset == true) {
		mysql_query('INSERT INTO tries (completed, rights) VALUES (0, 0)');
		$_SESSION['identifier'] = mysql_insert_id();
		$_SESSION['index'] = 0;
		$_SESSION['right'] = 0;
		$_SESSION['questions'] = array();
	}
}

/** ********************************************************************************************************/

function randomquestion() {
	do {
		$res = mysql_query('SELECT * FROM questions ORDER BY RAND() LIMIT 0, 1');
		$question = mysql_fetch_array($res);
	} while (array_search ($question['id'], $_SESSION['questions']) !== false);

	$_SESSION['index'] += 1;
	array_push($_SESSION['questions'], $question['id']);
	$_SESSION['currentquestion'] = $question['id'];

	$query = 'SELECT * FROM answers WHERE question_id = ' . $question['id'] . ' ORDER BY label ASC';
	$res = mysql_query($query);

	$ret = new stdClass();
	$ret->id = $question['id'];
	$ret->title = $question['title'];
	$ret->question = $question['question'];
	$ret->type = $question['type'];
	$ret->source = $question['source'];
	$ret->labels = array();
	$ret->data = array();

	$count = 0;

	while ($answer = mysql_fetch_array($res)) {
		$ret->labels[] = $answer['label'];
		$ret->data[] = $answer['value'];
		$count++;
	}

	$target = rand() % $count;
	$_SESSION['currentanswer'] = $ret->data[$target];

	return array ($ret, $target);
}

/** ********************************************************************************************************/

function fixcirclegraph() {
	?>

	var options = {
		labelInterpolationFnc: function(value) {
			return value[0]
		}
	};

	var responsiveOptions = [
		['screen and (min-width: 640px)', {
			chartPadding: 30,
			labelOffset: 100,
			labelDirection: 'explode',
			labelInterpolationFnc: function(value) {
				return value;
			}
		}],
		['screen and (min-width: 1024px)', {
			labelOffset: 90,
			chartPadding: 20
		}]
	];

	<?php
}
