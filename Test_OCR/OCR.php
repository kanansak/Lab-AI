<html>
<body>
<form action="up.php" method="post" enctype="multipart/form-data">
Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
</form>
<!-- <img src="curr.jpg"> -->
<?php
$menu = "";
$imgName = "curr.jpg";
if(isset($_GET['imgName'])) {
	global $imgName;
    $imgName = $_GET['imgName'];
}
echo '<img src="'.$imgName.'">';
echo "<br>";
//T-Food
$curl = curl_init();
$img_file = $imgName;
$data = array("uploadfile" => new CURLFile($img_file, mime_content_type($img_file), basename($img_file)));
 
	
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.aiforthai.in.th/ocr",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $data,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: multipart/form-data",
    "apikey: etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
  )
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
  $arr = json_decode($response,true);
  //print_r($arr);
  echo "<br>";
  array_walk_recursive($arr, function ($item, $key) {
    //echo "$key holds $item"."<br>";
	if( $key == "result" ){ 
		global $menu;
		$menu = $item; 
	}
  });
  $Lexto = $response;
}

//ระบบตัดคําภาษาไทยเล็กซ์โต (LexTo+)

$curl = curl_init();
 
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.aiforthai.in.th/tlexplus",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "text=$Lexto",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/x-www-form-urlencoded",
    "Apikey: etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
    
  )
));
 
$Lexto = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $arr = json_decode($Lexto); // convert string to array

  foreach($arr as $x => $val) {
      echo "$x : <br>";
      foreach($val as $y => $val2){
          echo "$y : $val2<br>";
      }
  }
}
?>
</body>
</html>