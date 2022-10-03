<?php

namespace App\Http\Controllers;

use App\Models\Aktiviti;
use App\Models\Kedatangan;
use App\Models\Urusetia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class eHadirController extends Controller
{
    public function daftar_kehadiran(Request $request)
    {
        dd($request->all());
        $data = $request->data;
        $data = explode("/", $data);
        $transid_aktiviti = $data[6];
        $nokp = $data[7];
        $bahagian = $data[8];

        $tarikh = date("Y-m-d");
        $masa = date("H:i:s");

        $dataaktiviti = DB::select("select * from aktivitis where transid_aktiviti = '$transid_aktiviti' and ('$tarikh' between tarikh_mula and tarikh_tamat)");

        $obj = new stdClass();

        if (count($dataaktiviti) > 0) {
            $datasesi = DB::select("select * from sesis where transid_aktiviti = '$transid_aktiviti' and ('$masa' between DATE_SUB(masa_mula, INTERVAL 1 HOUR) and masa_tamat)");

            if (count($datasesi) > 0) {
                $kedatangan = DB::select("select * from kedatangan where nokp = '$nokp' and transid_aktiviti = '$transid_aktiviti'");

                if (count($kedatangan) > 0) {
                    $response[] = array(
                        "status" => "1",
                        "msg" => "Telah mendaftar",
                    );
                    $obj->kod = 1;
                    $obj->msg = "Telah Mendaftar";
                } else {
                    if ($nokp == "" || $transid_aktiviti == "") {
                        $obj->kod = 2;
                        $obj->msg = "Sila pastikan anda scan QR Kod yang betul.";
                    } else {
                        $transid_sesi = $datasesi[0]['transid_sesi'];
                        $id_aktiviti = $dataaktiviti[0]['id'];
                        // $databahagian = $this->apiModel->getBahagian($nokp);
                        // $bahagian = $databahagian[0]['bahagian'];

                        Kedatangan::create([
                            'nokp' => $nokp,
                            'transid_aktiviti' => $transid_aktiviti,
                            'kodbahagian' => $bahagian,
                            'transid_sesi' => $transid_sesi,
                            'id_aktiviti' => $id_aktiviti,
                        ]);

                        $response[] = array(
                            "nama_aktiviti" => $dataaktiviti[0]['nama_aktiviti'],
                            "lokasi" => $dataaktiviti[0]['lokasi'],
                            "tarikh" => $dataaktiviti[0]['tarikh_mula'] . "-" . $dataaktiviti[0]['tarikh_tamat'],
                            "masa" => $dataaktiviti[0]['masa_mula'] . "-" . $dataaktiviti[0]['masa_tamat'],
                        );
                        $obj->status = 0;
                        $obj->msg = "Pendaftaran Berjaya";
                        $obj->data = $response;
                    }
                }
            } else {
                $obj->status = "1";
                $obj->msg = "Sila daftar pada masa yang telah di tetapkan";
            }
        } else {
            $obj->status = "1";
            $obj->msg = "Sila daftar pada tarikh yang telah di tetapkan";
        }

        return json_encode($obj);
    }

    public function senarai_aktiviti_hadir(Request $request)
    {
        $nokp = $request->nokp;
        $sql = "select b.*, a.nokp,(select c.id_aktiviti from urusetias c where c.urusetia = a.nokp and c.id_aktiviti = a.id_aktiviti) as isurusetia from kedatangans a, aktivitis b where a.id_aktiviti = b.id and a.nokp = '$nokp'";
        $result = DB::select($sql);
        return json_encode($result);
    }

    public function senarai_aktiviti(Request $request)
    {
        $nokp = $request->nokp;

        $list = DB::select("select a.*, b.urusetia from aktivitis a, urusetias b where a.id = b.id_aktiviti and b.urusetia = '$nokp' order by a.tarikh_mula desc");

        $obj = new Stdclass();

        foreach ($list as $val) {
            $id_aktiviti = $val['id'];
            $list_urusetia = DB::select("select a.*,b.nama,b.namabahagian,b.nokp from urusetias a, users b where a.urusetia = b.nokp and  id_aktiviti = '$id_aktiviti' order by b.nama");
            unset($urusetia);
            foreach ($list_urusetia as $valurusetia) {
                $urusetia[] = array(
                    "nokp" => $valurusetia['urusetia'],
                    "nama" => $valurusetia['nama'],
                    "namabahagian" => $valurusetia['namabahagian'],
                );
            }
            $transid_aktiviti = $val['transid_aktiviti'];
            $sesidata = DB::select('select * from sesi where transid_aktiviti = ?', [$transid_aktiviti]);
            unset($sesi);
            foreach ($sesidata as $val21) {
                $sesi[] = array(
                    "sesi" => $val21['sesi'],
                    "masa_mula" => $val21['masa_mula'],
                    "masa_tamat" => $val21['masa_tamat'],
                );
            }
            $aktiviti[] = array(
                "id" => $val['id'],
                "transid_aktiviti" => $transid_aktiviti,
                "nama_aktiviti" => $val['nama_aktiviti'],
                "tarikh_mula" => $val['tarikh_mula'],
                "tarikh_tamat" => $val['tarikh_tamat'],
                "masa_sesi" => $sesi,
                "lokasi" => $val['lokasi'],
                "urusetia" => $urusetia,
            );

        }
        $obj->aktiviti = $aktiviti;

        return json_encode($obj);
    }

    public function senarai_urusetia1(Request $request)
    {
        $id_aktiviti = $request->id_aktiviti;
        $sql = "select a.*,b.nama,b.namabahagian,b.nokp from urusetias a, users b where a.urusetia = b.nokp and  id_aktiviti = '$id_aktiviti' order by b.nama";
        $list = DB::select($sql);
        echo json_encode($list);
    }

    public function tambah_urusetia(Request $request)
    {
        $nokp = $request->nokp;
        $id_aktiviti = $request->id_aktiviti;

        $cond = "";
        if ($nokp != "") {
            $cond = " and urusetia = '$nokp' ";
        }
        $sql = "select a.*,b.nama,b.namabahagian from urusetias a, users b where a.urusetia = b.nokp and  a.id_aktiviti = '$id_aktiviti' and a.urusetia = '$nokp' order by b.nama";

        $list = DB::select($sql);

        $obj = new Stdclass();

        $bil = count($list);
        if ($bil > 0) {
            $obj->kod = 1;
            $obj->message = "Telah mendaftar sebagai urusetia..";
        } else {
            if ($nokp != "") {
                $this->apiModel->insertUrusetia($id_aktiviti, $nokp);
                $obj->kod = 0;
                $obj->message = "Pendaftaran sebagai urusetia berjaya." . $bil;
            } else {
                $obj->kod = 1;
                $obj->message = "Sila masukkan No Kad Pengenalan";
            }

        }
        echo json_encode($obj);
    }

    public function senarai_kehadiran(Request $request)
    {
        $id_aktiviti = $request->id_aktiviti;
        $sql = "select b.* from kedatangans a, users b where a.nokp = b.nokp and a.id_aktiviti = '$id_aktiviti'";
        $list = DB::select($sql);
        echo json_encode($list);
    }

    public function daftar_manual(Request $request)
    {
        $nokp = $request->nokp;
        $id_aktiviti = $request->id_aktiviti;
        $jenis = $request->jenis_pendaftaran;
        $transid_aktiviti = $request->transid_aktiviti;
        $transid_sesi = $request->transid_sesi;
        $response = "";
        $sql1 = "select * from kedatangans where nokp = '$nokp' and transid_aktiviti = '$transid_aktiviti'";
        $kedatangan = DB::select($sql1);
        $obj = new stdClass();

        if (count($kedatangan) > 0) {
            $obj->kod = 1;
            $obj->message = "Telah Mendaftar";
        } else {

            $sql2 = "select *,(select keterangan from bahagians b where a.bahagian = b.kod) as namabahagian from users a where nokp = '$nokp'";
            $sql2 = "select * from jpjp.staff where nokp = '$nokp'";
            $sql2 = "select * from jpjp.staff a,jpjp.bahagian b where a.bahagian = b.kod and a.nokp = '$nokp'";
            $data = DB::select($sql2);

            if (count($data) == 1) {
                $bahagian = $data[0]['bahagian'];
                Kedatangan::create([
                    'nokp' => $nokp,
                    'transid_aktiviti' => $transid_aktiviti,
                    'kodbahagian' => $bahagian,
                    'transid_sesi' => $transid_sesi,
                    'id_aktiviti' => $id_aktiviti,

                ]);
                $obj->kod = 0;
                $obj->message = "Pendaftaran Berjaya.";
                $obj->nama = $data[0]['nama'];
                $obj->nokp = $nokp;
                $obj->bahagian = $data[0]['namabahagian'];

            } else {
                $obj->kod = 2;
                $obj->message = "No Mykad Tidak Sah.";
            }

        }
        echo json_encode($obj);
    }

    public function daftarQR(Request $request)
    {
        $nokp = $request->nokp;

        $sql = "select *,(select keterangan from bahagians b where a.bahagian = b.kod) as namabahagian from users a where nokp = '$nokp'";
        $sql = "select * from jpjp.staff where nokp = '$nokp'";
        $sql = "select * from jpjp.staff a,jpjp.bahagian b where a.bahagian = b.kod and a.nokp = '$nokp'";

        $data = DB::select($sql);

        $obj = new stdClass();
        $bil = count($data);
        if ($bil == 0 || $nokp == "") {
            $obj->kod = 1;
            $obj->message = "No Mykad tiada dalam rekod.";
        } else {
            $obj->kod = 0;
            $obj->message = "Penjanaan Kod QR berjaya.";
            $obj->nama = $data[0]['nama'];
            $obj->bahagian = $data[0]['bahagian'];
            $obj->namabahagian = $data[0]['keterangan'];
            $obj->nokp = $data[0]['nokp'];
        }
        echo json_encode($obj);
    }

    public function tambah_aktiviti(Request $request)
    {
        $nokp = $request->nokp;
        $perkara = $request->perkara;
        $tarikh = $request->tarikh;
        $masa = $request->masa;
        $lokasi = $request->lokasi;
        $keterangan = $request->keterangan;
        $nama = $request->nama;

        $create_by = 0;
        $obj = new stdClass();
        Aktiviti::create([
            'nokp' => $nokp,
            'perkara' => $perkara,
            'tarikh' => $tarikh,
            'masa' => $masa,
            'lokasi' => $lokasi,
            'keterangan' => $keterangan,
            'nama' => $nama,
            'create_by' => $create_by,
        ]);

        $sql = "select * from aktivitis where nama_aktiviti = '$nama' and tarikh='$tarikh' and masa='$masa' "
            . "and lokasi='$lokasi' and keterangan='$keterangan' and create_by='$create_by' "
            . "and urusetia_id ='$nokp'";

        $aktiviti = DB::select($sql);
        $id_aktiviti = $aktiviti[0]['id'];

        Urusetia::create([
            'id_aktiviti' => $id_aktiviti,
            'urusetia' => $nokp,
            'create_by' => $create_by,
        ]);

        $obj->kod = 0;
        $obj->message = "Aktiviti baru telah disimpan";
        echo json_encode($obj);

    }
}
