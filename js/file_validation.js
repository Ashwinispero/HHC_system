function chk_file_validation(file_type,file_size,file_ext,file_format)
{
	if (window.File && window.FileReader && window.FileList && window.Blob)
	{
	   var msg="";
	   var ftype = file_type;
	   var fsize = file_size;
	   var fext = file_ext;
	   var fformat = file_format;
	   var fsize_mb = (((5242880)/1024)/1024);
	   
	   if(fsize > 5242880 ) // do something if file size greater than 5 mb (5242880)
	   {
		  msg="Your "+ftype+" couldn't be uploaded. "+ftype+" should be less than "+fsize_mb+" MB and saved as "+fformat+" files";	  
	   }
	  
	   else if($.inArray(fext,fformat) === -1) 
	   {
		  msg ="Your "+ftype+" couldn't be uploaded. "+ftype+" should be less than "+fsize_mb+" MB and saved as "+fformat+" files";
		       
	   }
	   else
	   {
		 msg="success";
	   }
	   
	   return msg;
	}   
 }