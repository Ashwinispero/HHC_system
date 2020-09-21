<?php 	
						$name  = "Chaitali"	;		
						$subject  = "Subject";			
						$email ="sameerpabale7@gmail.com";			
						$body ="testing mail";			
						$to = "sameer.speroinfosystems@gmail.com";						
						$headers  = 'MIME-Version: 1.0' . "\r\n";			
						$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";			
						$headers .= 'From: '.$name.' < '.$email.' >'."\r\n";			
						$headers .= 'Reply-To: '.$name.' < '.$email.' >'."\r\n";									
						mail($to,$subject,$body,$headers);
						?>