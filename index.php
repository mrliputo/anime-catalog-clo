<?php

require_once('Classes/Anime.php');
require_once('Classes/Common.php');

$rootDir = './Anime';
$animeDirs = Anime::dirs($rootDir);

$lastWatched = Anime::getLastWatched();
$lw = new Anime($rootDir, $lastWatched)

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Anime Catalog</title>
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<script src="assets/js/jquery.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="col-md-9 col-md-offset-1">
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">Animu CataOwOlog</a>
				</div>
				<ul class="nav navbar-nav">
					<li><a class="bold" href="index.php">Anime list</a></li>
					<li class="active"><a class="bf" id="bf-all" href="javascript:void(0)">All</a></li>
					<li><a class="bf" id="bf-watching" href="javascript:void(0)">Watching</a></li>
					<li><a class="bf" id="bf-planned" href="javascript:void(0)">Plan to Watch</a></li>
					<li><a class="bf" id="bf-finished" href="javascript:void(0)">Finished</a></li>
					<li><a class="bf" id="bf-no-data" href="javascript:void(0)">No Data</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a id="show-titles" class="toggle" href="javascript:void(0)">Show titles</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container-fluid">
		<div class="col-md-8 col-md-offset-2 main-content">

			<?php
			if(Anime::exists($rootDir, $lastWatched)):
				?>

				<div class="col-md-12">
					<div class="col-md-12 big-box-container">
						<div class="big-box">
							<div class="big-box-image">
								<img class="tall" src="<?php echo $lw->getImage() ?>">
							</div>
							<div class="big-box-info">
								<img class="tall" src="<?php echo $lw->getImage() ?>">
								<div class="info">
									<p>The last anime you watched</p>
									<p style="font-weight: bold; font-size: 1.3em;margin-top: 80px">

										<?php
										echo $lastWatched;
										if($lw->dataExists()) echo ' (' . $lw->getType() . ')';
										?>

									</p>
									<p>
										<?php echo $lw->getGenre() ?>
									</p>

									<p style="font-style: italic">

										<?php 
										if($lw->dataExists()):
											if($lw->getLastEps() == "Finished"):
												echo 'Finished';
											elseif($lw->getLastEps() == "Plan to Watch"):
												echo "You haven't watched this yet - " . $lw->getTotalEps() . ' episodes';
											else:
												echo 'Last ep: ' . Common::cleanFilename($lw->getLastEps());
											endif;
										else:
											echo "No data - " . $lw->getTotalEps() . ' episodes';
										endif;
										?>

									</p>

									<a class="continue-btn" href="anime.php?title=<?php echo $lastWatched ?>"><button class="btn btn-info">Continue Watching</button></a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
			endif;
			?>

			<div class="col-md-12">

				<?php 
				if($animeDirs):
					foreach($animeDirs as $dir): 
						if($dir != $lastWatched):
							$anime = new Anime($rootDir, $dir); 
							$lastEp = $anime->getLastEps();
							$filter = 'f-no-data';

							if($anime->dataExists()):
								if($lastEp == "Finished") $filter = 'f-finished';
								elseif($lastEp == "Plan to Watch") $filter = 'f-planned';
								else $filter = 'f-watching';
							endif;
							?>

							<div class="col-md-3 small-box-container hidden <?php echo $filter ?>">
							<div class="small-box">
								<img style="width: 100%" class="wide" src="<?php echo $anime->getImage() ?>">
								<div class="middle">
									<div class="text" style="background-color: 

									<?php
									if($anime->dataExists()):
										if($lastEp == "Plan to Watch") echo '#5bc0de';
										elseif($lastEp == "Finished") echo '#dd13c3';
										else echo '#13c689';
									else:
										echo '#e13e3e';
									endif;
									?>

									">

									<p style="font-weight: bold; font-size: 1.1em">

										<?php
										if($anime->dataExists()):
											?>
											<a href="anime.php?title=<?php echo $dir ?>"><?php echo $dir . ' (' . $anime->getType() . ')' ?></a>
											<?php
										else:
											?>
											<a href="anime.php?title=<?php echo $dir ?>"><?php echo $dir ?></a>
											<?php
										endif;
										?>

									</p>
									<p>
										<?php echo $anime->getGenre() ?>
									</p>


									<?php 
									if($anime->dataExists()):
										if($lastEp == "Plan to Watch"): 
											?>
											<p>Plan to Watch - <?php echo $anime->getTotalEps() ?> episodes</p>
											<?php 
										elseif($lastEp == "Finished"): 
											?>
											<p>Finished</p>
											<?php 
										else:
											?>
											<p>Watching - <?php echo $anime->getTotalEps() ?> episodes</p>
											<?php 
										endif;
									else:
										?>
										<p>No data - <?php echo $anime->getTotalEps() ?> episodes</p>
										<?php
									endif; 
									?>

								</div>
							</div>
						</div>
					</div>

					<?php
				endif; 
			endforeach;
		endif;
		?>

	</div>

	<div class="clear"></div>

</div>
</div>
</body>

<footer>
	<p>Miku is the best. | <a style="font-weight: bold" href="https://osu.ppy.sh/users/12158117">I'm bored.</a></p>
</footer>

<script type="text/javascript">

	$('.small-box-container').removeClass('hidden');

	$('.bf').click(function() {
		var box = $('.small-box-container');
		var id = $(this).attr('id');

		box.addClass('hidden');
		$('.active').removeClass('active');
		$(this).parent().addClass('active');

		if(id == 'bf-all') {
			box.removeClass('hidden');
		}else if(id == 'bf-watching') {
			$('.f-watching').removeClass('hidden');
		}else if(id == 'bf-planned') {
			$('.f-planned').removeClass('hidden');
		}else if(id == 'bf-finished') {
			$('.f-finished').removeClass('hidden');
		}else if(id == 'bf-no-data') {
			$('.f-no-data').removeClass('hidden');
		}
	});

	$('.toggle').click(function() {
		if($(this).attr('id') == 'show-titles') {
			$(this).attr("id", "hide-titles");
			$(this).html("Hide titles");
			$('.middle').addClass("opacity1");
		}else{
			$(this).attr("id", "show-titles");
			$(this).html("Show titles");
			$('.middle').removeClass("opacity1");
		}
	});
</script>
</html>