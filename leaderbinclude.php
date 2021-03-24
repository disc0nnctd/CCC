<?php
function OpenCon()
 {
	$dbhost = "sql6.freesqldatabase.com";
	$dbuser = "sql6400897";
	$dbpass = "gcysFbCvd9";
	$db = "sql6400897";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db);
 	return $conn;
 }
 
function CloseCon($conn)
{
    $conn -> close();
} 
 
$conn = OpenCon();
if($conn === false){
  die("ERROR: Could not connect." . $conn->connect_error);
  echo "<br>";
}
function getRating($platform, $user){
	$url = 'https://competitive-coding-api.herokuapp.com/api/'.$platform.'/'.$user;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response_json = curl_exec($ch);
	curl_close($ch);
	$response=json_decode($response_json, true);
	return $response['rating'];
}
$sql1 = "SELECT * FROM persons";  
$result = $conn->query($sql1);
echo '<h2 style="text-align: center; color: white;">Leaderboard</h1>';
echo '<style>
.score {
  cursor: pointer;
}
</style>';
echo '<table id="leader"; style="margin-left: auto; margin-right: auto; margin-top: 50px; position: relative; width: calc(90vw - 2rem); max-width: 800px; border-spacing: 0 1rem; border: 1px solid rgba(255,255,255,0.3); color: white; border-collapse: collapse;">';
echo '<tr style="background-color: rgba(255, 255, 255, 0.3);">';
echo '<th>Name</th>';
echo '<th class="score" onclick="sortTable(1)">Codechef</th>';
echo '<th class="score" onclick="sortTable(2)">Codeforces</th>';
echo '</tr>';
echo '<div style="border: 1px solid rgba(255,255,255,0.3);">';
$ctr = 1;
if ($result) {
  while($row = $result->fetch_assoc()) {
    echo '<tr style= "border: 1px solid rgba(255,255,255,0.3); height: 2rem;">';
    echo '<td style = "padding-left: 12rem; font-size: 1.1rem; letter-spacing: 0.05rem; text-align:left">';
    echo $row["username"]."</td>";
    echo '<td style = "font-size: 0.8rem;">';
    echo getRating('codechef', $row['ccname'])."</td>";
    echo '<td style = "font-size: 0.8rem;">';
    echo getRating('codeforces', $row['cfname'])."</td></tr>";
    $ctr = $ctr + 1;
  }
}
else
  echo"No Data Available";
echo '</div>';
echo '</table>';

echo '<table id="rank"; style="margin-left: auto; margin-right: auto; margin-top: 50px; position: absolute; max-width: 800px; border-spacing: 0 1rem; border: 1px solid rgba(255,255,255,0.3); color: white; border-collapse: collapse;">';
echo '<th id="rank" style="padding-left: 2rem;">';
echo 'Rank</th>';
echo '<div style="border: 1px solid rgba(255,255,255,0.3);">';

$ctr1=1;
while($ctr1!=$ctr){
    echo '<tr><td class="rank" style = "font-weight: 500; width: 10px; padding-left:2px;">';
    echo "$ctr1</td></tr>";
    $ctr1+=1;
}
echo '</div>';
echo '</table>';
echo "<br><br><br>";

echo '<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("leader");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "desc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
sortTable(1);
</script>';

CloseCon($conn);
?>
