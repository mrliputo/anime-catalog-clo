<?php

require_once('Classes/Anime.php');
require_once('Classes/Common.php');

// Set this value to null if you want to stream the episode
// or to the absolute path of your anime collection if you want to watch it locally
$baseDir = '/Volumes/Data/norman/Programming/Web/anime-catalog/Anime';

$rootDir = './Anime';
$title = $_GET['title'];
$anime = new Anime($rootDir, $title);
$episodes = $anime->getEpisodes();

if(Anime::getLastWatched() != $title) {
	Anime::updateLastWatched($title);
}

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
					<li><a id="list" class="bold" href="javascript:void(0)">Anime list</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a id="to-last" href="javascript:void(0)">Last Watched</a></li>
					<li><a id="refresh" href="javascript:void(0)">Refresh</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container-fluid">
		<div class="col-md-8 col-md-offset-2 main-content">
			
			<div class="card">
				
				<div class="episode-list-section col-md-7">
					<h3>Episode List <span style="font-weight: normal; font-size: .7em">(<?php echo $anime->getTotalEps() ?> episodes)</span></h3>
					<hr>
					<div class="episode-list">
						<div>
							<a style="color: #2ba0fa" href="javascript:void(0)" class="episode-item">
								Haven't been watched
							</a>

							<?php
							if($anime->dataExists()):
								?>
								<div class="episode-radiobtn">
									<input class="start-end-cb" <?php echo ($anime->getLastEps() == 'Plan to Watch') ? 'checked' : '' ?> value="Plan to Watch" type="radio" name="mark">
								</div>
								<?php
							endif;
							?>

						</div>
						

						<?php
						if($episodes):
							foreach($episodes as $ep):
								?>
								<div>
									<?php
									if($baseDir):
										?>

										<a ondragstart="dragStart(event)" draggable="true" dirty-name="<?php echo trim($ep) ?>" eps="<?php echo Common::cleanFilename($ep)?>" path="<?php echo $baseDir . '/' . $title . '/' . $ep ?>" href="javascript:void(0)" class="episode-item <?php echo ($anime->getLastEps() == $ep) ? 'highlight' : '' ?>">
											<?php echo Common::cleanFilename($ep) ?>
										</a>

										<?php
									else:
										?>
										<a ondragstart="dragStart(event)" draggable="true" dirty-name="<?php echo trim($ep) ?>" eps="<?php echo Common::cleanFilename($ep)?>" href="<?php echo $rootDir . '/' . $title . '/' . $ep ?>" class="episode-item <?php echo ($anime->getLastEps() == $ep) ? 'highlight' : '' ?>">
											<?php echo Common::cleanFilename($ep) ?>
										</a>
										<?php
									endif;
									?>

									<?php
									if($anime->dataExists()):
										?>
										<div class="episode-radiobtn">
											<input <?php echo ($anime->getLastEps() == $ep) ? 'checked' : '' ?> value="<?php echo $ep ?>" type="radio" name="mark">
										</div>
										<?php
									endif;
									?>

								</div>
								<div class="clear"></div>
								<?php
							endforeach;
						endif;
						?>

						<div>
							<a style="color: #ed27b3" href="javascript:void(0)" class="episode-item">
								Finished
							</a>

							<?php
							if($anime->dataExists()):
								?>
								<div class="episode-radiobtn">
									<input class="start-end-cb" <?php echo ($anime->getLastEps() == 'Finished') ? 'checked' : '' ?> value="Finished" type="radio" name="mark">
								</div>
								<?php
							endif;
							?>
							
						</div>
						
					</div>
				</div>

				<div class="info-section col-md-5" >
					<img src="<?php echo $anime->getImage() ?>" />
					<p class="info-title"><?php echo $title ?></p>
					<p><?php echo $anime->getGenre() ?></p>
					<p id="info-status" class="sizepointnine">
						
						<?php
						if($anime->dataExists()):
							if($anime->getLastEps() == "Plan to Watch"):
								echo "You haven't watched this anime";
							elseif($anime->getLastEps() == "Finished"):
								echo "You've finished this anime";
							else:
								echo "The last episode you watched is <span id='le'>" . Common::cleanFilename($anime->getLastEps()) . "</span>";
							endif;
						else:
							echo "No data";
						endif;
						?>

					</p>
					<a target="__blank" href="https://myanimelist.net/search/all?q=<?php echo $title ?>"><button style="width: 100%; margin-top: 20px" class="btn btn-sm btn-info">See on MyAnimeList</button></a>
					<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-btn-modal"><button style="width: 100%; margin-top: 10px" class="btn btn-sm btn-danger">Delete this anime</button></a>

					<?php
					if(!$anime->dataExists()):
						?>
						<div class="clear"></div>

						<form action="process/createDataFile.php" method="post" enctype="multipart/form-data" class="data-import">
							<label>Anime info</label>
							<input type="hidden" name="title" value="<?php echo $title ?>">
							<select name="type" class="form-control">
								<option value="TV">TV</option>
								<option value="Movie">Movie</option>
								<option value="OVA">OVA</option>
								<option value="Specials">Specials</option>
							</select>
							<input type="text" name="genre" placeholder="Genre" class="form-control" required>	
							<input id="cover-file" type="file" name="image" placeholder="Image" class="form-control hidden">
							<input id="cover-url" class="form-control" type="text" name="image-url" placeholder="Cover URL">
							<p class="ta-left sizepointnine"><a class="cover-btn" id="btn-file" class="cover-msg">Upload image file instead</a></p>
							<button type="submit" class="btn btn-info btn-sm" style="width: 100%">Submit</button>	
						</form>
						<?php
					endif;
					?>
					
				</div>

				<div class="clear"></div>

			</div>
			
		</div>
	</div>

	<div class="modal fade" id="delete-btn-modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Are you sure?</h4>
				</div>
				<div class="modal-body">
					<p>Are you really really sure? Are you really really, really really sure?</p>
				</div>
				<div class="modal-footer">
					<a type="button" class="btn btn-danger" href="process/delete.php?anime=<?php echo $title ?>">Delete</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="update-modal" role="dialog" type="">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update the episode?</h4>
				</div>
				<div class="modal-body">
					<p>Do you want to update the last watched episode of this anime to <span id="le-modal"></span>?</p>
				</div>
				<div class="modal-footer">
					<button type="button" id="confirm-yes" class="btn btn-primary confirm-btn">Yes</button>
					<button type="button" id="confirm-no" class="btn btn-default confirm-btn">No</button>
				</div>
			</div>
		</div>
	</div>

