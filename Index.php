<?php
// PROBLEM
// pri vseh datotekah, kjer uporabljam klic na ez_sql_mysql.php javi napako:
// Warning: in C:\wamp\www\SHOP\skripte\ez_sql_mysql.php on line 256
// #	Time	Memory	Function	           Location
// 1	0.0004	152280	{main}( )	           ..\Index.php:0
// 2	0.0016	263096	ezSQL_mysql->query( )  ..\Index.php:5
// 3	0.0038	273656	trigger_error ( )	   ..\ez_sql_mysql.php:256

require_once('skripte/ez_sql_core.php');
require_once('skripte/ez_sql_mysql.php');	
$db = new ezSQL_mysql('root','','trgovina','localhost');
$db->query("SET NAMES UTF 8");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
<!--PROBLEM
	Šumniki vseeno ne delajo! -->
    <meta charset="UTF 8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>
  
<body>
<h1><em>Trg o</em> Vina</h1>
<p> Na voljo so izdelki, prosim izberi:<br><br>

<form method="post">
Uredi izdelke po:
<input type="radio" name="sortiraj" value="cena DESC">Ceni padajoce
<input type="radio" name="sortiraj" value="cena ASC">Ceni narascajoce
<input type="radio" name="sortiraj" value="zvrst">Sorti
<input type="radio" name="sortiraj" value="id" checked>Stevilki
<input type="radio" name="sortiraj" value="akcija DESC">Izdelki v akciji
<input type="submit" name="submit_sort"></input><br>
</form>
	
<?php
//glede na izbor sortiranja ...
if (isset($_POST['sortiraj'])){ $sortiraj=$_POST['sortiraj'];
//... pobere podatke iz baze 
$podatki = $db->get_results("SELECT * FROM ponudba ORDER BY $sortiraj");
}
//... oz. pobere 'default' 
else {$podatki = $db->get_results("SELECT * FROM ponudba");}
//Prešteje št vnosov v bazo
$result=mysql_query("SELECT count(*) as total FROM ponudba");
$data=mysql_fetch_assoc($result);

// PROBLEM
// to deluje ampak ne razumem čisto dobro kaj se je zgodilo razen da je sedaj v arrayu "namesto Objektov Array" ;-))
// Prej je javljalo napako "... Cannot use object of type stdClass as array ..."
foreach($podatki as $pod) {
    $trgovina[] = [
	   'id'      => $pod->id,
       'izdelek' => $pod->izdelek,
	   'zvrst'   => $pod->zvrst,
       'cena'    => $pod->cena,
	   'akcija'  => $pod->akcija,
       'zaloga'  => $pod->zaloga,
	   'slika'   => $pod->slika

    ];
}
//če količina ni bila vnešena jo postavi na 0
for ($i=0; $i<$data['total']; $i++){
	if (isset($_POST['kolicina'.$i])) {$kolicina[$i]=$_POST['kolicina'.$i];} else {$kolicina[$i]=0;}
}
//izpis izdelkov
	?><form method="post"><?php
	for ($izd=0; $izd<$data['total']; $izd++) {
		?>
			<div class="row-fluid">
			  <div class="col-sm-2">
			    <?php if ($trgovina[$izd]['akcija']==1) {$color='red';} else {$color='black';}?>
				<div class="thumbnail" style="border-color:<?=$color ?>">
				  <!--<img src="images/wine0<?=$izd ?>.jpg" alt="..." style="width: 30%"> od prej ko se ni bral iz baze-->
				  <?php if ($trgovina[$izd]['slika']==null) {
							?><img src="images/ni_slike.jpg" alt="..." style="width: 30%"> <?php
							}
						else {
				  			?><img src="<?= $trgovina[$izd]['slika']?>" alt="..." style="width: 30%"> <?php
							}
				  ?>
				  <div class="caption">
				  <table class="table table-striped">
					<tr>
						<td>No.:</td>
						<td><?php echo $trgovina[$izd]['id'];?></td>
					</tr>
					<tr style="height:70px">
						<td>Naziv:</td>
						<td><h3><?php echo $trgovina[$izd]['izdelek'];?></h3></td>
					</tr>
					<tr>
						<td>Sorta:</td>
						<td><?php echo $trgovina[$izd]['zvrst']; ?></td>
					</tr>
					<tr>
						<td>Cena:</td>
						<td><?php echo $trgovina[$izd]['cena']; ?></td>
					</tr>
					<tr>
						<td>Zaloga:</td>
						<td><?php echo $trgovina[$izd]['zaloga']; ?></td>
					</tr>
					<tr>
						<td>KUPI:</td>
						<td><input name="kolicina<?=$izd?>" type="number" min="0" max="<?=$trgovina[$izd]['zaloga'] ?>" step="1" value ="0"/></td>
					</tr>
					</table>
				  </div>
				</div>
			  </div>
			</div>
		<?php				
	}
	?>
<!--SUBMIT KNOF-->
	<div class="col-sm-12">
	<input type="submit" class="btn btn-primary" value="KUPI ZDAJ!" name="submit"></input>
	ali
   <button type="reset" class="btn btn-info" data-target="#nov_izdelek" data-toggle="modal">Vpisi nov izdelek</button>
	</form>

	<?php
