<?php 

$wing1A=101;
$wing1B=102;
$wing1C=103;
$wing1D=104;
$wing1E=105;
$wing1F=106;

$wing2A=201;
$wing2B=202;
$wing2C=203;
$wing2D=204;
$wing2E=205;
$wing2F=206;

$wing3A=301;
$wing3B=302;
$wing3C=303;
$wing3D=304;
$wing3E=305;
$wing3F=306;

$wing4A=401;
$wing4B=402;
$wing4C=403;
$wing4D=404;
$wing4E=405;
$wing4F=406;

$wing5A=501;
$wing5B=502;
$wing5C=503;
$wing5D=504;
$wing5E=505;
$wing5F=506;

?>

<div id="Assisted_Living_booking">
<table class="table table-bordered" >
					  <tr style="Font-weight:bold" >
						<th style="background-color:white;width:15%;" ></td>
						<td style="background-color:white;width:15%;" align="center">1</td>
						<td style="background-color:white;width:15%;" align="center">2</td>
						<td style="background-color:white;width:15%;" align="center">3</td>
						<td style="background-color:white;width:15%;" align="center">4</td>
						<td style="background-color:white;width:15%;" align="center">5</td>
						<td style="background-color:white;width:15%;" align="center">6</td>
					  </tr>
					  <tr align="center" style="Font-weight:bold;background-color:white;">
						<td align="left" style="Font-weight:bold">First Floor</td>
						<td>
							<input style="background-color:gray" type="button" value="  101  " onclick="Booking_popup(<?php echo $wing1A; ?>);" disabled></input>
							<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='101' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
							<input style="background-color:gray" type="button" value="  102  " onclick="Booking_popup(<?php echo $wing1B; ?>);" disabled></input>
						    <?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='102' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
							<input style="background-color:#00cfcb" type="button" value="  103  " onclick="Booking_popup(<?php echo $wing1C; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='103' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  104  " onclick="Booking_popup(<?php echo $wing1D; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='104' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  105  " onclick="Booking_popup(<?php echo $wing1E; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='105' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  106  " onclick="Booking_popup(<?php echo $wing1F; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='106' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
					  </tr>
					  <tr align="center" style="Font-weight:bold;background-color:white;">
						<td align="left" style="Font-weight:bold">Second Floor</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  201  " onclick="Booking_popup(<?php echo $wing2A; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='201' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  202  " onclick="Booking_popup(<?php echo $wing2B; ?>);" disabled></input>
						
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='202' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  203  " onclick="Booking_popup(<?php echo $wing2C; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='203' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  204  " onclick="Booking_popup(<?php echo $wing2D; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='204' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  205  " onclick="Booking_popup(<?php echo $wing2E; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='205' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  206  " onclick="Booking_popup(<?php echo $wing2F; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='206' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
					  </tr>
					  <tr align="center" style="Font-weight:bold;background-color:white;">
						<td align="left" style="Font-weight:bold">Third Floor</td>
						<td>
						<input style="background-color:gray" type="button" value="  301  " onclick="Booking_popup(<?php echo $wing3A; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='301' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  302  " onclick="Booking_popup(<?php echo $wing3B; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='302' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  303  " onclick="Booking_popup(<?php echo $wing3C; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='303' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  304  " onclick="Booking_popup(<?php echo $wing3D; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='304' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  305  " onclick="Booking_popup(<?php echo $wing3E; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='305' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  306  " onclick="Booking_popup(<?php echo $wing3F; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='306' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
					  </tr>
					  <tr align="center" style="Font-weight:bold;background-color:white;">
						<td align="left" style="Font-weight:bold">Forth Floor</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  401  " onclick="Booking_popup(<?php echo $wing4A; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='401' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:#00cfcb" type="button" value="  402  " onclick="Booking_popup(<?php echo $wing4B; ?>);"></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='402' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  403  " onclick="Booking_popup(<?php echo $wing4C; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='403' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  404  " onclick="Booking_popup(<?php echo $wing4D; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='404' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  405  " onclick="Booking_popup(<?php echo $wing4E; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='405' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  406  " onclick="Booking_popup(<?php echo $wing4F; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='406' AND status='1' ");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
					  </tr>
					  <tr align="center" style="Font-weight:bold;background-color:white;">
						<td align="left" style="Font-weight:bold">Fifth Floor</td>
						<td>
						<input style="background-color:gray" type="button" value="  501  " onclick="Booking_popup(<?php echo $wing5A; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='501' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  502  " onclick="Booking_popup(<?php echo $wing5B; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='502' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  503  " onclick="Booking_popup(<?php echo $wing5C; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='503' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  504  " onclick="Booking_popup(<?php echo $wing5D; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='504' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  505  " onclick="Booking_popup(<?php echo $wing5E; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='505' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
						<td>
						<input style="background-color:gray" type="button" value="  506  " onclick="Booking_popup(<?php echo $wing5F; ?>);" disabled></input>
						<?php 
							$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='506' AND status='1'");
							$count=mysql_num_rows($query1);
							$available= 4 - $count;
							if($count==4)
							{
							?>
							<div style="color:red">Booked : <?php echo $count ; ?></div>
							<div style="color:red">Available : <?php echo $available ; ?></div>
							<?php							
							}
							else
							{
							?>	
							<div style="color:green">Booked : <?php echo $count ; ?></div>
							<div style="color:green">Available : <?php echo $available ; ?></div>
							<?php 
							}
							?>
						</td>
					  </tr>
				</table>
</div>

<div id="overlay_display">
  <div id="popupwindow_display">
  <div id="Assisted_View_booking_patient">
  
	
	
  
  </div>
</div>
</div>