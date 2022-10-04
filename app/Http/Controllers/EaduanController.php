<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Ref;
use App\Models\User;
use App\Models\MobileappsUser;
use App\Models\NoSiri;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\Storage;

class EaduanController extends Controller
{
    public function simpan_aduan(Request $request)
    {
        $idkesalahan = $request->idkesalahan;
        $tarikh = $request->tarikh;
        $masa = $request->masa;
        $lokasi = $request->lokasi;
        $latitude = $request->latitude;
        $longitude = $request->longlitude;
        $nokenderaan = $request->nokenderaan;
        $catatan = $request->catatan;
        $status = 0;
        $userid = $request->pengadu;
        $image = $request->image_name;
        $negeri = ucwords($request->negeri);
        $jenis_media = $request->jenis_media;
        $uuid = $request->device_id;
        $pautan = $request->pautan;
        $video = $request->video_name;
        $onesignal_id = $request->onesignal_id;

        if($image != "" && $video == ""){
            $nama_fail = $image."|";
            $jenis_media = "photo";
        }elseif($video != "" && $image == ""){
            $nama_fail = $video."|";
            $jenis_media = "video";
        }elseif($video != "" && $image != ""){
            $nama_fail = $image."|".$video;
            $jenis_media = "both";
        }else{
            $nama_fail = "|";
            $jenis_media = "none";
        }


        // $no = NoSiri::where('jenis', '1')->get();
        // if (!$no->isEmpty()) {
        //     $no_siri = $no[0]['no_siri'];
        //     $no_siri++;
        // } else {
        //     # code...
        // }
        
        $no_siri = rand(100000, 999999);
        
        // DB::select("update no_siri set no_siri = '$no_siri' where jenis = '1'");

        // $idkesalahan = $_POST['idkesalahan'];
        // $tarikh = $_POST['tarikh'];
        // $masa = $_POST['masa'];
        // $lokasi = $_POST['lokasi'];
        // $latitude = $_POST['latitude'];
        // $longitude = $_POST['longlitude'];
        // $nokenderaan = $_POST['nokenderaan'];
        // $catatan = $_POST['catatan'];
        // $status = $_POST['status'];
        // $userid = $_POST['userId'];

        if($negeri == "Perlis"){
            $kod_negeri = "09";
        }elseif($negeri == "Pulau Pinang"){
            $kod_negeri = "07";
        }elseif($negeri == "Kedah"){
            $kod_negeri = "02";
        }elseif($negeri == "Perak"){
            $kod_negeri = "08";
        }elseif($negeri == "Selangor"){
            $kod_negeri = "10";
        }elseif($negeri == "Negeri Sembilan"){
            $kod_negeri = "05";
        }elseif($negeri == "Melaka"){
            $kod_negeri = "04";
        }elseif($negeri == "Johor"){
            $kod_negeri = "01";
        }elseif($negeri == "Pahang"){
            $kod_negeri = "06";
        }elseif($negeri == "Terengganu"){
            $kod_negeri = "11";
        }elseif($negeri == "Kelantan"){
            $kod_negeri = "03";
        }elseif($negeri == "Sabah"){
            $kod_negeri = "12";
        }elseif($negeri == "Sarawak"){
            $kod_negeri = "13";
        }elseif($negeri == "Wilayah Persekutuan Kuala Lumpur"){
            $kod_negeri = "14";
        }elseif($negeri == "Wilayah Persekutuan Labuan"){
            $kod_negeri = "15";
        }else{
            $kod_negeri = "16";
        }
        if($userid != ""){
            // $update_date = date("Y-m-d H:i:s");
            $data = new Aduan;
            $data->no_aduan = $no_siri;
            $data->jenis_kesalahan = $idkesalahan;
            $data->tarikh_kesalahan = $tarikh;
            $data->masa_kesalahan = $masa;
            $data->lokasi_kesalahan = $lokasi;
            $data->latitude = $latitude;
            $data->longitude = $longitude;
            $data->no_kenderaan = $nokenderaan;
            $data->catatan = $catatan;
            $data->pengadu = $userid;
            $data->nama_fail = $nama_fail;
            $data->negeri = $negeri;
            $data->jenis_media = $jenis_media;
            $data->device_id = $uuid;
            $data->status_aduan = $status;
            $data->kod_negeri = $kod_negeri;
            $data->pautan = $pautan;
            $data->onesignal_id = $onesignal_id;
            try {
                $check = $request->validate([
                    'gambar' => 'mimes:jpeg,png,jpg,gif,mp3,mp4,wma',
                ]);
            } catch (\Throwable $th) {
                $resp = new stdClass;
                $resp->message = "File type not compatible";
                return response()->json($resp);
            }
    
            if ($request->gambar) {

                // $file = $request->file('theFile');
                // $name = $request->file('theFile')->getClientOriginalName();
                // $result = Storage::disk('sftp')->putFileAs('/aduantrafikdb/client_share/', $file, $name);
    
                $nama = $data->no_aduan . '_' . $data->pengadu . '_' . time() . '.' . $request->gambar->extension();
                // dd($nama);
                $request->gambar->move(public_path('aduantrafikdb/client_share/'), $nama);
                $obj = new stdClass;
                $obj->upload = $request->gambar;
                $obj->type = $request->gambar->getClientOriginalExtension();
                $saiz = $request->gambar->getSize();
                $saiz = $saiz / 1024;
                $obj->size = $saiz.' kb';
                $obj->file = '/aduantrafikdb/client_share/'.$nama;
                $data->file_path = '/aduantrafikdb/client_share/'.$nama;
                // $disk = Storage::build([
                //     'driver' => 'sftp',
                //     'root' => '/',
                // ]);
                 
                // $disk->put($nama, $request->gambar);
            }else {
                $obj = new stdClass;
                $obj->message = "Error";
            }
            // dd($data->all(), $request->all());
            $data->save();
            // $bilpengadu = DB::select("select * from users where nokp = '$userid'");
            // if(count($pengadu) == 1){
            //     DB::select("update users set onesignal_id = '$onesignal_id' where nokp = '$userid'");
            // }
            $response = new stdClass;
            $response->status = "saved";
            $response->status_save = $data;
            $response->file_upload = $obj;
            $response->no_aduan = $no_siri;
            
            return response()->json($response);
        }
    }

