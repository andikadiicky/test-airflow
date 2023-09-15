<style type="text/css">
	.font {
		font-size: 9px;
	}
</style>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<div>
		<label>
			Directory :
		</label>
		<input type="text" name="dir" required="">
		<input type="submit" name="get_directory">
	</div>
</form>
<table border="1">
	<tr>
		<th>Root Folder</th>
		<th>Nama File + Extension</th>
		<th>Versi GIT</th>
		<!-- <th>Comment</th> -->
		<th>PIC Dev</th>
		<th>Date</th>
	</tr>
	<?php
	@$dir = $_POST['dir'];
	$dir = str_replace('\'', '/', $dir);
	// echo $dir;
	if (isset($dir)) {
		ini_set('max_execution_time', 300);
		$file = array();
		$count = 0;
		chdir($dir);
		exec("git ls-files", $file);
		$detail = array();
		if (count($file) > 0) {
			header("Content-type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename=object_git.xls");
			$versi = "";
			$cmt = "";
			$pic = "";
			$date = "";
			foreach ($file as $line) {
				exec("git log -1 " . $line, $detail);
				foreach ($detail as $history) {
					if (strpos($history, 'commit ') === 0) {
						$cmt = '';
						$versi = trim(substr($history, strlen('commit '), 40));
					} else if (strpos($history, 'Author: ') === 0) {
						$pic = trim(substr($history, strlen('Author: ')));
					} else if (strpos($history, '    ') === 0) {
						$cmt = $cmt . ' ' . trim(substr($history, strlen('    ')));
					} else if (strpos($history, 'Date:   ') === 0) {
						$date = trim(substr($history, strlen('Date:   ')));
					}
				}
				echo "<tr>";
				echo "<td class='font'>" .substr($line, 0, strripos($line, '/') + 1). "</td>" . "<td class='font'>" . substr($line, strripos($line, '/') + 1) . "</td><td class='font'>" . "'" . $versi . "</td><td class='font'>" . $pic . "</td><td class='font'>" . $date . "</td>";
				echo "</tr>";
			}
		}
	}
	?>
</table>