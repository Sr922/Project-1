<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

if ( isset($_POST["submit"]) ) {

   if ( isset($_FILES["file"])) {

        //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
        	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
            //Print file details
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                 //if file already exists
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
            	echo $_FILES["file"]["name"] . " already exists. ";
            }
            else {
            	//Store file in directory "upload" with the name of "uploaded_file.txt"
	            $storagename = "uploaded_file.txt";
	            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
	            echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
            }
        }
     } else {
        echo "No file selected <br />";
     }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


class File { 
    public $path = ''; 
    
    
    function saveFile() { 
        print 'Inside `saveFile()`'; 
    } 

    function parseFile() {

    }
} 

$file = new file; 
?>

<h2>Parsing CSV Files</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">  
	Select CSV file to upload: *<br/><br/>
    <input type="file" name="file" id="file"><br/><br/>
    <input type="submit" value="Upload CSV" name="submit">
  
</form>

</body>
</html>