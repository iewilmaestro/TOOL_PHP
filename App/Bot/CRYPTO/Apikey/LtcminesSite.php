<?php
const
register_link = "https://ltcmines.site/earns/doge/?r=18jswG2t9EZrnHju5dyiYw1yGbkcrTSgJg",
host = "https://ltcmines.site/",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/c/iewil",
r = "/?r=purna.iera@gmail.com";

function h($ref=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h = [
	"Host: ".$host[1],
	"origin: ".host,
	"content-type: application/x-www-form-urlencoded",
	"user-agent: ".ua(),
	"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
	"accept-language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"];
	if($ref){
		$h = array_merge($h,["referer: ".$ref]);
	}
	return $h;
}
function claim($api, $coin, $email){
	while(true){
		$r = curl("https://ltcmines.site/earns/".$coin.r,h(),'',1)[1];
		if(preg_match('/You have to wait/',$r)){
			tmr(300);
		}
		$sesion = explode('"',explode('<input type="hidden" name="session-token" value="',$r)[1])[0];
		$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if(!$sitekey){
			print Error("sitekey error!");
			sleep(6);
			print "\r                         \r";
			continue;
		}
		if(explode('\"',explode('rel=\"',$r)[1])[0]){
			$atb = $api->AntiBot($r);
			if(!$atb){print Error("Atb @".provider_api." Error\n"); continue;}
			$cap = $api->RecaptchaV2($sitekey, "https://ltcmines.site/earns/".$coin.r);
			if(!$cap)continue;
			$data = "session-token=".$sesion."&address=".urlencode($email)."&antibotlinks=".$atb."&captcha=recaptcha&g-recaptcha-response=".$cap."&login=Verify+Captcha";
		}else{
			$cap = $api->RecaptchaV2($sitekey, "https://ltcmines.site/earns/".$coin.r);
			if(!$cap)continue;
			$data = "session-token=".$sesion."&address=".urlencode($email)."&captcha=recaptcha&g-recaptcha-response=".$cap."&login=Verify+Captcha";
		}
		
		$r = curl("https://ltcmines.site/earns/".$coin.r,h("https://ltcmines.site/earns/".$coin.r),$data,1)[1];
		$ss = explode('<',explode('<i class="fas fa-money-bill-wave"></i> ',$r)[1])[0];
		$wr = explode('</div>',explode('<div class="alert alert-danger">',$r)[1])[0];
		if(preg_match('/does not have sufficient/',$r)){
			exit(Error("The faucet does not have sufficient funds for this transaction.\n"));
		}
		if(preg_match('/Your daily claim limit/',$r)){
			exit(Error("Your daily claim limit\n"));
		}
		if($ss){
			print Sukses(trim($ss)." Faucetpay!");
			Cetak("Bal_Api",$api->getBalance());
			print line();
		}elseif($wr){
			print Error($wr.n);
			print line();
		}else{
			continue;
			print $r;exit;
		}
		tmr(300);
	}
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
$email = simpan("Email_Faucetpay");
if(!ua())print "\n".line();

if(!$cek_api_input){
	$apikey = MenuApi();
	if(provider_api == "Multibot"){
		$api = New ApiMultibot($apikey);
	}else{
		$api = New ApiXevil($apikey);
	}
	$cek_api_input = 1;
}

Ban(1);
print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);
hapus("cookie.txt");

menu:
$r = Curl(host.'earns',h())[1];
$con = explode('<div class="col-lg-4 col-sm-6">',$r);
foreach($con as $a=> $coins){
	if($a == 0)continue;
	$coin = explode('"',explode('/earns/',$coins)[2])[0];
	$coinx[$a] = $coin;
	print Menu($a,strtoupper($coin));
}
print Menu($a+=1,'All Coin');
$pil = readline(Isi('Input Number'));
Ban(1);
Cetak("Wallet",$email);
if($pil < $a){
	print Cetak("Coin",strtoupper($coinx[$pil]));
	print line();
	claim($api, $coinx[$pil], $email);
}elseif($pil == $a){
	print Cetak("Coin","All Coin");
	print line();
	goto allcoin;
}else{
	print Error("Bad Number Selected!\n");
	print line();
	goto menu;
}
exit;

allcoin:
while(true){
	foreach($coinx as $coin){
		if($res){
			if($res[$coin] > 2)continue;
		}
		$r = curl("https://ltcmines.site/earns/".$coin.r,h(),'',1)[1];
		if(preg_match('/You have to wait/',$r)){
			$res = his([$coin=>1],$res);
			continue;
		}
		$sesion = explode('"',explode('<input type="hidden" name="session-token" value="',$r)[1])[0];
		$sitekey = explode('"',explode('<div class="g-recaptcha" data-sitekey="',$r)[1])[0];
		if(!$sitekey){
			print Error("sitekey error!");
			sleep(6);
			print "\r                         \r";
			continue;
		}
		if(explode('\"',explode('rel=\"',$r)[1])[0]){
			$atb = $api->AntiBot($r);
			if(!$atb)continue;
			$cap = $api->RecaptchaV2($sitekey, "https://ltcmines.site/earns/".$coin.r);
			if(!$cap)continue;
			$data = "session-token=".$sesion."&address=".urlencode($email)."&antibotlinks=".$atb."&captcha=recaptcha&g-recaptcha-response=".$cap."&login=Verify+Captcha";
		}else{
			$cap = $api->RecaptchaV2($sitekey, "https://ltcmines.site/earns/".$coin.r);
			if(!$cap)continue;
			$data = "session-token=".$sesion."&address=".urlencode($email)."&captcha=recaptcha&g-recaptcha-response=".$cap."&login=Verify+Captcha";
		}
		$r = curl("https://ltcmines.site/earns/".$coin.r,h("https://ltcmines.site/earns/".$coin.r),$data,1)[1];
		
		$ss = explode('<',explode('<i class="fas fa-money-bill-wave"></i> ',$r)[1])[0];
		$wr = explode('</div>',explode('<div class="alert alert-danger">',$r)[1])[0];
		if(preg_match('/does not have sufficient/',$r)){
			print c.strtoupper($coin).": ".Error("The faucet does not have sufficient funds\n");
			$res = his([$coin=>3],$res);
			print line();
			continue;
		}
		if(preg_match('/Your daily claim limit/',$r)){
			print c.strtoupper($coin).": ".Error("Your daily claim limit\n");
			$res = his([$coin=>3],$res);
			print line();
			continue;
		}
		if($ss){
			print Sukses($coin.": ".trim($ss)." Faucetpay!");
			Cetak("Bal_Api",$api->getBalance());
			print line();
			$res = his([$coin=>1],$res);
		}elseif($wr){
			$wr = explode('</div>',explode('<div class="alert alert-danger">',$r)[1])[0];
			print c.strtoupper($coin).": ".Error($wr.n);
			print line();
			$res = his([$coin=>1],$res);
		}else{
			continue;
			print_r($r);exit;
		}
	}
	if(min($res) > 2)break;
}