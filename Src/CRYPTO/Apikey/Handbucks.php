<?php
const
host = "https://handbucks.com/",
register_link = "https://handbucks.com/?ref=anjim128",
typeCaptcha = "hcaptcha",
youtube = "https://youtube.com/@iewil";

function h($data=0){
	preg_match('@^(?:https://)?([^/]+)@i',host,$host);
	$h[] = "Host: ".$host[1];
	if($data)$h[] = "Content-Length: ".strlen($data);
	$h[] = "User-Agent: ".ua();
	$h[] = "Cookie: ".simpan("Cookie");
	return $h;
}

function dash(){
	$r = curl(host.'dashboard',h())[1];
	$data['user'] = explode('</h3>',explode('<h3>Welcome ',$r)[1])[0];
	$data['bal'] = explode('</strong>',explode('<strong>Balance: ',$r)[1])[0];//$0.0003
	return $data;
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
if(!Simpan("Cookie"))print "\n".line();
if(!ua())print "\n".line();

$apikey = MenuApi();
if(provider_api == "Multibot"){
	$api = New ApiMultibot($apikey);
}else{
	$api = New ApiXevil($apikey);
}

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = dash();
if(!$r["user"]){
	print Error("Session expired".n);
	hapus("Cookie");
	sleep(3);
	print line();
	goto cookie;
}

Cetak("Email",$r["user"]);
Cetak("Balance",$r["bal"]);
Cetak("Bal_Api",$api->getBalance());
print line();

while(1){
	$r = curl(host.'views/iframe', h())[1];
	$id = explode('"', explode('href="/views/iframe/', $r)[1])[0];
	if(!$id)break;
	$r = curl(host.'views/iframe/'.$id, h())[1];
	$tmr = explode(';',explode('seconds = ',explode('var timer =',$r)[1])[1])[0];
	if($tmr){
		tmr($tmr);
	}
	$r = curl(host.'views/iframe/'.$id, h(), http_build_query(Parsing($r)))[1];
	Cetak("Id_Ads",$id);
	Cetak("Balance",dash()["bal"]);
	print line();
}
print Error("You have watched all ads".n);
print Line();

while(1){
	$r = curl(host.'faucet',h())[1];
	$cek = GlobalCheck($r);
	if($cek['cf']){
		print Error("Cloudflare\n");
		hapus("Cookie");
		sleep(3);
		print line();
		goto cookie;
	}
	if($cek['fw']){
		exit(Error("Firewall\n"));
	}
	$tmr = explode(';',explode('seconds = ',explode('var timer =',$r)[1])[1])[0];
	if($tmr){
		tmr($tmr);
		continue;
	}
	$sitekey = explode('>',explode('<div class="h-captcha" data-sitekey=',$r)[1])[0];
	if(!$sitekey){
		print Error("Sitekey Error");
        sleep(5);continue;
	}
	$cap = $api->Hcaptcha($sitekey, host.'faucet');
	if(!$cap){
		print Error("@".provider_api." Error\n"); 
		print line();
		continue;
	}
	$data = [];
	$data = Parsing($r);
	$data['g-recaptcha-response'] = $cap;
	$data['h-captcha-response'] = $cap;
	
	$r = curl(host.'faucet',h(),http_build_query($data))[1];
	$ss = explode('"',explode('text: "You claimed',$r)[1])[0];
	if (preg_match('/Invalid captcha', $r)) {
		print Error('Invalid captcha');
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}else
	if($ss){
		print Sukses($ss);
		Cetak("Balance",dash()["bal"]);
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}else{
		continue;
	}
}