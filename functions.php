<?php
session_start();

// require_once 'vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';
// connect to database
$db = mysqli_connect('localhost', 'root', 'root', 'coursework', "3308");

// variable declaration
$name = "";
$email    = "";
$errors   = array();


if (isset($_POST['register_btn'])) {
	register();
}

// REGISTER USER
function register(){

	global $db, $errors, $name, $email;

	$name   		 =  e($_POST['name']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	if (empty($name)) {
		array_push($errors, "Name is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	if (count($errors) == 0) {
		$password = md5($password_1); //encrypt the password

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (name, email, user_type, password)
					  VALUES('$name', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO users (name, email, user_type, password)
					  VALUES('$name', '$email', 'user', '$password')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			// put logged in user in session
			$_SESSION['user'] = getUserById($logged_in_user_id);
			$_SESSION['success']  = "You are now logged in";
			header('location: index.php');
		}
	}
}

// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}

// log user out if logout button clicked
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $email, $errors;

	$email = e($_POST['email']);
	$password = e($_POST['password']);

	if (empty($email)) {
		array_push($errors, "email is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM users
		 					WHERE email='$email'
		  				AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found

			$logged_in_user = mysqli_fetch_assoc($results);
	    $_SESSION['user'] = $logged_in_user;
	  	$_SESSION['success']  = "You are now logged in";
			header('location: index.php');

		}else {
			array_push($errors, "Wrong email/password combination");
		}
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}

function isStudent()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'student' ) {
		return true;
	}else{
		return false;
	}
}

if (isset($_POST['batch'])) {
	batch();
}

function batch(){
  	global $db;

  $target_path = "files/.";
  $target_path = $target_path.basename( $_FILES['fileToUpload']['name']);

  if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) {
      echo "File uploaded successfully!";
  } else{
      echo "Sorry, file not uploaded, please try again!";
  }

  $h = fopen($target_path, "r");
  while (($data = fgetcsv($h, 1000, ",")) !== FALSE)
  {
    $name       =   e($data[0]);
    $email      =   e($data[1]);
    $user_type  =   e($data[2]);
    $password   =   e($data[3]);

    $password = md5($password);

    $query = "INSERT INTO users (name, email, user_type, password)
          VALUES('$name', '$email', '$user_type', '$password')";

    $result = mysqli_query($db, $query);
    header('location: index.php');
  }
  fclose($h);
}

function load_mentors($name, $hasEmptyOption){
	global $db;

	$query = "SELECT name FROM users WHERE user_type = 'mentor'";
	$result = mysqli_query($db, $query);

	echo "<select name='". $name . "'>";
	if($hasEmptyOption){
		echo "<option value=''</option>";
	}
	while ($row = mysqli_fetch_array($result)) {
			echo "<option value='"
					. $row['name'] . "'>". $row['name']
					."</option>";
	}
	echo "</select>";
}

if (isset($_POST['make_application'])) {
	make_application();
}

function make_application(){
	$names =  e($_POST['names']);
	$grade =  e($_POST['grade']);

	$topic1 = e($_POST['topic1']);
	$mentor1 = e($_POST['mentor1']);

	$topic2 = empty($_POST['topic2']) ? "" : e($_POST['topic2']);
	$mentor2 = empty($_POST['mentor2']) ? "" : e($_POST['mentor2']);

	$topic3 = empty($_POST['topic3']) ? "" : e($_POST['topic3']);
	$mentor3 = empty($_POST['mentor3']) ? "" : e($_POST['mentor3']);

	$avr_grade = e($_POST['avr-grade']);

	$templateProcessor =
		new \PhpOffice\PhpWord\TemplateProcessor(
			'template_files/Заявление.docx'
	);
	$templateProcessor->setValue('names', $names);
	$templateProcessor->setValue('grade', $grade);
	$templateProcessor->setValue('avg_grade', $avr_grade);
	$templateProcessor->setValue('date', date("d-m-Y"));

	$templateProcessor->setValue('topic1', $topic1);
	$templateProcessor->setValue('mentor1', $mentor1);

	$templateProcessor->setValue('topic2', $topic2);
	$templateProcessor->setValue('mentor2', $mentor2);

	$templateProcessor->setValue('topic3', $topic3);
	$templateProcessor->setValue('mentor3', $mentor3);


	$filename = 'Заявение ' . $names . '.docx';
	$templateProcessor->saveAs($filename);
	downloadFile($filename);
}

function downloadFile($filename){
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename="
			.basename($filename));
		header("Content-Description: File Transfer");
		@readfile($filename);
}

if (isset($_POST['make_assignment'])) {
	make_assignment();
}

function make_assignment(){
	$names =  e($_POST['names']);
	$grade =  e($_POST['grade']);

	$topic = e($_POST['topic']);
	$mentor = e($_POST['mentor']);

	$requirements = "";
	for($i=0; $i<$_POST['count']; $i++){
		$requirements .= "2." . ($i+1) . ". "
			.  $_POST['requirement' . $i] .'<w:br/>';
	}

	$templateProcessor =
		new \PhpOffice\PhpWord\TemplateProcessor(
			'template_files/Задание.docx'
	);

	$templateProcessor->setValue('names', $names);
	$templateProcessor->setValue('grade', $grade);
	$templateProcessor->setValue('topic', $topic);
	$templateProcessor->setValue('mentor', $mentor);
	$templateProcessor->setValue('requirements', $requirements);

	$filename = 'Задание ' . $names . '.docx';
	$templateProcessor->saveAs($filename);
	downloadFile($filename);
}
