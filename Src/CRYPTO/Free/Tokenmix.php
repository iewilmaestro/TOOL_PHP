<?php
const
host = "https://tokenmix.pro/",
register_link = "https://tokenmix.pro/?r=Oo8ycF__Ms",
youtube = "https://youtube.com/@iewil";

function h(){
	$h[] = "user-agent: ".ua();
	$h[] = "cookie: ".simpan("Cookie_Autofaucet");
	return $h;
}

Ban(1);
Cetak("Register",register_link);
print line();
cookie:
simpan("Cookie_Autofaucet");
ua();

print p."Jangan lupa \033[101m\033[1;37m Subscribe! \033[0m youtub saya :D";sleep(2);
//system("termux-open-url ".youtube);
Ban(1);

$r = run(host."user/autofaucet",h());
while(true){
	tmr(60);
	$user = explode('"',explode('t.value="',$r)[1])[0];
	$data = "user=".$user;

	$r = run(host."user/autofaucet",h(),$data);
	if(preg_match('/Cloudflare/',$r)){
		print Error("Cloudflare detect\n");
		print line();
		hapus("Cookie_Autofaucet");
		goto cookie;
	}
	$err=trim(explode('</div>',explode('<div class="AutoACell AAC-error">',$r)[1])[0]);
	if(preg_match('/Insufficient balance to claim rewards/',$r))exit(Error("Insufficient balance to claim rewards\n"));
	
	$coin = explode('</div>',explode('<i class="fas fa-coins"></i>',$r)[1])[0];
	if($coin){
		preg_match('/(-?[1-9]+\\d*([.]\\d+)?)\s(.*)/',$coin,$out);
		print Menu("*",floor($out[1])." ".$out[3]);
	}
	$pay = explode('</a>',explode('<a href="/withdraw" target="_blank">',$r)[1])[0];
	preg_match_all('#<div class="AutoACell AAC-success">(.*?)<a#is',$r,$hasil);
	for($x=0;$x<count($hasil[1]);$x++){
		$has = $hasil[1][$x];
		print Sukses(trim(explode("to",$has)[0]));
	}
	if($coin){
		print line();
	}
}