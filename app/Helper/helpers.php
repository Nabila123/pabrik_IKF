<?php
    use App\Models\PPICGudangRequest;
    use App\Models\AdminPurchase;
    use App\Models\GudangRajutKeluar;
    use App\Models\GudangRajutMasuk;
    use App\Models\GudangCuciKeluar;
    use App\Models\GudangCompactKeluar;
    use App\Models\GudangCompactMasuk;
    use App\Models\GudangInspeksiKeluar;
    use App\Models\GudangInspeksiMasuk;
    use App\Models\GudangPotongKeluar;
    use App\Models\GudangPotongRequest;
    use App\Models\GudangJahitMasuk;
    use App\Models\GudangBatilMasuk;
    use App\Models\GudangControlMasuk;
    use App\Models\GudangSetrikaMasuk;
    use App\Models\GudangSetrikaStokOpname;
    use App\Models\GudangJahitReject;
    use App\Models\GudangControlReject;

    function rupiah($angka){
	
        $hasil_rupiah = "Rp " . number_format($angka,0,',','.');
        return $hasil_rupiah;
     
    }

    function checkPermission($id_menu='', $id_role='') {
        $id_role = $id_role == '' ? \Auth::user()->roleId : $id_role;

        return \DB::table('permission')
                    ->where('roleId','=',$id_role)
                    ->where('menuId','=',$id_menu)
                    ->count()>0?1:0;
    }

    function checkFitur($menu='', $id_role='', $check='') {
        $id_role = $id_role == '' ? \Auth::user()->roleId : $id_role;
        $menu = \DB::table('mst_menu')->where('nama','=',$menu)->first();
        
        $permission =  \DB::table('permission')
                    ->where('roleId','=',$id_role)
                    ->where('menuId','=',$menu->id)
                    ->first();
            
        switch ($check) {
            case 'create':
                return $permission->isCreate == 1?true:false;
                break;

            case 'update':
                return $permission->isUpdate == 1?true:false;
                break;

            case 'delete':
                return $permission->isDelete == 1?true:false;
                break;            
        }
    }

    function notifMenu($id)
    {
        $user = \Auth::user()->roleId;
        $mains = \DB::table('mst_menu')->where('id','=', $id)->first();
        $notif = [];

        if ($mains->nama == "PPIC") { //PPIC Gudang Request
            if ($user == 9) {
                $PPIC = PPICGudangRequest::where('statusDiterima', 1)->get();               
                if (count($PPIC) != 0) {
                    $notif[5] = count($PPIC);
                }
                $notifRequest = GudangPotongRequest::where('statusDiterima', 0)->get();            
                if (count($notifRequest) != 0) {
                    $notif[36] = count($notifRequest);
                }
            }
            
            if (count($notif) != 0) {
                return $notif;
            }
            
        }

        if ($mains->nama == "Purchase") {
            if ($user == 7) { //KDeptProd
                $KDeptProd = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', 0)->where('isKaDeptPO', 0)->get();            
                if (count($KDeptProd) != 0) {
                    $notif[8] = count($KDeptProd);
                    return $notif;
                }
            }
            if ($user == 6) { //KDeptPO
                $CNotif = 0;
                $prosesPO = [];
                $KDeptPO = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', '!=', 0)->where('isKaDeptPO', 0)->get();  
                $checkPO = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', '!=', 0)->where('isKaDeptPO', '!=', 0)->get();  
                foreach ($checkPO as $detail) {
                    if ($detail->kode == "-") {
                        $prosesPO[] = $detail;
                    }
                }
                                
                if (count($KDeptPO) != 0) {
                    $CNotif += count($KDeptPO);
                }
                if (count($prosesPO) != 0) {
                    $CNotif += count($prosesPO);
                }
                
                if ($CNotif != 0) {
                    $notif[8] = $CNotif;
                    return $notif;
                }

            }
            if ($user == 3 ) { //KDivPO
                $KDivPO = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', 0)->where('isKaDivFin', 0)->get();  
                if (count($KDivPO) != 0) {
                    $notif[9] = count($KDivPO);
                    return $notif;
                }
            }
            if ($user == 5) { //KDivFin
                $KDivFin = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', '!=', 0)->where('isKaDivFin', 0)->get();  
                if (count($KDivFin) != 0) {
                    $notif[9] = count($KDivFin);
                    return $notif;
                }
            }   

            // if (count($notif) != 0) {
            //     return $notif;
            // }
        }

        if ($mains->nama == "Gudang Bahan Baku") {
            if ($user == 7|| $user == 10) { //Gudang Rajut Masuk
                $countNotif = 0;
                $notifRajut = GudangRajutMasuk::where('statusDiterima', 0)->get();          
                $notifCompact = GudangCompactMasuk::where('statusDiterima', 0)->get();  
                $notifInspeksi = GudangInspeksiMasuk::where('statusDiterima', 0)->get();  
                $notifPPICReq = PPICGudangRequest::where('statusDiterima', 0)->get();  

                if (count($notifRajut) != 0){
                    $countNotif += count($notifRajut);
                } 
                if (count($notifCompact) != 0){
                    $countNotif += count($notifCompact);
                } 
                if (count($notifInspeksi) != 0) {
                    $countNotif += count($notifInspeksi);
                }

                if ($countNotif != 0) {
                    $notif[16] = $countNotif;
                }

                if (count($notifPPICReq) != 0) {
                    $notif[14] = count($notifPPICReq);
                }
                
            }          

            if (count($notif) != 0) {
                return $notif;
            }
        }

        if ($mains->nama == "Gudang Rajut") {
            if ($user == 7 || $user == 13) { //Gudang Rajut Keluar
                $notifRajutKeluar = GudangRajutKeluar::where('statusDiterima', 0)->get();      
                if (count($notifRajutKeluar) != 0) {
                    $notif[19] = count($notifRajutKeluar);      
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Cuci") {
            if ($user == 7 || $user == 14 ) { //Gudang Cuci Keluar
                $notifCuciKeluar = GudangCuciKeluar::where('statusDiterima', 0)->get();            
                if (count($notifCuciKeluar) != 0) {
                    $notif[23] = count($notifCuciKeluar);
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Compact") {
            if ($user == 7 || $user == 15) { //Gudang Compact Keluar
                $notifCompactKeluar = GudangCompactKeluar::where('statusDiterima', 0)->get();            
                if (count($notifCompactKeluar) != 0) {
                    $notif[27] = count($notifCompactKeluar);
                    return $notif;
                }
            }
            
        }

        if ($mains->nama == "Gudang Inspeksi") {
            if ($user == 7 || $user == 16) { //Gudang Inspeksi Keluar
                $notifInspeksiKeluar = GudangInspeksiKeluar::where('statusDiterima', 0)->get();            
                if (count($notifInspeksiKeluar) != 0) {
                    $notif[31] = count($notifInspeksiKeluar);
                    return $notif;
                }
            }
            
        }

        if ($mains->nama == "Gudang Potong") {
            if ($user == 7 || $user == 17) { //Gudang Potong Keluar
                $notifKeluar = GudangPotongKeluar::where('statusDiterima', 0)->get();   
                if (count($notifKeluar) != 0) {
                    $notif[37] = count($notifKeluar);
                }        
            }          
            
            return $notif;
        }

        if ($mains->nama == "Gudang Jahit") {
            if ($user == 7 || $user == 18) { //Gudang Jahit
                $notifJahitMasuk = GudangJahitMasuk::where('statusProses', 0)->get();            
                $notifJahitReject = GudangJahitReject::where('statusProses', 0)->get();            
                if (count($notifJahitMasuk) != 0) {
                    $notif[41] = count($notifJahitMasuk);
                }

                if (count($notifJahitReject) != 0) {
                    $notif[43] = count($notifJahitReject);
                }
            }
            
            return $notif;
        }
        

        if ($mains->nama == "Gudang Batil") {
            if ($user == 7 || $user == 19) { //Gudang Batil
                $notifBatilMasuk = GudangBatilMasuk::where('statusDiterima', 0)->get(); 
                $notifBatilReject = GudangJahitReject::where('gudangRequest', 'Gudang Batil')->where('statusProses', 2)->get();           
                if (count($notifBatilMasuk) != 0) {
                    $notif[46] = count($notifBatilMasuk);
                }

                if (count($notifBatilReject) != 0) {
                    $notif[48] = count($notifBatilReject);
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Control") {
            if ($user == 7 || $user == 20) { //Gudang Control
                $notifControlMasuk = GudangControlMasuk::where('statusDiterima', 0)->get();            
                $notifControlReject = GudangControlReject::where('statusProses', 0)->get(); 
                $notifControlJahitPotong = GudangJahitReject::where('gudangRequest', 'Gudang Control')->where('statusProses', 2)->get();                      
                $notifControl = 0;


                if (count($notifControlMasuk) != 0) {
                    $notif[51] = count($notifControlMasuk);
                }

                if (count($notifControlReject) != 0) {
                    $notifControl += count($notifControlReject);
                }

                if (count($notifControlJahitPotong) != 0) {
                    $notifControl += count($notifControlJahitPotong);
                }

                if($notifControl != 0){
                    $notif[53] = $notifControl;
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Setrika") {
            if ($user == 7 || $user == 21) { //Gudang Setrika
                $notifSetrikaMasuk = GudangSetrikaMasuk::where('statusDiterima', 0)->get(); 
                $notifSetrikaReject = GudangControlReject::where('gudangRequest', 'Gudang Setrika')->where('statusProses', 2)->get();            
                if (count($notifSetrikaMasuk) != 0) {
                    $notif[61] = count($notifSetrikaMasuk);
                }

                if (count($notifSetrikaReject) != 0) {
                    $notif[63] = count($notifSetrikaReject);
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Packing") {
            if ($user == 7 || $user == 22) { //Packing
                $notifPackingMasuk = GudangSetrikaStokOpname::where('kodeBarcode', null)->where('statusPacking', 0)->whereDate('tanggal', date('Y-m-d'))->groupBy('jenisBaju')->get();            
                if (count($notifPackingMasuk) != 0) {
                    $notif[56] = count($notifPackingMasuk);
                }
            }
            
            return $notif;
        }
    }
?>