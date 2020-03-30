<?php

include("header.php");



$mysqli = new mysqli($sql_login_host, $sql_login_user, $sql_login_pass, $sql_login_db);

// Taking Album ID from URL

if(isset($_GET['album_id'])){
  $album_id = $_GET['album_id'];
if(isset($_POST['delete_album']))
{
  
    $mysqli->query("DELETE FROM album WHERE id=$album_id");

    // Redirecting to Homepage
    Header("Location: index.php");
}
if(isset($_POST['publish_album']))
{
    // Running Delete Query
    $mysqli->query("UPDATE album SET published=1 WHERE id=$album_id");

    // Redirecting to Homepage
    Header("Location: index.php");
}


// When Update the Album Name
if(isset($_POST['album_name']))
{
    // Taking Form Data
    $album_name = $_POST['album_name'];

    // Running Update Query
    $mysqli->query("UPDATE album SET name='$album_name' WHERE id=$album_id");
}

// When New Photo Uploaded
if(isset($_FILES['photo']))
{
    // Name of the Upload Folder
    $uploaddir = 'uploads/';

    // Name of the Uploaded Photo
    $name = $_FILES['photo']['name'];

    // The Final Path Where the Photo will be Stored
    $uploadfile = $uploaddir . $name;


    // Taking User ID from SESSION
    $user_id = $_SESSION['user_id'];

    // If The Upload is Successfull
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {

        // Inserting Data into Database
        $mysqli->query("INSERT INTO photos VALUES(null,'$name',$album_id,$user_id,0)");
        $success = "Successfully Uploaded.";

    }
    else {
        $error = "Upload Failed! Please Try Again.";
    }

}

// Fetching The Album Information From DB
$result_album = $mysqli->query("SELECT * FROM album WHERE id=$album_id");

// Keep the Album data into array
$album = $result_album->fetch_array(MYSQLI_ASSOC);

// Fetching All Photo Under The Album From DB
$result_photo = $mysqli->query("SELECT * FROM photos WHERE album_id=$album_id ");
}
$upload_dir = 'uploads/';
if(isset($_GET['delete'])){
  $id = $_GET['delete'];

  //select old photo name from database
  $sql = "select id from photos where id = ".$id;
  $result = mysqli_query($mysqli, $sql);
  if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $photo = $row['name'];
    unlink($upload_dir.$photo);
    //delete record from database
    $sql = "delete from photos where id=".$id;
    if(mysqli_query($mysqli, $sql)){
      header('location:index.php');
    }
  }
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
    <a class="btn btn-danger" href="view.php?delete=<?php echo $row['id'] ?>" onclick="return confirm('Are you sure to delete this record?')">
      <span class="glyphicon glyphicon-remove-circle"></span>Delete this photo
    </a>
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

            <form method="post">
                   <input type="text" name="album_name" value="<?php echo $album['name']; ?>" />

                   <button type="submit" class="btn btn-success">Update Album name</button>

                      <button name="publish_album" type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-remove-circle"></span>Publish This Album To Others</button>
  <button name="delete_album" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle"></span>Delete This Album</button>
            </form>


          <button class="btn btn-warning" data-toggle="modal" data-target="#myModal">Upload New Photo</button>
          <h3>All Photos Of <?php echo $album['name']; ?></h3>
      </div>
    </div>
    <div class="row photogallery">
     <?php while($row = $result_photo->fetch_array(MYSQLI_ASSOC)) { ?>
      <div class="col-sm-3 col-md-3">
        <img src="uploads/<?php echo $row['name']; ?>" />
      </div>
    <?php } ?>
    </div>

<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Upload New Photo</h4>
          <?php if(isset($success)){ ?>
                <h3 class="text-success"><?php echo $success; ?></h3>
          <?php } ?>
          <?php if(isset($error)){ ?>
                <h3 class="text-danger"><?php echo $error; ?></h3>
          <?php } ?>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-group">
            <input name="photo" type="file" class="form-control" />
              <button type="submit" class="btn btn-primary">Upload</button>
          </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 <?php include("footer.php"); ?>
