<?


$curl = curl_init() ;

curl_setopt ( $curl , CURLOPT_URL , 'https://sms.service.iwinv.kr/send/' ) ;
curl_setopt ( $curl , CURLOPT_TIMEOUT , 0 ) ;
curl_setopt ( $curl , CURLOPT_POST , 1 ) ;
curl_setopt ( $curl , CURLOPT_RETURNTRANSFER , 1 ) ;
curl_setopt ( $curl , CURLOPT_POSTFIELDS , '{ "from":"01022223333" , "to":"01085092864" , "text": "SMS TEST MESSAGE 에요" , "date":"NULL" }' ) ;
curl_setopt ( $curl , CURLOPT_HTTPHEADER ,
	array
	(
		'Content-Type: application/json' ,
		'secret:NjI0RVlHQzBESTdXT1pKRkxITjVFQzQ3NTg4MjkyRTYmMGVlOGMyMDEzNDdmYTMyYjAwMjhmMDJiZWU4MTNiNzUxOWQ5MTkyOTYzNTNlYWIyYjNlYjA5ZTAyZjRhMDAxMA=='
	)
) ;
curl_setopt ( $curl , CURLOPT_SSL_VERIFYPEER , FALSE ) ;

$result = curl_exec ( $curl ) ;
$err = curl_error ( $curl ) ;

curl_close ( $curl ) ;

if ( $err )
	echo 'Error :' . $err ;
else
	echo $result ;
				