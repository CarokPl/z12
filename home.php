<?php

function dirToArray($dir)
{
	$result = array();
	$cdir = scandir($dir);
	foreach ($cdir as $key => $value) {
		if (!in_array($value, array(".", ".."))) {
			if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
				$result[$value] = array();
			} else {
				$result[] = $value;
			}
		}
	}
	return $result;
}

function uploadFile($file, $catalog)
{
	if (is_uploaded_file($file['tmp_name'])) {
		if ($file['size'] > MAX_FILE_SIZE) {
			echo "<div class='alert alert-danger'><p><b>Uwaga!</b> Plik jest za duży. Maksymalnie: " . MAX_FILE_SIZE . "</p></div>";
		} else {
			$catalogToUpload = 'files/' . $_SESSION['login'] . "/";
			$catalogToUpload .= $catalog . '/';
			$catalogToUpload .= $file['name'];
			move_uploaded_file($file['tmp_name'], $catalogToUpload);
		}
	}
}

function downloadFile($file)
{
	if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($file) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		exit;
	}
}

function createCatalog($name)
{
	mkdir("files/" . $_SESSION['login'] . '/' . $name, 0700);
	header('Location: index.php?catalog=' . $name);
}

$catalog = $_GET['catalog'] ? $_GET['catalog'] : '';
$path = $_SESSION['login'] . '/';
$path .= $catalog ? $catalog . '/' : '';

if (!file_exists('files/' . $path)) header('Location: index.php');


$download = $_GET['download'] ? $_GET['download'] : null;
$newCatalog = $_GET['newCatalog'] ? $_GET['newCatalog'] : null;
if ($download) {
	downloadFile($download);
}
if ($newCatalog) {
	createCatalog($newCatalog);
}
uploadFile($_FILES['file'], $catalog);

$files = dirToArray('files/' . $path);
?>

<header class="page-header">
	<h1>Dysk
		<small>/<?= $path ?></small>
	</h1>
</header>
<section style="margin-bottom: 20px;">
	<form method="post" id="uploadFile-form" enctype="multipart/form-data">
		<label class="btn btn-primary btn-file">
			Dodaj plik <input type="file" style="display: none;" name="file"
							  onchange="document.getElementById('uploadFile-form').submit();">
		</label>
		<? if ($catalog != ''): ?>
			<a class="btn btn-default" href="index.php">
				<span class="glyphicon glyphicon-arrow-left"></span>
			</a>
		<? else: ?>
			<button type="button" class="btn btn-default" data-toggle="modal" data-target=".add-directory-modal">
				Utwórz katalog
			</button>
		<? endif; ?>
	</form>

</section>

<section style="text-align: center;">
	<? foreach ($files as $key => $value): ?>
		<? if (is_array($value)): ?>
			<div class="col-sm-3" style="height: 150px" title="<?= $key ?>">
				<a href="index.php?catalog=<?= $key ?>">
					<span class="glyphicon glyphicon-folder-open" style="font-size: 100px"></span>
				</a>
				<p style="overflow-x: hidden"><?= $key ?></p>
			</div>
		<? else: ?>
			<div class="col-sm-3" style="height: 150px" title="<?= $value ?>">
				<a href="index.php?download=files/<?= $path . $value ?>">
					<span class="glyphicon glyphicon-file" style="font-size: 100px"></span>
				</a>
				<p style="overflow-x: hidden"><?= $value ?></p>
			</div>
		<? endif; ?>
	<? endforeach; ?>
</section>

<div class="modal fade add-directory-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="get">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Utwórz katalog</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="input-newCatalog">Nazwa katalogu</label>
						<input id="input-newCatalog" type="text" class="form-control" name="newCatalog">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Utwórz</button>
				</div>
			</form>
		</div>
	</div>
</div>