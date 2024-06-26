<?php
const
register_link = "https://faucetclick.site/?r=866",
host = "https://faucetclick.site/",
youtube = "https://youtube.com/@iewil";

function h($host = 0){
	if(!$host)
	$h[] = "Host: faucetclick.site";
	$h[] = "cookie: ".simpan("Cookie");
	$h[] = "user-agent: ".ua();
	return $h;
}

function dash(){
	$r = curl(host."dashboard",h())[1];
	$user = explode('</div>',explode('<div class=font-medium>',$r)[1])[0];
	$bal = explode('</div>',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[1])[0];
	$en = explode('</div>',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[3])[0];
	return ["user"=>$user,"balance"=>$bal,"energy"=>$en];
}
function center($str){
	$len = 50;
	$strlen = strlen($str);
	$lenstr = $len - $strlen;
	$pinggir = floor($lenstr/2);
	print str_repeat(" ",$pinggir).k.strtoupper($str).str_repeat(" ",$pinggir).n;
}
function rata($str,$garis = null){
	$len = 16;
	$strlen = strlen($str);
	$lenstr = $len - $strlen;
	return h.$str.str_repeat(" ",$lenstr).u.$garis;
}
function Colum($a,$b,$c=0){
	return rata($a,"|").rata($b,"|").rata($c).n;
}
function autofaucet(){
	while(true){
		$r = curl(host."auto",h())[1];
		$cost = explode('</div>',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[2])[0];
		if(dash()["energy"] < $cost)break;
		$token = explode('>',explode('name=token value=',$r)[1])[0];
		$tmr = explode(",",explode("let timer=",$r)[1])[0];
		if($tmr){tmr($tmr);}
		$data= "token=".$token;
		$r = curl(host."auto/verify",h(),$data)[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			print Sukses($ss);
			$r = dash();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
	print Error("You Need Energy\n");
	print line();
}
function game($game,$id){
	while(true){
		$r = curl(host."games/play/".$game,h())[1];
		$score = explode(";",explode("var required_score=",$r)[1])[0];
		$csrf = explode("';",explode("var csrf='",$r)[1])[0];
		$data = "score=".$score."&csrf=".$csrf;
		$arr = ["x-requested-with: XMLHttpRequest"];
		tmr(10);
		$res = curl(host."games/verify?id=".$id,array_merge($arr,h()),$data)[1];
		if(explode('"status":"success"',$res)[1]){
			print Sukses(explode(",",explode('"',explode('"message":"',$res)[1])[0])[0]);
			$r = dash();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
		if(explode('"status":"error"',$res)[1]){
			Cetak("Game",$game);
			print Error(explode('"',explode('"message":"',$res)[1])[0].n);
			print line();
			break;
		}
	}
}
function convert($harga, $saldo){
	return sprintf('%.8f',floatval($saldo/$harga));
}
function saldo(){
	$r = curl(host."/withdraw".$game,h())[1];
	$prov = explode('<input type="hidden" name="method" value="',$r);
	$val = explode('"',explode('value="',$prov[1])[1])[0];
	print k.str_pad("CONVERT BALANCE TO CRYPTO",50," ",STR_PAD_BOTH)."\n";
	foreach ($prov as $anjeng => $data){
		if($anjeng==0)continue;
		$title = explode('"',$data)[0];
		$coins = explode('<option value="',$data);
		print bp.str_pad(strtoupper($title),50," ",STR_PAD_BOTH).d."\n";
		foreach($coins as $asu => $coin){
			if($asu==0)continue;
			$coinx = explode(')',$coin)[0];
			$crypto = explode('">',$coin)[0];
			$ket = explode('</b>',explode('<b>',$coin)[1])[0];
			print h.$asu."- ";
			if(strlen($ket)>5){
				print k.$ket.n;
			}
			//$crypto = strtoupper(preg_replace("/[^a-zA-Z]/","", explode('=',$coinx)[0]));
			$fli = preg_replace("/\D+/","", explode('=',$coinx)[1]);
			if(!$fli)continue;
			$hasil = convert($fli, $val);
			print "\t".p.$hasil." ".$crypto.n;
		}
	}
}
function leaderboard(){
	$r = curl(host.'leaderboard',h())[1];
	$title = explode('<h4 class="text-lg mb-4">',$r);
	foreach($title as $a => $judul){
		if($a == 0)continue;
		$host = explode('</h4>',$judul)[0];
		center($host);
		print line();
		print Colum("Nickname","Score","Reward");
		print line();
		$nic = explode('<td class="username-rank"><span>',$judul);
		foreach($nic as $b => $nick){
			if($b == 0)continue;
			$nickname = explode('<a',$nick)[0];
			if(strlen($nickname)>14){
				$nickname = substr($a,0,14);
			}
			$claim = explode('</td>',explode("<td>",$nick)[1])[0];
			$reward = explode('</td>',explode("<td>",$nick)[2])[0];
			print Colum($nickname,$claim,$reward);
		}
		print line();
	}
}
function acivement(){
	$r = curl(host."achievements",h())[1];
	$list = explode('<div class="text-1xl font-medium leading-8">Daily Achievement</div>',$r);
	foreach($list as $a => $aciv){
		if($a == 0)continue;
		$url = explode('"',explode('<form action="',$aciv)[1])[0];
		$csrf = explode('>',explode('name=csrf_token_name value=',$aciv)[1])[0];
		$uncomplet = explode('Uncompleted',$aciv)[1];
		$task = explode('</div>',explode('<div class="text-1xl font-medium leading-8 mt-6">Task: ',$aciv)[1])[0];
		if($uncomplet)continue;
		$data = "csrf_token_name=".$csrf;
		$r = curl($url,h(),$data)[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			print Sukses($task);
			print Sukses($ss);
			$r = dash();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
}
function faucet(){
	while(true){
		$r = curl(host.'claim',h())[1];
		$_sisa = explode('<',explode('<div class="text-3xl font-medium leading-8 mt-6">',$r)[4])[0];
		$sisa = explode('/',$_sisa)[0];
		if($sisa < 1)break;
		$tmr = explode('-',explode('var wait = ',$r)[1])[0];//299 - 1;
		if($tmr){
			Tmr($tmr);continue;
		}
		
		$data['csrf_token_name'] = explode('>',explode('id=token value=',$r)[1])[0];
		$data['token'] = explode('>',explode('name=token value=',$r)[1])[0];
		$data['captcha'] = 'recaptchav2';
		$data['g-recaptcha-response'] = '';
		$r = curl(host.'claim/verify',h(),http_build_query($data))[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			Cetak("Claim",$_sisa);
			print Sukses($ss);
			$r = dash();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
	print Error("Faucet has finished\n");
	print line();
}
function ads(){
	while(true){
		$r = curl(host.'ads',h())[1];
		$id = explode("'",explode('ads/view/',$r)[1])[0];
		if(!$id)break;
		$r = curl(host.'ads/view/'.$id,h())[1];
		$tmr = explode(';',explode('var timer=',$r)[1])[0];
		$csrf = explode('>',explode('_token_name value=',$r)[1])[0];
		$token = explode('>',explode('name=token value=',$r)[1])[0];
		
		$data = "csrf_token_name=$csrf&token=$token";
		$arr = ["x-requested-with: XMLHttpRequest"];
		$r = json_decode(curl(host.'ads/antibot',array_merge($arr,h()),$data)[1],1);
		if($r['status'] == 'success'){
			tmr($tmr);
			$token = $r['token'];
			$data = "captcha=recaptchav2&g-recaptcha-response=&csrf_token_name=$csrf&token=$token";
			$r = curl(host.'ads/verify/'.$id,h(),$data)[1];
			$ss = explode("`",explode("html: `",$r)[1])[0];
			if($ss){
				print Sukses($ss);
				$r = dash();
				Cetak("Balance",$r["balance"]);
				Cetak("Energy",$r["energy"]);
				print line();
			}
		}
	}
	print Error("Ads has finished\n");
	print line();
}
function article(){
	while(true){
		$r = curl(host."articles",h())[1];
		$id = explode("'",explode('article/view/',$r)[1])[0];;
		if(!$id)break;
		
		$r = curl(host.'article/view/'.$id,h())[1];
		$csrf = explode('>',explode('_token_name value=',$r)[1])[0];
		$token = explode('>',explode('name=token value=',$r)[1])[0];
		$slug = explode('>',explode('name=slug value=',$r)[1])[0];
		
		$data = "csrf_token_name=$csrf&token=$token&slug=$slug";
		$r = curl(host.'articles/antibot',h(),$data)[0];
		$loc = trim(explode(n, explode('q=',explode('location:', $r)[1])[1])[0]);
		$arr = ['referer: https://www.google.com/'];
		$r = curl(urldecode($loc),array_merge($arr,h()))[1];
		$tmr = explode(';',explode('let timer=',$r)[1])[0];
		if($tmr)tmr($tmr);
		$csrf = explode('>',explode('_token_name value=',$r)[1])[0];
		$final = explode('"',explode('<form action="',$r)[1])[0];
		$data = "csrf_token_name=$csrf";
		$r = curl($final,h(),$data)[1];
		$ss = explode("`",explode("html: `",$r)[1])[0];
		if($ss){
			print Sukses($ss);
			$r = dash();
			Cetak("Balance",$r["balance"]);
			Cetak("Energy",$r["energy"]);
			print line();
		}
	}
	print Error("Article has finished\n");
	print line();
}
Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Cookie");
ua();

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
system("termux-open-url ".youtube);Ban(1);

$r = dash();
if(!$r["user"]){
	print Error("Cookie Expired!\n");
	hapus("Cookie");
	goto cookie;
}
Cetak("Username",$r["user"]);
Cetak("Balance",$r["balance"]);
Cetak("Energy",$r["energy"]);
print line();

while(true){
	$r = dash();
	if(!$r["user"]){
		print Error("Cookie Expired!\n");
		hapus("Cookie");
		goto cookie;
	}
	
	game("2048-lite","1");
	game("pacman-lite","2");
	game("hextris-lite","3");
	game("taptaptap","4");
	
	ads();
	article();
	faucet();
	acivement();
	autofaucet();
	tmr(600);
}

/*
menu:
Menu(1, "Game"); 
Menu(2, "Faucet"); 
Menu(3, "Visit Ptc");
Menu(4, "Auto Faucet");
Menu(5, "Read Article");
Menu(6, "Achievements");
//Game
$pil = readline(Isi("Nomor"));
print line();
if($pil==1){
	game("2048-lite","1");
	game("pacman-lite","2");
	game("hextris-lite","3");
	game("taptaptap","4");
	goto menu;
}elseif($pil==2){
	faucet();
	goto menu;
}elseif($pil==3){
	ads();
	goto menu;
}elseif($pil==4){
	autofaucet();
	goto menu;
}elseif($pil==5){
	article();
	goto menu;
}elseif($pil==6){
	acivement();
	goto menu;
}else{echo Error("Bad Number\n");print line();goto menu;}
//shortlink
//offerwall
//mining
leaderboard();
saldo();
*/