<?php 	
						$name  = "Chaitali"	;		
						$subject  = "Subject";			
						$email ="chaitali.fartade@mulikainfotech.com";			
						$body ="testing mail";			
						$to = "Ashwinik.speroinfosystems@gmail.com";						
						$headers  = 'MIME-Version: 1.0' . "\r\n";			
						$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";			
						$headers .= 'From: '.$name.' < '.$email.' >'."\r\n";			
						$headers .= 'Reply-To: '.$name.' < '.$email.' >'."\r\n";									
						mail($to,$subject,$body,$headers);
						?>