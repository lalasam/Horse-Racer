<!DOCTYPE html>
<html>
<head>
<script>
function loadMyData() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	document.getElementById("demo").innerHTML=this.responseText;
		var jobj=JSON.parse(this.responseText);
		document.getElementById("demo").innerHTML=""; //clear text
		for(var i in jobj){
		  document.getElementById("demo").innerHTML+= i+":"+jobj[i]+"<br>";
		}
    }
  };
  xhttp.open("POST", "get_my_data.php", true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("uname=<?=$_POST['uname']?>");
}

function logout(){
	document.form1.operation.value='LOGOUT';
	document.form1.submit();
}

function check_login(f){
	if(f.uname.value=='') {
		alert("Enter User name!");
		f.uname.getfocus();
		return false
	}
	f.operation.value='LOGIN';
	return true;
}

</script>
</head>

<body>
	<form name='form1' method='POST' onsubmit='return check_login(this);'>
	<input type='hidden' name='operation'>
	<h2>Welcome to MIU IT Horse Racer!</h2>
<?
session_start();
if($_POST['operation']=='LOGIN'){
	$_SESSION['id']=rand(1,1000000);
}
else if($_POST['operation']=='LOGOUT'){
	session_destroy();
	$_SESSION['id']=null;
}
if(!$_SESSION['id']) { // user login required
	print_user_login();	
}
else { // user logged in already
	print_get_my_data();
	print_polling();
}
?>
	
	</form>	
 </body>
</html>

<?
	function print_get_my_data(){
		echo "
			<button type='button' onclick='loadMyData()'>Get My Data</button>
			<button type='button' onclick='logout()'>Logout</button>
			<div id='demo'></div>
		";
	}
	
	function print_user_login(){
		echo "User Name: ";
		echo "<input type=text name='uname'> ";		
		echo "<input type=submit> ";		
		echo "<input type=reset>";		
	}
	
	function print_polling(){
		echo "<script>
			window.onload=function(){
				var game_handle=setInterval(loadMyData,1000); // plling
			}
			</script>
		";
	}

?>
