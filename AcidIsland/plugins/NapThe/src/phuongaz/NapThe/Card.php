<?php

namespace phuongaz\NapThe;

use pocketmine\Player;
use pocketmine\Server;

use onebone\pointapi\PointAPI;

Class Card{

    /** @var array */
    private $data = [];

    public function __construct(array $data){
        $this->data = $data;
    }

    public function sendCard(Player $player){
        $data = NapThe::getSettingConfig();
        $merchant_id = $data["merchant_id"];
        $api_email = $data["email"];
        $secure_code = $data["secure_code"];
        $api_url = "http://api.napthengay.com/v2/";
        $trans_id = time();
        $user = $player->getName();

        $card = $this->data;
        $arrayPost = array(
        "merchant_id"=>intval($merchant_id),
        "api_email"=>trim($api_email),
        "trans_id"=>trim((string)$trans_id),
        "card_id"=>trim((string)$card["ID"]),
        "card_value"=> intval($card["CARD_VALUE"]),
        "pin_field"=>trim($card["PIN"]),
        "seri_field"=>trim($card["SERI"]),
        "algo_mode"=>"hmac");

        $data_sign = hash_hmac("SHA1", implode("",$arrayPost), $secure_code);
        $arrayPost["data_sign"] = $data_sign;

        $curl = curl_init($api_url);
        curl_setopt_array($curl, array(
        CURLOPT_POST=>true,
        CURLOPT_HEADER=>false,
        CURLINFO_HEADER_OUT=>true,
        CURLOPT_TIMEOUT=>120,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS=>http_build_query($arrayPost)));
        $data = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($data, true);
        $time = time();
        if($status==200){
            $amount = $result["amount"];
            switch($amount) {
                case 10000: $coin = 10; break;
                case 20000: $coin = 20; break;
                case 50000: $coin = 50; break;
                case 100000: $coin = 100; break;
                case 200000: $coin = 200; break;
                case 500000: $coin = 500; break;
            }           
            if($result["code"] == 100){
                $file = "carddung.log";
                $fh = fopen($file,"a") or die("cant open file");
                fwrite($fh,"Tai khoan: ".$user.", Loai the: ".$card["NAME"].", Menh gia: ".$amount.", Thoi gian: ".$time);
                fwrite($fh,"\r\n");
                fclose($fh);
                $player->sendMessage("§l§fĐã nạp thành công thẻ với §ftrị giá§e ". $amount ." §f và nhận được§". $coin ."§fpoint");
				PointAPI::getInstance()->addPoint($player->getName(), $coin);
				}
        }else{
            $player->sendMessage("§r•§c Đã xảy ra lỗi trong quá trình xử lí!. Lỗi: ".$error);
        }
    }
}
