<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage {
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
$obj = new main();


class main {

    public function __construct()
    {
        $pageRequest = 'uploadform';
        //check if there are parameters
        if(isset($_REQUEST['page'])) {
        	//load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }
        //instantiate the class that is being requested
        $page = new $pageRequest;


        if($_SERVER['REQUEST_METHOD'] == 'GET') {
        	$page->get();
        } else {
        	$page->post();
        }

    }

}

abstract class page {
    protected $html;

    public function __construct()
    {
        $this->html .= '<html>';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }
    public function __destruct()
    {
        $this->html .= '</body></html>';
        // stringFunctions::printThis($this->html);
    }

    public function get() {
        echo 'default get message';
    }

    public function post() {
        print_r($_POST);
    }
}

class uploadform extends page
{

    public function get()
    {
    	$form  = "<h2>Parsing CSV Files</h2>";
		$form .= "<p><span class=\"error\">* required field.</span></p>";
        $form .= '<form action="index.php?page=uploadform" method="post"
	enctype="multipart/form-data">';
		$form .= "Select CSV file to upload: *<br/><br/>";
        $form .= '<input type="file" name="file" id="file"><br/><br/>';
        $form .= '<input type="submit" value="Upload CSV" name="submit">';
        $form .= '</form> ';
        $this->html .= $form;

        echo $form;

    }

    public function post() {
        $file = new file;
        $url = 'https://web.njit.edu/~sr922/Project-1/index.php?page=parsedCSV&';

        if ( isset($_FILES["file"])) {

	        //if there was an error uploading the file
	        if ($_FILES["file"]["error"] > 0) {
	        	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

	        }
	        else {
	            $file->set_name($_FILES["file"]["name"]);
	            $file->set_type($_FILES["file"]["type"]);
	            $file->set_size($_FILES["file"]["size"]);
	            $file->set_tmp_name($_FILES["file"]["tmp_name"]);
	            
	            if($file->saveFile($url)){
	            	header('Location: '.$url."fileName=". __DIR__ . '/uploads/' . $file->get_name());
	            }
	        }
	     } else {
	        echo "No file selected <br />";
	    }
    }
}

class File { 
    var $name;
	
    /* Setter Functions */
	function set_name($name) {
		$this->name = $name;
	}

	function set_type($type){
		$this->type = $type;
	}

	function set_size($size) {
		$this->size = $size / 1024;
	}

	function set_tmp_name($tmp_name){
		$this->tmp_name = $tmp_name;
	}

	/* Getter Functions */
	function get_name() {
		return $this->name;
	}

	function get_type() {
		return $this->type;
	}

	function get_size() {
		return $this->size;
	}

	function get_tmp_name() {
		return $this->tmp_name;
	}

    
    
    function saveFile($url) { 
        if (file_exists("upload/" . $this->get_name())) {
        	echo $file->get_name() . " already exists. ";
        }
        else {
        	if (move_uploaded_file($this->get_tmp_name(), __DIR__ . '/uploads/' . $this->get_name())) {
			    return true;
			} else {
			   return false;
			}
			
            
        }
    } 
}


class parsedCSV extends page {
	public function get()
	{
    	$fileName = $_GET["fileName"];
    	Parser::parseCSV($fileName);

	}

	public function post(){

	}
}

class Parser {
    public static function parseCSV($fileName) {
    	if ( $file = fopen($fileName , 'r' ) ) {

    		echo "<h2>Parsed CSV File</h2>";

		    $headers = fgets ($file, 4096 );
		    
		    $num_of_fields = strlen($headers) - strlen(str_replace(",", "", $headers));
		    

		    $fields = array();
		    $fields = explode( ",", $headers, ($num_of_fields+1) );

		    $line = array();
		    $i = 0;

		    while ( $line[$i] = fgets ($file, 4096) ) {

		        $data[$i] = array();
		        $data[$i] = explode( ",", $line[$i], ($num_of_fields+1) );

		        $i++;
		    }

		        echo "<table>";
		        echo "<tr>";
		    for ( $k = 0; $k != ($num_of_fields+1); $k++ ) {
		    	$fields[$k] = str_replace("\"", "", $fields[$k]);
		        echo "<td>" . $fields[$k] . "</td>";
		    }
		        echo "</tr>";

		    foreach ($data as $key => $number) {
	    		echo "<tr>";
		        foreach ($number as $k => $content) {
		        	$content = str_replace("\"", "", $content);

		        	echo "<td style=\"border-width: thin; border-style: solid;\">" . $content . "</td>";
		        }
		        echo "</tr>";
		    	
		    }

		    echo "</table>";
		}
    }
}

?>