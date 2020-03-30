
<?php

	$db=mysqli_connect("localhost","root","","photoalbum");
					/* server name, username, passwor, database name */
					include("header.php");

				
?>


 					<?php

 				$q=mysqli_query($db,"SELECT * FROM users where id='$_SESSION[user_id]' ;");
 			?>
 			<h2 style="text-align: center;">My Profile</h2>

 			<?php
 				$row=mysqli_fetch_assoc($q);


 			?>
 			<div style="text-align: center;">
	 			<h4>
	 				<?php 	echo $row['username']; ?>
	 			</h4>
 			</div>
 			<?php
 				echo "<b>";
 				echo "<table class='table table-bordered'>";
	 				echo "<tr>";
	 					echo "<td>";
	 						echo "<b> ID: </b>";
	 					echo "</td>";

	 					echo "<td>";
	 						echo $row['id'];
	 					echo "</td>";
	 				echo "</tr>";


	 				echo "<tr>";
	 					echo "<td>";
	 						echo "<b> User Name: </b>";
	 					echo "</td>";
	 					echo "<td>";
	 						echo $row['username'];
	 					echo "</td>";
	 				echo "</tr>";

	 				echo "<tr>";
	 					echo "<td>";
	 						echo "<b> Password: </b>";
	 					echo "</td>";
	 					echo "<td>";
	 						echo $row['password'];
	 					echo "</td>";
	 				echo "</tr>";

	 				echo "<tr>";
	 					echo "<td>";
	 						echo "<b> Email: </b>";
	 					echo "</td>";
	 					echo "<td>";
	 						echo $row['email'];
	 					echo "</td>";
	 				echo "</tr>";
					echo "<tr>";
						echo "<td>";
							echo "<b> Account Created: </b>";
						echo "</td>";
						echo "<td>";
							echo $row['created'];
						echo "</td>";
					echo "</tr>";




 				echo "</table>";
 				echo "</b>";
 			?>
 		</div>
 	</div>
 </body>
 </html>