//sešteje količino vseh nakupov če je bil pritisnjen submit
if (isset($_POST['submit'])) {$ninakupa=0;
	for ($i=0; $i<$data['total']; $i++) {$ninakupa=$ninakupa+$kolicina[$i];}
//če je količina večja od 0 izpiše nakup
	if ($ninakupa>0) {
	echo "<br><br>Pravkar ste kupili ".$ninakupa." steklenic vina, od tega:<br>";
	for ($i=0; $i<$data['total']; $i++)  {
//tisti nakupi, ki so prazni jih preskoči
			if (!$kolicina[$i]==0)  {echo $kolicina[$i]." kom ".$trgovina[$i]['izdelek']."<br>";
//odšteje količino od zaloge in vpiše v bazo									 
			$trgovina[$i]['zaloga']=$trgovina[$i]['zaloga']-$kolicina[$i];
			$nova= $trgovina[$i]['zaloga'];						
			$db->query("UPDATE ponudba SET zaloga = '$nova' WHERE id=($i+1)");
									}	
										 }
	echo "<br>HVALA!<br>";
	?> <a href="#" data-toggle="modal" data-target="#racun">klikni za račun</a> <?php
	}
//če je količina enaka 0 ni bilo nakupa
	else {echo "<br>Prosim izberite količino!";}
		}
	if (isset($_POST["kom{$izd}"])) {$kolicina[$izd]=$_POST["kom{$izd}"]; }
	?>
	</div>

<!--RACUN-->
 <div class="modal fade" id="racun">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">RAČUN</h4>
      </div>
      <div class="modal-body">
        <p>
        <table class="table table-striped">
        	<tr>
				<td><b>Izdelek</td>
				<td><b>Količina</td>
				<td><b>Cena za kom.</td>
				<td><b>Cena za kol.</td>
				<td><b>DDV</td>
				<td><b>Cena z DDV</td>
			</tr>
        <?php
			$DDV=0; $SKUPAJ=0;
			for ($i=0; $i<$data['total']; $i++) { 
			if ($kolicina[$i]>0) {
			?>
			<tr>
				<td><?php echo $trgovina[$i]['izdelek']; ?> </td>
				<td><?php echo $kolicina[$i]; ?> </td>
				<td><?php echo $trgovina[$i]['cena']; ?> </td>
				<td><?php echo ($trgovina[$i]['cena'])*$kolicina[$i]; ?> </td>
				<td><?php echo ($trgovina[$i]['cena'])*$kolicina[$i]*0.22; ?> </td>
				<td><?php echo ($trgovina[$i]['cena'])*$kolicina[$i]*1.22; ?> </td>
				<?php   $DDV=$DDV+(($trgovina[$i]['cena'])*$kolicina[$i]*0.22);
						$SKUPAJ=$SKUPAJ+(($trgovina[$i]['cena'])*$kolicina[$i]*1.22);
				?>
			</tr>
								<?php }	}
        ?>
       		 <tr>
				<td><b>SKUPAJ</td>
				<td><b></td>
				<td><b></td>
				<td><b></td>
				<td><b><?=$DDV?></td>
				<td><b><?=$SKUPAJ?></td>
			</tr>
        </table>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zapri</button>
        <button type="button" class="btn btn-primary" onclick="window.print();">Natisni</button>
      </div>
    </div>
  </div>
</div>

<!--VPIS NOVEGA IZDELKA-->
 <div class="modal fade" id="nov_izdelek">
  <div class="modal-dialog">
    <div class="modal-content">
    <form method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">VPIŠI NOV IZDELEK</h4>
      </div>
      <div class="modal-body">
        <p>
        <table class="table table-striped">
        	<tr>
				<td><b>Ime vina:</td>
				<td><b><input name="new_izdelek" type="text"/></td>
			</tr>
    	    <tr>
				<td><b>Sorta:</td>
				<td><b><select name="new_zvrst"><option value="Sauvignon">Sauvignon</option><option value="Chardonnay">Chardonnay</option><option value="Syrah">Syrah</option></select></td>
			</tr>
    	    <tr>
				<td><b>Cena:</td>
				<td><b><input name="new_cena" type="number" min="0.01" max="99" step="0.01" value ="0"/></td>
		 		</tr>
    	    <tr>
				<td><b>Zaloga:</td>
				<td><b><input name="new_zaloga" type="number" min="1" max="10000" step="1" value ="1"/></td>
			</tr>
    	    <tr>
				<td><b>Ali bo vino v akciji:</td>
				<td><b><select name="new_akcija"><option value="1">DA</option><option value="0">NE</option></select></td>
			</tr>
        </table>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Preklici</button>
        <input type="submit" class="btn btn-primary" value="VPIŠI" name="submit_new"></input>
	  </div>
	</form>
    <?php
	if (isset($_POST['submit_new'])){
		$new_izdelek=$_POST['new_izdelek']; $new_zvrst=$_POST['new_zvrst']; $new_cena=$_POST['new_cena']; $new_zaloga=$_POST['new_zaloga']; $new_akcija=$_POST['new_akcija']; 
		$db->query("INSERT INTO ponudba (izdelek, zvrst, cena, zaloga, akcija) VALUES ('$new_izdelek', '$new_zvrst', '$new_cena', '$new_zaloga', '$new_akcija')");
			}
	?>
    </div>
  </div>
</div>
</p>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>