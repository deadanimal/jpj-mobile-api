<?php

namespace App\Http\Controllers;

use App\Models\Cawangan;
use App\Models\KumpulanPerkhidmatan;
use App\Models\LogTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartQv2Controller extends Controller
{
    public function getTicketNo(Request $request)
    {
        $contents = file_get_contents("php://input");

        $id_kumpulan_perkhidmatan = $request->id_kumpulan_perkhidmatan;
        $cawangan = $request->cawangan;
        $player_id = $request->player_id;
        $tarikh = $request->tarikh;
        $token = $request->token;

        $mytoken = $cawangan . $player_id . $tarikh . "jpjit2020" . $id_kumpulan_perkhidmatan;
        $mytoken = hash("sha256", $mytoken);
        $token = strtoupper($token);
        $mytoken = strtoupper($mytoken);

        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ?? isset($_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = $ip ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

        $device = $_SERVER['HTTP_USER_AGENT'];
        $trkh = date("Y-m-d H:i:s");
        $direction = "1";
        $module = "getTicketNo";

        LogTransaksi::create([
            'ip' => $ip, 'device' => $device, 'tarikh' => $trkh, 'direction' => $direction, 'module' => $module, 'data' => $contents,
        ]);

        $mytoken == $token;

        $cawInfo = Cawangan::where('id_cawangan', $cawangan)->first();
        $jnsLimit = $cawInfo->jenis_limit;

        $data = KumpulanPerkhidmatan::where([
            'id_cawangan' => $cawangan,
            'id' => $id_kumpulan_perkhidmatan,
        ])->first();
        $ispelbagai = $data->ispelbagai;

        if ($id_kumpulan_perkhidmatan == "1" || $id_kumpulan_perkhidmatan == "3") {

            $kumpulan_perkhidmatan = KumpulanPerkhidmatan::where([
                'id_cawangan' => $cawangan,
                'kategori' => $id_kumpulan_perkhidmatan,
            ])->first();
            $id_kumpulan_perkhidmatan = $kumpulan_perkhidmatan->id;

        }

        $tarikh = date("Y-m-d H:i:s");
        $today = date("Y-m-d");
        $nama_hari = date("D", strtotime($today));

        $negeri = substr($cawangan, 2, 2);
        $activeID = DB::select("SELECT CASE WHEN player_id='' THEN 0 ELSE COUNT(*) END AS bilangan FROM tiket_" . $negeri . " WHERE DATE(tarikh) = '$today' AND cawangan = '$cawangan' AND player_id='$player_id' AND status_tiket=0");

        if ($activeID[0]['bilangan'] == 0) {

            if ($ispelbagai == "1") {

                $sql = "update kumpulan_perkhidmatan set no_terkini =  where id_cawangan = '$cawangan' and no_siri = '6000'";
                $this->exec_query($sql);
                $sql = "select * from kumpulan_perkhidmatan where id_cawangan = '$cawangan' and status=1 and no_siri = '6000'";
                return $this->exec_query($sql, "FETCH");

                // KumpulanPerkhidmatan::where([
                //     'id_cawangan' => $cawangan,
                //     'no_siri' => '6000',
                // ])->update([
                //     'no_terkini' = $no_terkini + 1
                // ]);

                $perkhidmatan = $this->apiModel->getServicesGroupPelbagai($cawangan, $id_kumpulan_perkhidmatan);
                $no_tiket = $perkhidmatan[0]['no_terkini'];
                $no_siri = $perkhidmatan[0]['no_siri'];
            } else {
                $perkhidmatan = $this->apiModel->getServicesGroup($cawangan, $id_kumpulan_perkhidmatan);
                $no_tiket = $perkhidmatan[0]['no_terkini'];
                $no_siri = $perkhidmatan[0]['no_siri'];
            }

        }

    }

    public function batalNombor(Request $request) 
    {
        $transid = $request->transid;
        $id_kumpulan_perkhidmatan = $request->id_kumpulan_perkhidmatan;
        $cawangan = $request->cawangan;
        $player_id = $request->player_id;
        $no_siri = $request->no_siri;
        $tarikh = $request->tarikh;
        $token = $request->token;

        $mytoken = $cawangan . $player_id . $tarikh . "jpjit2020" . $transid;
        $mytoken = hash("sha256", $mytoken);

        $token = strtoupper($token);
        $mytoken = strtoupper($mytoken);

        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
        $device= $_SERVER['HTTP_USER_AGENT'];
        $trkh = date("Y-m-d H:i:s");
        $direction = "1";
        $module = "batalNombor";
        DB::select("insert into log_transaksi (ip,device,tarikh,direction,module,data) values ('$ip','$device','$tarikh','$direction','$module','$data')");

        if ($mytoken != $token) {
            echo $mytoken . "|" . $token;
        } else {
            $status = "5";
            
            $tarikh = date("Y-m-d H:i:s");
            $negeri = substr($cawangan, 2, 2);
            $cond = "";
            if ($status == "0") {
                $cond = ", active_date ='$tarikh', kaunter = NULL ";
            }elseif($status == "5"){
                $cond = ", batal_oleh = '2', cancel_date = '$tarikh' ";
            }

            DB::select("update log_tiket set status_tiket = '$status' $cond where transid = '$transid'");
            DB::select("update tiket_".$negeri." set status_tiket = '$status' $cond where transid = '$transid'");
            
            $obj->status = "0";
            $obj->mesage = "Nombor anda telah berjaya dibatalkan. Sila dapatkan nombor baharu untuk urusan seterusnya";

            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
            $device=$_SERVER['HTTP_USER_AGENT'];
            $trkh = date("Y-m-d H:i:s");
            $direction = "2";
            $module = "batalNombor";
            DB::select("insert into log_transaksi (ip,device,tarikh,direction,module,data) values ('$ip','$device','$tarikh','$direction','$module','$data')");

            return response()->json($obj);
        }
    }

    function refreshMasaMenunggu(Request $request) 
    {
        $transid = $data->transid;
        $id_kumpulan_perkhidmatan = $data->id_kumpulan_perkhidmatan;
        $cawangan = $data->cawangan;
        $player_id = $data->player_id;
        $no_siri = $data->no_siri;
        $tarikh = $data->tarikh;
        $token = $data->token;

        $mytoken = $cawangan . $player_id . $tarikh . "jpjit2020" . $id_kumpulan_perkhidmatan . $no_siri;
        $mytoken = hash("sha256", $mytoken);

        $token = strtoupper($token);
        $mytoken = strtoupper($mytoken);

        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
        $device=$_SERVER['HTTP_USER_AGENT'];
        $trkh = date("Y-m-d H:i:s");
        $direction = "1";
        $module = "refreshMasaMenunggu";
        // $this->apiModel->insertLogTransaksiMasaMenunggu($ip,$device,$trkh,$direction,$module,$contents);

        //semak jenis had limit
        $cawInfo = DB::select("SELECT * from cawangan WHERE id_cawangan = '$cawangan' ");
        $jnsLimit = $cawInfo[0]['jenis_limit'];

        $mytoken == $token;

        if ($mytoken != $token) {
            // echo $mytoken;
        } else {
            $data = DB::select("select * from kumpulan_perkhidmatan where id_cawangan = '$cawangan' and id = '$id_kumpulan_perkhidmatan'");
            $ispelbagai = $data[0]['ispelbagai'];
            if($ispelbagai == "1"){
                $negeri = substr($cawangan, 2, 2); 
                DB::select("select * from tiket_".$negeri." where cawangan = '$cawangan' and no_siri = '6000' " . "and kaunter > '0' order by call_date desc limit 1");
            }else{
                $negeri = substr($cawangan, 2, 2); 
                DB::select("select * from tiket_".$negeri." where cawangan = '$cawangan' and kumpulan_perkhidmatan = '$id' " . "and kaunter > '0' order by call_date desc limit 1");
            }
            
            $no_sekarang = $data[0]['no_tiket'];
            //tambahan pada 23-09-2021
            $sesiTiket = $data[0]['sesi'];

            $masa_mula = strtotime($data[1]['call_date']);
            $masa_tamat = strtotime($data[0]['call_date']);

            $jumlah = $masa_tamat - $masa_mula;
            $masa_menunggu = floor($jumlah / 60);

            $tarikh = date("Y-m-d");

            $nama_hari = date("D", strtotime($tarikh)); 
            
            $waktu_sekarang = date("H:i:s");
            $waktu_sekarang = trim($waktu_sekarang);
            $session = DB::select("SELECT * FROM waktu_operasi WHERE cawangan = '$cawangan' AND ('$waktu_sekarang' BETWEEN waktu_mula AND waktu_tamat) AND status_aktif = '1' AND nama_hari='$nama_hari'");
            $sesi = $session[0]['sesi'];            
            
            if (count($session) == 0) 
            {
                //jika sesi null bermakna pelanggan scan di luar waktu operasi(time rehat),sistem mendapatkan sesi berikutnya
                $sessionRehat = $this->apiModel->getSessionRehat($cawangan,$nama_hari);
                $sesi = $sessionRehat[0]['sesi'];
                //$janaNo=1;                
                //tempoh tambahan dari waktu scan ke waktu mula sesi 
                $waktuMula = $sessionRehat[0]['waktu_mula'];
                    
                    $dateTime1 = date_create($waktuMula); 
                    $dateTime2 = date_create(date("H:i:s"));                  
                    $diff = date_diff($dateTime1, $dateTime2); 
                    
                    $tempoh_tmbhn = $diff->days * 24 * 60;
                    $tempoh_tmbhn += $diff->h * 60;
                    $tempoh_tmbhn += $diff->i;
                    
                    
            //masa scan di dalam waktu operasi
            }else{
                $today = date("Y-m-d");
                if($jnsLimit==1){
                        $hps = $this->apiModel->getHadPelangganSesi($cawangan,$jnsLimit);
                        $cP = $this->apiModel->getBilanganPelangganSesi($cawangan,$today,$sesi);
                }else
                {
                        $hps = $this->apiModel->getHadPelangganSesiPerkhidmatan($cawangan,$jnsLimit,$id_kumpulan_perkhidmatan);
                        $cP = $this->apiModel->getBilanganPelangganSesiPerkhidmatan($cawangan,$today,$sesi,$id_kumpulan_perkhidmatan);
                }

               // $hps = $this->apiModel->getHadPelangganSesi($cawangan);
                if($sesi == '1'){
                    $had_pelanggan_sesi = $hps[0]['had_pelanggan1'];
                }
                else if($sesi == '2'){
                    $had_pelanggan_sesi = $hps[0]['had_pelanggan2'];
                }
                else{
                    $had_pelanggan_sesi = $hps[0]['had_pelanggan3'];
                }
        
               // $today = date("Y-m-d");
               // $cP = $this->apiModel->getBilanganPelangganSesi($cawangan,$today,$sesi);
                $bil_pelanggan_sesi = $cP[0]['bilangan'];
                
                //semakan bilangan pelanggan kurang@sama had pelanggan serta di dalam waktu operasi
                //if ($bil_pelanggan_sesi<$had_pelanggan_sesi && date("H:i:s")<= $session[0]['waktu_tamat']){
                if ($bil_pelanggan_sesi<=$had_pelanggan_sesi){
                
                //$janaNo = 1;
                    $sesi = $sesi;
                    $tempoh_tmbhn = 0;
                    $waktuMula = $session[0]['waktu_mula'];
                } //jika bilangan pelanggan telah melebihi had
                else{
                    $sesi = $sesi +1; //ke sesi berikutnya dan semak jika sesi baru aktif
                    $sesiAktif = DB::select("SELECT * FROM waktu_operasi WHERE cawangan = '$cawangan' AND status_aktif = '1' AND nama_hari='$nama_hari' and sesi='$sesi'");
                    if (count($sesiAktif) != 0)
                    {
                    $janaNo = 1;
                    $sesi = $sesi;
                    $waktuSesi = DB::select("SELECT * FROM waktu_operasi WHERE cawangan = '$cawangan' AND status_aktif = '1' AND nama_hari='$nama_hari' and sesi='$sesi'");
                    $waktuMula = $waktuSesi[0]['waktu_mula']; //perbezaan waktu scan ke waktu sesi baru
                    
                    $dateTimeObject1 = date_create($waktuMula); 
                    $dateTimeObject2 = date_create(date("H:i:s"));                
                    $difference = date_diff($dateTimeObject1, $dateTimeObject2); 
                    
                    $tempoh_tmbhn = $difference->days * 24 * 60;
                    $tempoh_tmbhn += $difference->h * 60;
                    $tempoh_tmbhn += $difference->i;
                    //$tempoh_tmbhn=40;
                    
                    //tiada lagi sesi berikutnya yang aktif
                    }else{
                        $janaNo = 2;
                        //$sesi=null;
                        $tempoh_tmbhn = null;
                        $waktuMula=null;
                        //$tempoh_tmbhn = 100;
                    }
                
                }
        }
        
        $negeri = substr($cawangan, 2, 2);
        $dataid = DB::select("select * from tiket_".$negeri." where cawangan='$cawangan' and date(tarikh) = '$tarikh' and no_tiket='$no_siri' ");
        $sesinew = $dataid[0]['sesi'];
       /* if($sesinew==1)
        {
           $tempoh_tmbhn=0; 
        }else{
            $tempoh_tmbhn=$tempoh_tmbhn; 
        }*/
        
           if($ispelbagai == "1"){
                //$data = $this->apiModel->getBilanganHariIniPelbagai($cawangan,$tarikh,$no_tiket);
                $negeri = substr($cawangan, 2, 2);
                if($no_tiket == ""){
                    $data = DB::select("select count(*) as bilangan from tiket_".$negeri." where cawangan = '$cawangan' and date(tarikh) = '$tarikh' and kaunter IS NULL AND batal_oleh=0 and sesi='$sesi' group by cawangan");
                }else{
                    $data = DB::select("select count(*) as bilangan from tiket_".$negeri." where cawangan = '$cawangan' and date(tarikh) = '$tarikh' and kaunter IS NULL AND batal_oleh=0 and no_tiket < '$no_tiket' and sesi='$sesi' group by cawangan");
                }
            }else{
               // $data = $this->apiModel->getBilanganHariIni($cawangan, $id_kumpulan_perkhidmatan, $tarikh, $no_tiket,$no_tiket);
                $negeri = substr($cawangan, 2, 2);
                $data = DB::select("select count(*) as bilangan from tiket_".$negeri." where cawangan = '$cawangan' and kumpulan_perkhidmatan = '$id_kumpulan_perkhidmatan' and date(tarikh) = '$tarikh' and kaunter IS NULL AND batal_oleh=0 and no_tiket < '$no_tiket' and sesi='$sesi' group by cawangan");
            }

            
             if($data[0]['bilangan'] > 1){
                $masa_menunggu = ($data[0]['bilangan']) * 2 + $tempoh_tmbhn;
                $kedudukan_menunggu = $data[0]['bilangan'];
            }else{
                $masa_menunggu = 1 * 2 + $tempoh_tmbhn;
                $kedudukan_menunggu = 1;
            }

            $obj->no_sekarang = $no_sekarang;
            $obj->masa_menunggu = $masa_menunggu;
            $obj->cawangan = $cawangan;
            $obj->kedudukan_menunggu = $kedudukan_menunggu;
            $obj->sesiTiket = $sesiTiket;
            $obj->sesi = $sesinew;            

            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
            $device=$_SERVER['HTTP_USER_AGENT'];
            $trkh = date("Y-m-d H:i:s");
            $direction = "2";
            $module = "refreshMasaMenunggu";
            // $this->apiModel->insertLogTransaksiMasaMenunggu($ip,$device,$trkh,$direction,$module,json_encode($obj));

            return response()->json($obj);
        }
    }

    public function senaraiCawangan(Request $data)
    {
        // $contents = file_get_contents("php://input");
        // $data = json_decode($contents);
        $player_id = $data->player_id;
        $tarikh = $data->tarikh;
        $token = $data->token;

        $mytoken = $player_id . $tarikh . "jpjit2020";
        $mytoken = hash("sha256", $mytoken);
        $token = strtoupper($token);
        $mytoken = strtoupper($mytoken);

        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
        $device=$_SERVER['HTTP_USER_AGENT'];
        $trkh = date("Y-m-d H:i:s");
        $direction = "1";
        $module = "senaraiCawangan";
        DB::select("insert into log_transaksi (ip,device,tarikh,direction,module,data) values ('$ip','$device','$tarikh','$direction','$module','$data')");
        // $token = $mytoken;
        if ($mytoken != $token) {
             echo $mytoken;
        } else {
            
             $data = '{ 
                "cawangan":[
                {"id" : "1", "name" : "Jabatan Pengangkutan Jalan Negeri Selangor", "address": "Jalan Padang Jawa\n40620 Shah Alam", "phone" : "03-55669555", "fax": "03-55432202", "koordinat" : { "lat": "3.057617", "lng" : "101.495948"}, "distance" : "0.00"},
                {"id" : "2", "name" : "Cawangan Kuala Kubu Bharu", "address": "Kuala Kubu Bharu\n44000 Hulu Selangor", "phone" : "03-60641130", "fax": "03-60643731", "koordinat" : { "lat": "3.558582", "lng" : "101.645354"}, "distance" : "0.00"},
                {"id" : "3", "name" : "Cawangan Sabak Bernam", "address": "Batu 2, Jalan Besar, Tebuk Pulai,\n45200 Sabak Bernam", "phone" : "03-32161466", "fax": "03-32164929", "koordinat" : { "lat": "3.728188", "lng" : "100.971702"}, "distance" : "0.00"},
                {"id" : "4", "name" : "Cawangan Petaling Jaya", "address": "Jalan Sultan\n46620 Petaling Jaya", "phone" : "03-79609440", "fax": "03-79564230", "koordinat" : { "lat": "3.103164", "lng" : "101.645445"}, "distance" : "0.00"},
                {"id" : "5", "name" : "Cawangan Banting", "address": "No 1 , Jalan Campakasari 2\n42700 Banting", "phone" : "03-31872591", "fax": "03-31229698", "koordinat" : { "lat": "2.798577", "lng" : "101.493215"}, "distance" : "0.00"},
                {"id" : "6", "name" : "Cawangan Bangi", "address": "Lot 139 Jalan 7/7C\nSeksyen 7, 43650 Bandar Baru Bangi", "phone" : "03-89258386", "fax": "03-89258386", "koordinat" : { "lat": "2.968197", "lng" : "101.774865"}, "distance" : "0.00"},
                {"id" : "7", "name" : "Pejabat JPJ UTC Selangor", "address": "Lot 3-1(A)\nKompleks UTC Selangor @ Anggerik Mall\nNo.5, Jalan 14/18, Seksyen 14\n40000, Shah Alam", "phone" : "03-55234347", "fax": "-", "koordinat" : { "lat": "3.071227", "lng" : "101.519869"}, "distance" : "0.00"},
                {"id" : "0116111", "name" : "Jabatan Pengangkutan Jalan Cawangan Putrajaya", "address": "Unit 11, Aras Bawah,\nBangunan Komersil PJH,\nPersiaran Perdana, Presint 4\n62100 Putrajaya", "phone" : "03-88811705", "fax": "03-88811711", "koordinat" : { "lat": "2.912756", "lng" : "101.684284"}, "distance" : "0.00"}]
            }';

    // $data = array(
    //     array("id"=>"1","name"=>"JPJ Selangor","koordinat"=>array("lat"=>"2.3939393","lng"=>"3.55555")),
    //     array("id"=>"2","name"=>"JPJ Kedah","koordinat"=>array("lat"=>"2.3939393","lng"=>"3.55555")),
    // );


    //         $obj->data = $data;

            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
            $device=$_SERVER['HTTP_USER_AGENT'];
            $trkh = date("Y-m-d H:i:s");
            $direction = "2";
            $module = "getListCawangan";
            DB::select("insert into log_transaksi (ip,device,tarikh,direction,module,data) values ('$ip','$device','$tarikh','$direction','$module','$data')");
            // $this->apiModel->insertLogTransaksi($ip,$device,$trkh,$direction,$module,$data);

            // echo (json_encode($obj));
            return response()->json($data);
        }
    }
}