</body>

<footer>
	<p>Miku is the best. | <a class="bold" href="https://osu.ppy.sh/users/12158117">I'm bored.</a></p>
</footer>

<script type="text/javascript">

	function sendByAjax(_title, _new_content) {
		jQuery.ajax({
			url: "process/updateEpisode.php",
			cache: false,
			method: 'get',
			data: {
				title: _title,
				new_content: _new_content
			},
			success: function(result){
				updateStatus(result);
			},
			error:function(data){
				console.log(data);
			}
		});
	}

	function dragStart(event) {
		var eps = event.target.getAttribute("eps");

		<?php
		if($baseDir):
			?>
			var path = event.target.getAttribute("path");
			event.dataTransfer.setData("Text", path);
			<?php
		endif;
		?>

		$('.highlight').removeClass('highlight');
		$( "a:contains(" + eps + ")" ).addClass('highlight');
	}

	function updateStatus(string) {
		if(string == "Finished") {
			$('#info-status').html("You've finished this anime");
		}else if(string == "Plan to Watch") {
			$('#info-status').html("You haven't watched this anime");
		}else{
			$('#info-status').html("The last episode you watched is <span id='le'>" + string + "</span>");
		}
	}

	function modalConfirm(type) {
		var highlight = $('.highlight').attr('eps');
		var lastep = $('#le').html();

		if($('.episode-list').find('.highlight').length == 1 && highlight != lastep) {
			$("#update-modal").attr('type', type);
			$('#le-modal').html(highlight);
			$('#update-modal').modal('show');
		}else{
			if(type == 'refresh') {
				window.location = window.location;
			}else if(type == 'list') {
				window.location = "index.php";
			}
		}
	}

	$('input[type=radio][name=mark]').on('change', function() {
		sendByAjax("<?php echo $title ?>", $(this).val());
		$('.highlight').removeClass('highlight');

		if(!$(this).hasClass('start-end-cb')) {
			$(this).parent().parent().find('a').addClass('highlight');
		}
		
	});

	$('#refresh').click(function() {
		modalConfirm('refresh');
	});

	$('#list').click(function() {
		modalConfirm('list');
	});

	$('.confirm-btn').click(function() {
		var id = $(this).attr('id');
		var highlight = $('.highlight').attr('dirty-name');
		var type = $('#update-modal').attr('type');
		
		$('#update-modal').modal('hide');

		if(id == 'confirm-yes') {
			sendByAjax("<?php echo $title ?>", highlight);
		}

		if(type == 'refresh') {
			window.location = window.location;
		}else if(type == 'list') {
			window.location = "index.php";
		}
		
	});

	$('.cover-btn').click(function() {
		var id = $(this).attr('id');
		var coverUrl = $('#cover-url');
		var coverFile = $('#cover-file');

		if(id == 'btn-url') {
			$(this).html('Upload image file instead');
			$(this).attr('id', 'btn-file');
			coverUrl.removeClass('hidden');
			coverFile.addClass('hidden');
		}else{
			$(this).html('Paste cover URL instead');
			$(this).attr('id', 'btn-url');
			coverFile.removeClass('hidden');
			coverUrl.addClass('hidden');
		}
	});

	$('#to-last').click(function() {
		$([document.documentElement, document.body]).animate({
			scrollTop: ($(".highlight").offset().top - 20)
		}, 300);
	});
</script>
</html>
