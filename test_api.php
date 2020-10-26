 <?php $from_date='2020-10-01';
  $to_date='2020-10-25';

  $form_url =  "http://192.168.0.131/API/DropCall.php?startdate=".$from_date."&enddate=".$to_date." ";
    echo $form_url;
   // $data_to_post = array();
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $form_url);
 // curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  //curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
  $result = curl_exec($curl);
  curl_close($curl);
  echo $result;
  ?>