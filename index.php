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

require_once('common.php');

getsession();

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case 'reset':
			getsession(true);
			header('Location: questions.php');
			exit();
			break;

		case 'answer':
			$answer = $_GET['answer'];

			if (isset($answer) == false || (is_float($answer) == false && is_numeric($answer) == false))
				die('ko');

			$identifier = $_SESSION['identifier'];
			$question = $_SESSION['currentquestion'];
			$rightanswer = $_SESSION['currentanswer'];

			$right = false;
			if ($rightanswer == $answer)
				$right = true;

			$query = "UPDATE questions SET frequency = frequency + 1";
			if ($right == true)
				$query .= ", rights = rights + 1";
			$query .= " WHERE id = " . $question;
			mysql_query($query);

			$query = "UPDATE tries SET completed = completed + 1";
			if ($right == true)
				$query .= ", rights = rights + 1";
			$query .= " WHERE id = " . $identifier;
			mysql_query($query);

			$query = "UPDATE answers SET selected = selected + 1 WHERE question_id = $question AND value = $answer";
			mysql_query($query);

			if ($right == true) {
				$_SESSION['right'] = $_SESSION['right'] + 1;
				echo 'ok';
			}
			else {
				echo $rightanswer;
			}

			exit();
			break;
	}
}

doheader('homepage');

?>

<div class="row">
	<div class="col-lg-6 text-right">
		<h1 class="maintitle">OPEN<br />DATA<br />QUIZ</h1>
	</div>
	<div class="col-lg-6 text-left visible-lg-block">
		<img src="img/logo.png" alt="logo" class="img-responsive" />
	</div>
</div>

<hr />

<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<p>
			Governi, comunità, smanettoni, giornalisti, politici: tutti parlano di opendata, ma nessuno ha ancora capito a cosa servano.
		</p>
		<p>
			Nell'attesa di una risposta, ecco qui un utilizzo ludico: un quiz di <?php echo $questions ?> domande tratte da alcuni dataset presi più o meno casualmente online, su tutti i temi possibili.
		</p>
	</div>
</div>

<hr />

<div class="row">
	<div class="col-lg-12 text-center">
		<a class="btn btn-primary btn-lg" href="questions.php">Inizia il Quiz</a>
	</div>
</div>

<?php

dofooter();
