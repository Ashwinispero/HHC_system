 <?php 
require_once('config.php');
	

		if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
			$data = json_decode(file_get_contents('php://input'));
            $mobileNumber = $data->mobileNumber;
            $patientname = $data->patientname;
            $datetime = $data->datetime;
            $profname = $data->profname;
            $profmob =$data->profmob;
			
	
				$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
				$txtMsg3='';
 
                    $txtMsg3.= " Dear,$patientname,";
                    $txtMsg3.= " Professional: .$profname.,";
                    $txtMsg3.= " Mob No: ".$profmob." ,";
                    $txtMsg3.= "$datetime.," ;
                    $txtMsg3.= " In case of E-Payments send SMS with Patient Name,NEFT Number,Event ID on 9130031532 \n";
                    $txtMsg3.= " For feedback,service extention or any query call Spero on 7620400100 " ;
					
					//$txtMsg3.= " Dear $patientname,Professional: $profname, Mob No:$profmob,$datetime,
								//In case of E-Payments send SMS with Patient Name,NEFT Number,Event ID on 9130031532
										//For feedback,service extention or any query call Spero on 7620400100";
                    
					echo $txtMsg3;
                    
                    
									$data_to_post['uname'] = 'SperocHL';
									$data_to_post['pass'] = 'SpeRo@12';
									$data_to_post['send'] = 'speroc';
									$data_to_post['dest'] = $mobileNumber; 
									$data_to_post['msg'] = $txtMsg3;
									
									$curl = curl_init();
									curl_setopt($curl,CURLOPT_URL, $form_url);
									curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
								    curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
								    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
									$result = curl_exec($curl);
									curl_close($curl);

                                                        
                     
							
								
		}
		
		else
		{
			http_response_code(405); 
				 
			
		}	 

 ?>
 
 