<?php
error_reporting(0);
	require 'config.php';
	if(isset($_POST['action']) && $_POST['action'] == 'register'){
		$name=check_input($_POST['name']);
		$uname=check_input($_POST['uname']);
		$email=check_input($_POST['email']);
		$pass=check_input($_POST['pass']);
		$cpass=check_input($_POST['cpass']);
		$pass=sha1($pass);
		$cpass=sha1($cpass);
		$created=date('Y-m-d');

		if($pass!=$cpass){
			echo 'Password did not matched!';
			exit();
		}
		else{
			$sql=$conn->prepare("SELECT username,email FROM users WHERE username=? OR email=?");
			$sql->bind_param("ss",$uname,$email);
			$sql->execute();
			$result=$sql->get_result();
			$row=$result->fetch_array(MYSQLI_ASSOC);

			if($row['username']==$uname){
				echo 'Username not available try different!';
			}
			elseif($row['email']==$email){
				echo 'Email is already registered try different!';
			}
			else{
				$stmt=$conn->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
				$stmt->bind_param("sss",$uname,$email,$pass);
				if($stmt->execute()){

					echo 'Registered Successfully. Login Now!';
				}
				else{
					echo 'Something went wrong. Please try again!';
				}
			}
		}
	}

	if(isset($_POST['action']) && $_POST['action'] == 'login'){
		session_start();

		$username=$_POST['username'];
		$password=sha1($_POST['password']);

		$stmt_l=$conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
		$stmt_l->bind_param("ss",$username,$password);
		$stmt_l->execute();
		$result = $stmt_l->get_result();
		$row = $result->fetch_assoc();
		$user= $row['id'];

		if($user!=null){
			$_SESSION['user_id']=$user;
			echo 'ok';
			if(!empty($_POST['rem'])){
				setcookie("username",$_POST['username'],time()+(10*365*24*60*60));
				setcookie("password",$_POST['password'],time()+(10*365*24*60*60));
			}
			else{
				if(isset($_COOKIE['username'])){
					setcookie("username","");
				}
				if(isset($_COOKIE['password'])){
					setcookie("password","");
				}
			}
		}
		else{
			echo 'Login Failed! Check your username and password';
		}
	}

	if(isset($_POST['action']) && $_POST['action'] == 'forgot'){

		$femail=$_POST['femail'];

		$stmt_p=$conn->prepare("SELECT id FROM users WHERE email=?");
		$stmt_p->bind_param("s",$femail);
		$stmt_p->execute();
		$res=$stmt_p->get_result();

		if($res->num_rows>0){
			$token="qwertyuiopasdfghjklzxcvbnm1234567890";
			$token=str_shuffle($token);
			$token=substr($token, 0,10);

			$stmt_i=$conn->prepare("UPDATE users SET token=?, tokenExpire=DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email=?");
			$stmt_i->bind_param("ss",$token,$femail);
			$stmt_i->execute();

			require 'phpmailer/PHPMailerAutoload.php';
			$mail= new PHPMailer;
			$mail->Host='smtp.mailtrap.io';
			$mail->Port=587;
			$mail->isSMTP();
			$mail->SMTPAuth=true;
			$mail->SMTPSecure='tls';

			$mail->Username='';
			$mail->Password='';

			$mail->addAddress($femail);
			$mail->setFrom('noreply@testing.com','Email');
			$mail->Subject='Reset Password';
			$mail->isHTML(true);

			$mail->Body="<h3>Click the below link to reset your password.</h3><br><a href='http://localhost/photogallery/resetPassword.php?email=$femail&token=$token'>http://localhost/photogallery/resetPassword.php?email=$femail&token=$token</a><br><h3>Regards<br>Team</h3>";

			if($mail->send()){
				echo 'We have send you the reset link in your email ID, please check your email.';
			}
			else{
				echo 'Something went wrong please try again later.';
			}
		}

	}

	function check_input($data){
		$data=trim($data);
		$data=stripslashes($data);
		$data=htmlspecialchars($data);
		return $data;
	}
?>
