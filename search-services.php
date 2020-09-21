<?php   require_once 'inc_classes.php';
        require_once "emp_authentication.php";
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/pinterest-style.css" />
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Search Services</title>


</head>
<body>
<style type="text/css">
			
			.free-wall {
				margin: 15px;
			}
			.brick {
				width: 321.2px;
				
			}
			
			
		</style>
<?php include "include/header.php"; ?>
<section>
  <div class="container-fluid">
    <div class="row">
      <!-- Left Start-->
    <div class="col-lg-4 col-left">
        <div id="content-1" class="content mCustomScrollbar">
            <?php include "include_callers.php"; ?>
            <div class="line-seprator"></div>
            
            <h4 class="section-head"><span><img src="images/patient-icon.png" width="29" height="29"></span>PATIENT Details <a href="javascript:void(0);" class="edit-details"><span aria-hidden="true" class="glyphicon glyphicon-pencil pull-right"></span></a></h4>
            <div role="tabpanel"> 
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#existing" aria-controls="home" role="tab" data-toggle="tab">EXISTING</a></li>
                  <li role="presentation"><a href="#new" aria-controls="profile" role="tab" data-toggle="tab">NEW</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="existing">
                    <div class="exPatientListing">
                        <?php include "include_existing_patient.php"; ?>     
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="new">
                    <div class="newPatientListing">
                        <?php include "include_new_patient.php"; ?>   
                    </div>
                </div>
              </div>
                
            </div>
            
        </div>
      </div>
      <!-- Left End-->
      <div class="col-lg-8 col-left-right">
        <div class="col-lg-12">
            <h2 class="page-title">Search Results</h2>
            <div class="row">
            <div id="freewall" class="free-wall">
		    
		    <div class="brick">
            	<div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
                
               <div class="text-right"><input type="submit" class="btn btn-select" value="Select"></div>
            </div>    
		    </div>
            
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, 8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8,  </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8,  8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, 8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8,  8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, 8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8,  8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8,  8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, 8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, 8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, 8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8,</div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
            <div class="brick">
            <div class="search-result">
		        <div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right selectBtn"><input type="submit" class="btn btn-select" value="Select"></div>
		    </div>
            </div>
		</div>
            
           <!-- 
           <div class="col-lg-4 search-box">
           		<div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004</div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right padding10"><input type="submit" class="btn btn-select" value="Select"></div>
           </div> 
           
           <div class="col-lg-4 search-box">
           		<div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004</div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right padding10"><input type="submit" class="btn btn-select" value="Select"></div>
           </div> 
           
           <div class="col-lg-4 search-box">
           		<div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right padding10"><input type="submit" class="btn btn-select" value="Select"></div>
           </div> 
           
           <div class="col-lg-4 search-box">
           		<div class="result-list">
                <label>HHC No:</label>
                <div class="search-text">SPHHCAAA123PN</div>
                </div>
                <div class="result-list">
                <label>Name:</label> 
                <div class="search-text">Prathamesh D. Apte</div>
                </div>
                <div class="result-list">
                <label>Contact No:</label>
                <div class="search-text">+91 923 345 6132</div>
                </div>
                <div class="result-list last">
                <label>Address:</label>
                <div class="search-text">8, Shubhankar Apts. Lane 14, Bhandarkar Road, Erandvane, Pune 411004 8, </div>
               </div>
               	<div class="clearfix"></div>
                <div class="text-right padding10"><input type="submit" class="btn btn-select" value="Select"></div>
           </div> 
           -->
           </div> 
        </div>
      </div>
    </div>
  </div>
</section>

<!-- For Tiels-->

<?php  include "include/scripts.php"; ?>

<script type="text/javascript" src="js/freewall.js"></script>
<!-- <script type="text/javascript">

			var wall = new Freewall("#freewall");
			wall.reset({
				selector: '.brick',
				animate: true,
				cellW: 300,
				cellH: 'auto',
				onResize: function() {
					wall.fitWidth();
				}
			});
			
			wall.container.find('.brick').load(function() {
				wall.fitWidth();
			});


		</script> -->
<script type="text/javascript">
		$(document).ready(function() {
			var wall = new Freewall("#freewall");
			wall.fitWidth();
			ResizeWindow();
			
			});
			
			function ResizeWindow()
			{
				
				var wall = new Freewall("#freewall");
				//alert(wall);
			wall.reset({
				selector: '.brick',
				animate: true,
				cellW: 300,
				cellH: 'auto',
				onResize: function() {
					wall.fitWidth();
				}
			});
			}
		</script>

</body>
</html>
