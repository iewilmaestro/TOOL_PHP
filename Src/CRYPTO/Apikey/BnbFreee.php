<?php
const
host = "https://api.bnbfreee.com/",
register_link = "https://bnbfreee.com?r=0x00f3D58C2657c4c4b0F838C97d3879F9139fe5C2",
typeCaptcha = "RecaptchaV2",
youtube = "https://youtube.com/@iewil";

function h($data=0,$au=0){
	$h[] = "Host: api.bnbfreee.com";
	$h[] = "Content-Type: application/json";
	if($data)$h[] = "Content-Length: ".strlen($data);;
	$h[] = "Referer: https://bnbfreee.com/";
	$h[] = "User-Agent: ".ua();
	if($au)$h[] = "Authorization: ".$au;
	return $h;
}
function login($api, $email, $password){
	$cap = $api->RecaptchaV2("6LcK--IdAAAAAEmWdU_-U5Dm578lJNwAtmPzqqRT", "https://bnbfreee.com/lucky");
	$data = '{"email":"'.$email.'","password":"'.$password.'","recaptha":"'.$cap.'"}';
	return json_decode(curl("https://api.bnbfreee.com/users/login",h($data),$data,1)[1],1);
}
Ban(1);
cookie:
Cetak("Register",register_link);
print line();
$email = Simpan("Email");
$password = Simpan("Password");
if(!ua())print "\n".line();

print line();
$apikey = MenuApi();
if(provider_api == "Multibot"){
	$api = New ApiMultibot($apikey);
}else{
	$api = New ApiXevil($apikey);
}

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
Ban(1);

login:
if(!file_exists("Data/".nama_file."/Autorization")){
	$r = login($api, $email, $password);
	if($r['statusCode'] == 200){
		file_put_contents("Data/".nama_file."/Autorization", "Bearer ".$r['access_token']);
	}else{
		Print Error($r['message'].n);
		hapus("Email");
		hapus("Password");
		print line();
		goto cookie;
	}
}
$akses_token = file_get_contents("Data/".nama_file."/Autorization");

$r = json_decode(curl(host."users/profile",h('',$akses_token),'',1)[1],1);
if($r['statusCode'] == 200){
	Cetak("Email",$r["user"]["email"]);
	Cetak("Address",substr($r["user"]["address_wallet"],0,17)."xxx");
	Cetak("Balance",sprintf('%.8f',floatval($r["user"]['money']))." BNB");
	Cetak("Bal_Api",$api->getBalance());
	print line();
}else{
	print Error("Akses token Expired!\n");
	sleep(3);
	hapus("Autorization");
	Ban(1);
	goto login;
}

while(true){
	$cap = $api->RecaptchaV2("6LcK--IdAAAAAEmWdU_-U5Dm578lJNwAtmPzqqRT", "https://bnbfreee.com/lucky");
	$data = '{"recaptha":"'.$cap.'"}';
	$r = json_decode(curl("https://api.bnbfreee.com/lucky",h($data,$akses_token),$data,1)[1],1);
	if($r['status'] == "success"){
		Cetak("Number",$r['lucky']['number']);
		Cetak("You Win",sprintf('%.8f',floatval($r['lucky']['money']))." BNB");
		$r = json_decode(curl(host."users/profile",h('',$akses_token),'',1)[1],1);
		Cetak("Balance",sprintf('%.8f',floatval($r["user"]['money']))." BNB");
		Cetak("Bal_Api",$api->getBalance());
		print line();
	}else{
		Print Error(explode(',',$r['message'])[0].n);
		print line();
		if($r['message'] == 'Unauthorized'){
		hapus("Autorization");
		goto login;
		}
	}
	tmr(3600);
}



