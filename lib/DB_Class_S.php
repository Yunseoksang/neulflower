<?php



class DB_S_Connect{
	
	//constructor
	function __construct(){
	}

	//destructor
	function __destuct(){
		// $this->close();
	}


	public function connect(){

		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "s_sangjo" );

		return $con;
	}


	public function admin_connect(){
		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "s_admin" );

		return $con;
	}


	public function fullfillment_connect(){
		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "s_fullfillment" );

		return $con;
	}

	public function client_connect(){
		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "s_client" );

		return $con;
	}

	public function consulting_connect(){
		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "s_consulting" );

		return $con;
	}

	public function flower_connect(){
		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "s_flower" );

		return $con;
	}

	public function statistics_connect(){
		$con = mysqli_connect('alwaysflower.sldb.iwinv.net' , 'root' , 'r42kTwGYuF97') or die("Failed to connect database"); 		
		mysqli_select_db($con, "s_statistics" );

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








if(isset($_COOKIE['admin_info'])){
	$admin_info = json_decode($_COOKIE['admin_info'], true);

	if((isset($admin_info['admin_mode']) && $admin_info['admin_mode'] == "smart") || $_POST['mode'] == "smart"){ 
		$db = new DB_S_Connect();
		//print("DB_S_Connect");
	
		$db_connect_option = "SMART FACTORY";

	}else{
		$db = new DB_Connect();
		$db_connect_option = "BASIC";
	
	}



}else if($_POST['mode'] == "smart"){

			$db = new DB_S_Connect();
			//print("DB_S_Connect");
		
			$db_connect_option = "SMART FACTORY";
	
}else{
	$db = new DB_Connect();
	$db_connect_option = "BASIC";

}







?>