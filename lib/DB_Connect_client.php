<?php
// error_reporting(E_ALL^E_NOTICE);
// ini_set('display_errors', 1);





class DB_Connect{
	
	//constructor
	function __construct(){
	}

	//destructor
	function __destuct(){
		// $this->close();
	}



	public function consulting_connect(){
		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "consulting" );

		return $con;
	}





	//Closing database connection
	public function close($con){
		mysqli_close($con);
	}
	
	function array_to_json( $array ){
    	if( !is_array( $array ) ){
        	return false;
    	}

    	$associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    	if( $associative ){
        	$construct = array();
        	foreach( $array as $key => $value ){

            	// We first copy each key/value pair into a staging array,
            	// formatting each key and value properly as we go.

            	// Format the key:
            	if( is_numeric($key) ){
                	$key = "key_$key";
            	}
            	$key = "\"".addslashes($key)."\"";

            	// Format the value:
            	if( is_array( $value )){
                	$value = $this->array_to_json( $value );
            	} else if( !is_numeric( $value ) || is_string( $value ) ){
                	$value = "\"".addslashes($value)."\"";
            	}

            	// Add to staging array:
            	$construct[] = "$key: $value";
        	}

        // Then we collapse the staging array into the JSON form:
        $result = "{" . implode( ", ", $construct ) . "}";

    } else { // If the array is a vector (not associative):

        $construct = array();
        foreach( $array as $value ){

            // Format the value:
            if( is_array( $value )){
                $value = $this->array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "\"".addslashes($value)."\"";
            }

            // Add to staging array:
            $construct[] = $value;
        }

        // Then we collapse the staging array into the JSON form:
        $result = "[" . implode( ", ", $construct ) . "]";
    }

    return $result;
	}
}








date_default_timezone_set('Asia/Seoul');




$db = new DB_Connect();

$dbcon = $db->consulting_connect();



$db_consulting = "consulting";


?>
