<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<body>

<p>Please upload a CSV file.</p>

<form method="post" action="batch.php" enctype="multipart/form-data">
  <?php echo display_error(); ?>
  <input type="file" id="myFile" name="fileToUpload">
	<button type="submit" class="btn" name="batch">Batch register</button>
</form>

</body>
</html>
