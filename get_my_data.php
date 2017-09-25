<?PHP
session_start();
$file=$_SESSION['id'].".json";
$p=array();
if(!file_exists($file)){ // initial login. Create json file
	new_player($p,$_POST['uname']);
	file_put_contents($file,json_encode($p));
}
else { // session continue
	$p=json_decode(file_get_contents($file));
	update($file,'last_act',date('Y-m-d H:i:s')); // update last_act
}
if($p->creator==false) do_admin(); // remove any old json files
header("Content-Type: application/json; charset=UTF-8");
echo file_get_contents($file);

function new_player(&$p,$uname){
	$p['uname']=$uname;
	$p['status']='idle';
	$p['last_act']=date('Y-m-d H:i:s');
	$p['creator']=false;
	$p['enemy']='';
	$p['num']='';
	$p['my_turn']=false;
	$p['h1']=0;
	$p['h2']=0;
	$p['h3']=0;
}

function update($file,$fd,$val=null){
	$p=array();
	$p=json_decode(file_get_contents($file));
	$p->$fd=$val;
	file_put_contents($file,json_encode($p));
}

function do_admin(){
	$dir=".";
	$handler = opendir($dir); 
	while ($file = readdir($handler)) { 
		$ext = substr(strrchr($file,"."),1); 
		$ext = strtolower($ext); 
		if ($ext=="json") {
			$p=array();
			$p=json_decode(file_get_contents($file));
			if(strtotime(date('Y-m-d H:i:s'))-strtotime($p->last_act) >30)  // remove old file
				unlink($file); 
        }
	} 
	closedir($handler); 
}
?>
