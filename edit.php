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

doheader();

if (isset($_GET['password']) == false || $_GET['password'] != $editpwd) {
	?>

	<div class="row">
		<div class="col-sm-12 text-centered">
			<div class="alert alert-danger" role="alert">Accesso non Autorizzato</div>
		</div>
	</div>

	<?php
}
else {
	dbconnect();

	if (isset($_GET['action']) == true && $_GET['action'] == 'add') {
		$title = mysql_escape_string ($_POST['title']);
		$type = mysql_escape_string ($_POST['type']);
		$question = mysql_escape_string ($_POST['question']);
		$source = mysql_escape_string ($_POST['source']);

		$query = "INSERT INTO questions (title, type, question, source) VALUES ('$title', '$type', '$question', '$source')";
		mysql_query($query);
		$questionid = mysql_insert_id();

		for ($i = 0; $i < count($_POST['labels']); $i++) {
			$label = mysql_escape_string ($_POST['labels'][$i]);
			if ($label == '')
				continue;

			$value = mysql_escape_string ($_POST['values'][$i]);
			$query = "INSERT INTO answers (question_id, label, value) VALUES ($questionid, '$label', '$value')";
			mysql_query($query);
		}
	}

	?>

	<div class="row">
		<form method="POST" action="edit.php?action=add" class="form-horizontal">
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Titolo</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="title">
				</div>
			</div>

			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Tipo</label>
				<div class="col-sm-10">
					<div class="radio">
						<label>
							<input type="radio" name="type" value="Bar" checked>
							Grafico a Barre
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="type" value="Pie">
							Grafico a Torta
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="question" class="col-sm-2 control-label">Domanda</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="question">
				</div>
			</div>

			<div class="form-group">
				<label for="val" class="col-sm-2 control-label">Valori</label>
				<div class="col-sm-10">
					<?php for ($i = 0; $i < 5; $i++): ?>
					<input class="col-sm-6" type="text" class="form-control" name="labels[]">
					<input class="col-sm-6" type="text" class="form-control" name="values[]">
					<?php endfor ?>
				</div>
			</div>

			<div class="form-group">
				<label for="source" class="col-sm-2 control-label">Fonte</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="source">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Salva</button>
				</div>
			</div>
		</form>
	</div>

	<?php
}

dofooter();
