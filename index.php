<?php
include('functions.php');
if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>

	<div class="header">
		<h2>Home Page</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php
						echo $_SESSION['success'];
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>
		<!-- logged in user information -->
		<div class="profile_info">
			<div>
					<strong><?php echo $_SESSION['user']['name']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
						<br>
						<a href="index.php?logout='1'" style="color: red;">logout</a>
					</small>
          <?php if (isAdmin()) : ?>
                   &nbsp; <a href="create_user.php"> + add user(s)</a>
          <?php endif ?>
           <?php if (isStudent()) : ?>
             <br><br>
                     &nbsp; <a href="make_assignment.php"> Make your assignment</a><br>
										 &nbsp; <a href="make_application.php"> Make your application</a>
            <?php endif ?>

			</div>
		</div>
	</div>
</body>
</html>