    public function upld_images(Request $request)
    {

        try {
            $check = $request->validate([
                'gambar' => 'mimes:jpeg,png,jpg,gif,mp3,mp4,wma',
            ]);
        } catch (\Throwable $th) {
            $resp = new stdClass;
            $resp->message = "File type not compatible";
            return response()->json($resp);
        }

        if ($request->gambar) {

            $nama = time() . '_' . $request->gambar . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('aduantrafikdb/client_share/'), $nama);
            $obj = new stdClass;
            $obj->upload = $request->gambar;
            $obj->type = $request->gambar->getClientOriginalExtension();
            $saiz = $request->gambar->getSize();
            $saiz = $saiz / 1024;
            $obj->size = $saiz.' kb';
            $obj->file = 'aduantrafikdb/client_share' . $nama;

            // $disk = Storage::build([
            //     'driver' => 'sftp',
            //     'root' => '/',
            // ]);
             
            // $disk->put($nama, $request->gambar);
        }else {
            $obj = new stdClass;
            $obj->message = "Error";
        }

        return response()->json($obj);
    }

    public function get_status_aduan(Request $request)
    {
        $userid = $request->nokp;

        $data = Aduan::where('pengadu', $userid)->get();
        // dd($data);
        $senarai_aduan = [];
        foreach ($data as $key => $val) {
            $status = Ref::where('jenis', '2')->first()->keterangan;
            if ($val['jenis_kesalahan'] == 1) {
                $nama_kesalahan = "Gagal mematuhi lampu isyarat merah";
            } 
            elseif ($val['jenis_kesalahan'] == 2) {
                $nama_kesalahan = "Memandu di lorong kecemasan";
            }
            elseif ($val['jenis_kesalahan'] == 3) {
                $nama_kesalahan = "Memotong barisan";
            }
            elseif ($val['jenis_kesalahan'] == 4) {
                $nama_kesalahan = "Memotong sebelah kiri";
            }
            elseif ($val['jenis_kesalahan'] == 5) {
                $nama_kesalahan = "Memotong garisan berkembar";
            }
            elseif ($val['jenis_kesalahan'] == 6) {
                $nama_kesalahan = "Menggunakan telefon bimbit semasa memandu";
            }
            elseif ($val['jenis_kesalahan'] == 7) {
                $nama_kesalahan = "No plat fancy";
            }
            elseif ($val['jenis_kesalahan'] == 8) {
                $nama_kesalahan = "Cermin gelap";
            }
            elseif ($val['jenis_kesalahan'] == 9) {
                $nama_kesalahan = "Tidak memakai tali pinggang keledar dan helmet";
            }

            $k = [
                "nokp_pengadu" => $val['pengadu'],
                "no_aduan" => $val['no_aduan'], 
                "status" => $val['status_aduan'], 
                "id" => $val['id'], 
                "keterangan_status" => $status, 
                "send_flag" => $val['send_flag'], 
                "tarikh" => $val['tarikh_kesalahan'],
                "masa" => $val['masa_kesalahan'],
                "kesalahan" => $nama_kesalahan,
                
            ];
            $id = $val['id'];
            array_push($senarai_aduan, $k);
        }
        // dd($data);

        // DB::select("select a.*,(select b.keterangan_apps from ref b where b.jenis = '2' and b.kod = a.status_aduan) as keterangan_status from aduan a where a.pengadu = '$userid' and a.send_flag <> '0'");
        // $data = DB::select("select a.*,(select b.keterangan_apps from ref b where b.jenis = '2' and b.kod = a.status_aduan) as keterangan_status from aduan a where a.pengadu = '$userid'");

        // DB::select("update aduan set send_flag = status_aduan where id = '$id'");
        return response()->json($senarai_aduan);
    }

    public function kemaskini_aduan(Request $request)
    {
        $response = new stdClass();

        $no_aduan = $request->no_aduan;
        
        $idkesalahan = $request->idkesalahan;
        $tarikh = $request->tarikh;
        $masa = $request->masa;
        $lokasi = $request->lokasi;
        $latitude = $request->latitude;
        $longitude = $request->longlitude;
        $nokenderaan = $request->nokenderaan;
        $catatan = $request->catatan;
        $status = $request->status;
        $userid = $request->pengadu;
        // $gambar = $request->image_name;
        $negeri = $request->negeri;
        $jenis_media = $request->jenis_media;
        $uuid = $request->device_id;
        $pautan = $request->pautan;
        $noaduan = $request->noaduan;
        $image = $request->image_name;
        $video = $request->video_name;
        $onesignal_id = $request->onesignal_id;

        if($image != "" && $video == ""){
            $nama_fail = $image;
            $jenis_media = "photo";
        }elseif($video != "" && $image == ""){
            $nama_fail = $video;
            $jenis_media = "video";
        }else{
            $nama_fail = $image."-".$video;
            $jenis_media = "both";
        }

        $status_aduan = 3;

        if($negeri == "Perlis"){
            $kod_negeri = "09";
        }elseif($negeri == "Pulau Pinang"){
            $kod_negeri = "07";
        }elseif($negeri == "Kedah"){
            $kod_negeri = "02";
        }elseif($negeri == "Perak"){
            $kod_negeri = "08";
        }elseif($negeri == "Selangor"){
            $kod_negeri = "10";
        }elseif($negeri == "Negeri Sembilan"){
            $kod_negeri = "05";
        }elseif($negeri == "Melaka"){
            $kod_negeri = "04";
        }elseif($negeri == "Johor"){
            $kod_negeri = "01";
        }elseif($negeri == "Pahang"){
            $kod_negeri = "06";
        }elseif($negeri == "Terangganu"){
            $kod_negeri = "11";
        }elseif($negeri == "Kelantan"){
            $kod_negeri = "03";
        }elseif($negeri == "Sabah"){
            $kod_negeri = "12";
        }elseif($negeri == "Sarawak"){
            $kod_negeri = "13";
        }else{
            $kod_negeri = "16";
        }

        $data = Aduan::where('no_aduan', $no_aduan)->first();
        // dd($data);
        $data->jenis_kesalahan = $idkesalahan;
        $data->tarikh_kesalahan = $tarikh;
        $data->masa_kesalahan = $masa;
        $data->lokasi_kesalahan = $lokasi;
        $data->latitude = $latitude;
        $data->longitude = $longitude;
        $data->no_kenderaan = $nokenderaan;
        $data->catatan = $catatan;
        $data->pengadu = $userid;
        $data->nama_fail = $nama_fail;
        $data->negeri = $negeri;
        $data->jenis_media = $jenis_media;
        $data->device_id = $uuid;
        $data->status_aduan = $status;
        $data->kod_negeri = $kod_negeri;
        $data->pautan = $pautan;
        $data->onesignal_id = $onesignal_id;
        // dd($data->all(), $request->all());
        $data->save();


        // DB::select("update aduan set jenis_kesalahan = '$idkesalahan',tarikh_kesalahan = '$tarikh',masa_kesalahan = '$masa',lokasi_kesalahan = '$lokasi',latitude = '$latitude',longitude = '$longitude',
        // no_kenderaan = '$nokenderaan',catatan = '$catatan',pengadu = '$userid',update_date = '$update_date',nama_fail = '$nama_fail',negeri = '$negeri',jenis_media = '$jenis_media',
        // device_id = '$uuid',kod_negeri = '$kod_negeri',pautan = '$pautan', status_aduan = '$status_aduan' where no_aduan = '$no_aduan'");

        $response->status = "updated";
        $response->status_save = $data;
        $response->no_aduan = $no_aduan;
        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $aduan = Aduan::where('no_aduan', $id)->first();
        
        try {
            $aduan->delete();

            $resp = new stdClass;
            $resp->status = "Successful deleted";
            return response()->json($resp);
        } catch (\Throwable $th) {
            $resp = new stdClass;
            $resp->status = "Error";
            return response()->json($resp);
        }
        
    }

    public function checksajo()
    {
        $idkesalahan = 1;
        $tarikh = "2022-01-01";
        $masa = "2:02";
        $lokasi = "Selayang";
        $latitude = "1";
        $longitude = "1";
        $nokenderaan = "BEX2820";
        $catatan = "CHeckSajo";
        $status = 1;
        $userid = "980410025195";
        $image = "";
        $negeri = "Selangor";
        $jenis_media = "";
        $uuid = "123";
        $pautan = "najhan.xyz";
        $video = "";
        $onesignal_id = 1212121;

        if($image != "" && $video == ""){
            $nama_fail = $image."|";
            $jenis_media = "photo";
        }elseif($video != "" && $image == ""){
            $nama_fail = $video."|";
            $jenis_media = "video";
        }elseif($video != "" && $image != ""){
            $nama_fail = $image."|".$video;
            $jenis_media = "both";
        }else{
            $nama_fail = "|";
            $jenis_media = "none";
        }


        // $no = NoSiri::where('jenis', '1')->get();
        // if (!$no->isEmpty()) {
        //     $no_siri = $no[0]['no_siri'];
        //     $no_siri++;
        // } else {
        //     # code...
        // }
        
        $no_siri = rand(100000, 999999);
        
        // DB::select("update no_siri set no_siri = '$no_siri' where jenis = '1'");

        // $idkesalahan = $_POST['idkesalahan'];
        // $tarikh = $_POST['tarikh'];
        // $masa = $_POST['masa'];
        // $lokasi = $_POST['lokasi'];
        // $latitude = $_POST['latitude'];
        // $longitude = $_POST['longlitude'];
        // $nokenderaan = $_POST['nokenderaan'];
        // $catatan = $_POST['catatan'];
        // $status = $_POST['status'];
        // $userid = $_POST['userId'];

        if($negeri == "Perlis"){
            $kod_negeri = "09";
        }elseif($negeri == "Pulau Pinang"){
            $kod_negeri = "07";
        }elseif($negeri == "Kedah"){
            $kod_negeri = "02";
        }elseif($negeri == "Perak"){
            $kod_negeri = "08";
        }elseif($negeri == "Selangor"){
            $kod_negeri = "10";
        }elseif($negeri == "Negeri Sembilan"){
            $kod_negeri = "05";
        }elseif($negeri == "Melaka"){
            $kod_negeri = "04";
        }elseif($negeri == "Johor"){
            $kod_negeri = "01";
        }elseif($negeri == "Pahang"){
            $kod_negeri = "06";
        }elseif($negeri == "Terengganu"){
            $kod_negeri = "11";
        }elseif($negeri == "Kelantan"){
            $kod_negeri = "03";
        }elseif($negeri == "Sabah"){
            $kod_negeri = "12";
        }elseif($negeri == "Sarawak"){
            $kod_negeri = "13";
        }elseif($negeri == "Wilayah Persekutuan Kuala Lumpur"){
            $kod_negeri = "14";
        }elseif($negeri == "Wilayah Persekutuan Labuan"){
            $kod_negeri = "15";
        }else{
            $kod_negeri = "16";
        }
        if($userid != ""){
            // $update_date = date("Y-m-d H:i:s");
            $data = new Aduan;
            $data->no_aduan = $no_siri;
            $data->jenis_kesalahan = $idkesalahan;
            $data->tarikh_kesalahan = $tarikh;
            $data->masa_kesalahan = $masa;
            $data->lokasi_kesalahan = $lokasi;
            $data->latitude = $latitude;
            $data->longitude = $longitude;
            $data->no_kenderaan = $nokenderaan;
            $data->catatan = $catatan;
            $data->pengadu = $userid;
            $data->nama_fail = $nama_fail;
            $data->negeri = $negeri;
            $data->jenis_media = $jenis_media;
            $data->device_id = $uuid;
            $data->kod_negeri = $kod_negeri;
            $data->pautan = $pautan;
            $data->onesignal_id = $onesignal_id;
            // dd($data->all(), $request->all());
            $data->save();
            // $bilpengadu = DB::select("select * from users where nokp = '$userid'");
            // if(count($pengadu) == 1){
            //     DB::select("update users set onesignal_id = '$onesignal_id' where nokp = '$userid'");
            // }
            dd('habis');
        }
    }

    public function page_up()
    {
        return view('test_upload');
    }
}
