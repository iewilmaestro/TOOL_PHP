<?php
const
register_link = "https://liteonion.online/?r=595",
host = "https://liteonion.online/",
youtube = "https://youtube.com/c/iewil",
r = "?r=purna.iera@gmail.com";

function h($ref=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h = [
	"Host: ".$host[1],
	"origin: ".host,
	"content-type: application/x-www-form-urlencoded",
	"user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36",
	"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
	"accept-language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"];
	if($ref){
		$h = array_merge($h,["referer: ".$ref]);
	}
	return $h;
}

Ban(1);
Cetak("Register",register_link);
print line();
cookie:
$email = simpan("Email_Faucetpay");
ua();
print line();

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

if(!file_exists("Data/".nama_file."/cookie.txt")){
	loginagain:
	$r = curl(register_link,h(),'',1)[1];
	//$r = curl(host,h(),'',1)[1];
	$csrf = explode('">',explode('<input type="hidden" name="csrf_token_name" id="token" value="',$r)[1])[0];
	$data = [
		"wallet" => simpan("Email_Faucetpay"),
		"csrf_token_name" => $csrf
	];
	
	$r = curl(host."auth/login",h(),http_build_query($data),1)[1];
	$ss = explode("',",explode("html: '",$r)[1])[0];
	if($ss){
		Cetak("INFO",$ss);print line();sleep(5);
		Ban(1);
	}else{
		print Error("Sepertinya akun di banned\n");
		hapus("cookie.txt");
		goto loginagain;
	}
}

Ban(1);
$r = curl(host,h(),'',1)[1];
if(!explode('Logout',$r)[1]){
	hapus("cookie.txt");
	goto loginagain;
}
Cetak("Wallet",simpan("Email_Faucetpay"));
print line();

$r = Curl(host,h(),'',1)[1];
$con = explode("faucet/currency/",$r);

while(true){
	foreach($con as $a => $coin){
		if($a == 0)continue;
		$coint = explode('"', $coin)[0];
		$r = curl(host."faucet/currency/".$coint,h(),'',1)[1];
		if(preg_match('/Please confirm your email address to be able to claim or withdraw/',$r)){
			print Error("Please confirm your email address to be able to claim or withdraw\n");
			print line();
			exit;
		}
		if($res && count($res) == count($con)-1){
			$check = $res[$coint];
			if($check < 5)continue;
		}
		if(preg_match('/Daily claim limit/',$r)){
			$res = his([$coint=>1],$res);
			Cetak($coint,"Daily claim limit");
			continue;
		}
		$status_bal = explode('</span>',explode('<span class="badge badge-danger">',$r)[1])[0];
		if($status_bal == "Empty"){
			$res = his([$coint=>1],$res);
			Cetak($coint,"Sufficient funds");
			continue;
		}
		$cek = GlobalCheck($r);
		$data = [];
		$data = Parsing($r);
		if($cek['cf']){
			print Error("Cloudflare\n");
			hapus("Cookie");
			sleep(3);
			print line();
			goto cookie;
		}
		if($cek['fw']){
			$data['g-recaptcha-response'] = "";
			curl(host."firewall/verify",h(),http_build_query($data),1)[1];
			continue;
		}
		if(preg_match('/You have to wait/',$r)){
			$res = his([$coint=>6],$res);
		}
		
		$data['captcha'] = "recaptchav2";
		$data['g-recaptcha-response'] = "";
		
		$r = curl(host."faucet/verify/".$coint,h(),http_build_query($data),1)[1];
		$ss = explode("account!'",explode("html: '0.",$r)[1])[0];
		$wr = explode("'",explode("html: '",$r)[1])[0];
		$ban = explode('</div>',explode('<div class="alert text-center alert-danger"><i class="fas fa-exclamation-circle"></i> Your account',$r)[1])[0];
		if(preg_match('/does not have sufficient/',$r)){
			print c.strtoupper($coint).": ".Error(" The faucet does not have sufficient funds\n");
			$res = his([$coint=>3],$res);
			print line();
		}
		if($ss){
			print Sukses("0.".str_replace("has been sent ","",$ss));
			print line();
			$res = his([$coint=>10],$res);
		}elseif($wr){
			print Error(substr($wr,0,30));
			$res = his([$coint=>6],$res);
			sleep(3);
			print "\r                  \r";
		}elseif($ban){
		    exit(Error("Your account".$ban.n));
		}else{
			continue;
			print_r($r);exit;
		}
		
	}
	tmr(120);
	if(max($res) < 5)break;
}