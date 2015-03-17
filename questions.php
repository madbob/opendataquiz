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

if ($_SESSION['index'] >= $questions) {
	header('Location: final.php');
	exit();
}

doheader('domanda ' . $_SESSION['index'] . ' su ' . $questions);

list ($question, $target) = randomquestion();
$rightanswer = $question->data[$target];

?>

<div class="row">
	<div class="col-lg-12">
		<div class="progress">
			<?php $perc = $_SESSION['index'] * (100 / $questions) ?>
			<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $perc ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $perc ?>%;">
				<span class="sr-only"><?php echo $perc ?>% completo</span>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="page-header text-center">
			<h1><?php echo $question->title ?></h1>

			<h4>
				<?php
				$labels = $question->labels;
				sort ($labels);
				echo join(', ', $labels);
				?>
			</h4>

			<h2><?php echo sprintf($question->question, $question->labels[$target]) ?></h2>
			<h5>Clicca sulla <?php $question->type == 'Bar' ? print('barra') : print('fetta') ?> corrispondente</h5>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-8 col-lg-offset-2 text-center">
		<div class="ct-chart"></div>
	</div>
</div>

<div class="row final hidden">
	<hr />

	<div class="col-lg-offset-2 col-lg-4 text-center">
		<div class="alert alert-success hidden" role="alert">Risposta Esatta!</div>
		<div class="alert alert-danger hidden" role="alert">Risposta Sbagliata!</div>
		<p>Fonte: <a href="<?php echo $question->source ?>"><?php echo $question->source ?></a></p>
	</div>
	<div class="col-lg-4 text-right">
		<a class="btn btn-primary btn-lg" href="questions.php">Avanti</a>
	</div>
</div>

<script>
	<?php initMixedData($question) ?>

	var chart = new Chartist.<?php echo $question->type ?>('.ct-chart', data, options, responsiveOptions);
	var replied = false;

	$('.ct-chart').on('click', '.ct-bar, .ct-slice', function () {
		if (replied == true)
			return;
		replied = true;

		var selected = $(this).attr('ct:value');

		$.get('index.php', {action: 'answer', answer: selected}, function (reply) {
			var right = false;
			if (reply == 'ok')
				right = true;

			<?php manageTransform($question) ?>
		});
	});
</script>

<?php

function initMixedData($question) {
	$sorted = $question->data;
	sort($sorted);
	$tot = count($question->data);

	?>

	<?php if ($question->type == 'Bar'): ?>

	var data = {
		labels: [<?php echo join(',', array_fill(0, $tot, "'????'")) ?>],
		series: [[<?php echo join(',', $sorted) ?>]]
	};

	var options = {};
	var responsiveOptions = [];

	<?php elseif ($question->type == 'Pie'): ?>

	var data = {
		labels: [<?php echo join(',', array_fill(0, $tot, "'????'")) ?>],
		series: [<?php echo join(',', $sorted) ?>]
	};

	<?php

	fixcirclegraph();

	endif;
}

function manageTransform($question) {
	?>

	$('.ct-chart').animate({opacity: 0}, 500, function () {
		<?php if ($question->type == 'Bar'):
			$t = 'bar';
			?>

			var data = {
				labels: ['<?php echo join("','", $question->labels) ?>'],
				series: [[<?php echo join(',', $question->data) ?>]]
			};

		<?php elseif ($question->type == 'Pie'):
			$t = 'slice';
			?>

			var data = {
				labels: ['<?php echo join("','", $question->labels) ?>'],
				series: [<?php echo join(',', $question->data) ?>]
			};

		<?php endif ?>

		chart.update(data);
		selected = selected.replace('.', '\\.');
		reply = reply.replace('.', '\\.');

		if (right == false) {
			$('.ct-chart .ct-<?php echo $t ?>[ct\\:value=' + selected + ']').attr('class', 'ct-<?php echo $t ?> wrong');
			$('.ct-chart .ct-<?php echo $t ?>[ct\\:value=' + reply + ']').attr('class', 'ct-<?php echo $t ?> right');
		}
		else {
			$('.ct-chart .ct-<?php echo $t ?>[ct\\:value=' + selected + ']').attr('class', 'ct-<?php echo $t ?> right');
		}

		$('.ct-chart').animate({opacity: 1}, 500, function () {
			$('.final').removeClass('hidden');
			if (right == true)
				$('.final .alert-success').removeClass('hidden');
			else
				$('.final .alert-danger').removeClass('hidden');
		});
	});

	<?php
}
