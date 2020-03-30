<?php
// Including Common Header
include("header.php");


// Connecting Database
$mysqli = new mysqli($sql_login_host, $sql_login_user, $sql_login_pass, $sql_login_db);

// Taking Album ID from URL

if(isset($_GET['album_id'])){
  $album_id = $_GET['album_id'];


    $user_id = $_SESSION['user_id'];



// Fetching The Album Information From DB
$result_album = $mysqli->query("SELECT * FROM album WHERE id=$album_id");

// Keep the Album data into array
$album = $result_album->fetch_array(MYSQLI_ASSOC);

// Fetching All Photo Under The Album From DB
$result_photo = $mysqli->query("SELECT * FROM photos WHERE album_id=$album_id ");
}


?>
<html>

<head>
	<style>
* {box-sizing: border-box}
.mySlides1, .mySlides2 {display: none}
img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a grey background color */
.prev:hover, .next:hover {
  background-color: #f1f1f1;
  color: black;
}
</style>
</head>
<body>

<div class="slideshow-container">
	<?php
  $upload_dir ="uploads/";
				$sql = "SELECT * FROM photos WHERE album_id=$album_id";
				$result = mysqli_query($mysqli, $sql);
				if(mysqli_num_rows($result)){
					while($row = mysqli_fetch_assoc($result)){
			?>

  <div class="mySlides1">
    <img src="<?php echo $upload_dir.$row['name'] ?>" style="width:100%;height:500px;">
	<div class="text-center">
	<h2><?php echo $row['name'] ?></h2>

						</div>
  </div>
  <a class="prev" onclick="plusSlides(-1, 0)">&#10094;</a>
  <a class="next" onclick="plusSlides(1, 0)">&#10095;</a>

<?php
					}
				}
			?>
			</div>
<script>
var slideIndex = [1,1];
var slideId = ["mySlides1", "mySlides2"]
showSlides(1, 0);
showSlides(1, 1);

function plusSlides(n, no) {
  showSlides(slideIndex[no] += n, no);
}

function showSlides(n, no) {
  var i;
  var x = document.getElementsByClassName(slideId[no]);
  if (n > x.length) {slideIndex[no] = 1}
  if (n < 1) {slideIndex[no] = x.length}
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";
  }
  x[slideIndex[no]-1].style.display = "block";
}
</script>

</body>
</html>
    <div class="row">
      <div class="col-sm-12">


          <h3>All Photos of Album  <?php echo $album['name']; ?></h3>
      </div>
    </div>
    <div class="row photogallery">
     <?php while($row = $result_photo->fetch_array(MYSQLI_ASSOC)) { ?>
      <div class="col-sm-3 col-md-3">
        <img src="uploads/<?php echo $row['name']; ?>" />
      </div>
    <?php } ?>
    </div>


 <?php include("footer.php"); ?>
