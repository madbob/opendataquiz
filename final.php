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

if ($_SESSION['index'] < $questions) {
	header('Location: questions.php');
	exit();
}

doheader('finito!');

?>

<div class="row">
	<div class="col-lg-12">
		<div class="page-header text-center">
			<h1>Fine!</h1>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-8 col-lg-offset-2 text-center">
		<div class="ct-final"></div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 text-center">
		<a class="btn btn-primary btn-lg" href="https://twitter.com/share?url=http%3A%2F%2Fopendataquiz.madbob.org%2F&text=%23opendata+quiz%3A+io+ho+risposto+correttamente+a+<?php echo $_SESSION['right'] ?>+domande+su+<?php echo $questions ?>%3A+prova+tu%21" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600\');return false;" target="_blank">Condividi su Twitter il tuo Risultato</a>
		<a class="btn btn-default btn-lg" href="index.php?action=reset">Ricomincia Daccapo</a>
	</div>
</div>

<hr />

<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<p>
			Gli opendata, se opportunamente usati, visualizzati ed intrecciati, ci permettono di avere una comprensione più completa del mondo. Al di là delle soggettive opinioni personali, delle ipotesi teoriche e di chi trae beneficio dal distorcere i fatti.
		</p>
		<p>
			Ogni tanto val la pena dare una occhiata ai diversi siti che, in maniera più o meno tematica, li raccolgono e li elaborano per far emergere considerazioni, effetti e relazioni non sempre così evidenti e palesi. Tra i miei preferiti:
		</p>
		<ul>
			<li><a href="http://blog.openpolis.it/">Il Blog di OpenPolis</a></li>
			<li><a href="http://www.infodata.ilsole24ore.com/">Info Data Blog de Il Sole 24 Ore</a></li>
			<li><a href="http://www.theguardian.com/data">DataBlog di The Guardian</a></li>
		</ul>
		<p>
			Insomma: potrebbero servire anche ad altro, oltre che per fare un giochino online... Se esistessero davvero, e non fossero solo un pretesto per far convegni...
		</p>
	</div>
</div>

<hr />

<div class="row">
	<div class="col-lg-8 col-lg-offset-2 text-center">
		<p>
			<b>Made with <img src="img/madewithhammer.png" /> by <a href="http://madbob.org/">MadBob</a> | See <a href="https://github.com/madbob/opendataquiz">on GitHub</a></b>
		</p>
	</div>
</div>

<script>
	var data = {
		labels: ['OK', 'KO'],
		series: [<?php echo $_SESSION['right'] ?>, <?php echo $questions - $_SESSION['right'] ?>]
	};

	<?php fixcirclegraph() ?>

	var chart = new Chartist.Pie('.ct-final', data, options, responsiveOptions);

	$('.ct-final').animate({opacity: 1}, 500, function () {
		$('.ct-final .ct-slice[ct\\:value=<?php echo $_SESSION['right'] ?>]').attr('class', 'ct-slice right');
		$('.ct-final .ct-slice[ct\\:value!=<?php echo $_SESSION['right'] ?>]').attr('class', 'ct-slice wrong');
	});
</script>

<?php

dofooter();
