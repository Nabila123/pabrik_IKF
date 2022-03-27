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

    function checkPermission($id_menu='', $id_role='') {
        $id_role = $id_role == '' ? \Session::get('id_role') : $id_role;
        
        return \DB::table('permission')
                    ->where('roleId','=',$id_role)
                    ->where('menuId','=',$id_menu)
                    ->count()>0?1:0;
    }


    function notifApprovePurchase()
    {
        $user = \Auth::user()->roleId;
        $purchaseNotif = [];

        if ($user == 8) { //KDeptProd
            $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', 0)->where('isKaDeptPO', 0)->get();            
        }
        if ($user == 7) { //KDeptPO
            $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', 1)->where('isKaDeptPO', 0)->get();  
        }
        if ($user == 4) { //KDivPO
            $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', 0)->where('isKaDivFin', 0)->get();  
        }
        if ($user == 6) { //KDivFin
            $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', 1)->where('isKaDivFin', 0)->get();  
        }

        return $purchaseNotif;
    }

    function notifMenu($id)
    {
        $user = \Auth::user()->roleId;
        $mains = \DB::table('mst_menu')->where('id','=', $id)->first();
        $notif = [];

        if ($mains->nama == "PPIC" && $user == 38) { //PPIC Gudang Request
            $PPIC = PPICGudangRequest::where('statusDiterima', 1)->get();               
            if (count($PPIC) != 0) {
                $notif[5] = count($PPIC);
            }
            return $notif;
        }

        if ($mains->nama == "Purchase") {
            if ($user == 8) { //KDeptProd
                $KDeptProd = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', 0)->where('isKaDeptPO', 0)->get();            
                if (count($KDeptProd) != 0) {
                    $notif[8] = count($KDeptProd);
                }
            }
            if ($user == 7) { //KDeptPO
                $KDeptPO = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', '!=', 0)->where('isKaDeptPO', 0)->get();  
                if (count($KDeptPO) != 0) {
                    $notif[8] = count($KDeptPO);
                }
            }
            if ($user == 4 ) { //KDivPO
                $KDivPO = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', 0)->where('isKaDivFin', 0)->get();  
                if (count($KDivPO) != 0) {
                    $notif[9] = count($KDivPO);
                }
            }
            if ($user == 6) { //KDivFin
                $KDivFin = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', '!=', 0)->where('isKaDivFin', 0)->get();  
                if (count($KDivFin) != 0) {
                    $notif[9] = count($KDivFin);
                }
            }   

            if (count($notif) != 0) {
                return $notif;
            }
        }

        if ($mains->nama == "Gudang Bahan Baku") {
            if ($user == 38) { //Gudang Rajut Masuk
                $notifRajut = GudangRajutMasuk::where('statusDiterima', 0)->get();          
                $notifCompact = GudangCompactMasuk::where('statusDiterima', 0)->get();  
                $notifInspeksi = GudangInspeksiMasuk::where('statusDiterima', 0)->get();  

                if (count($notifRajut) != 0){
                    $notif[] = $notifRajut;
                } 
                if (count($notifCompact) != 0){
                    $notif[] = $notifCompact;
                } 
                if (count($notifInspeksi) != 0) {
                    $notif[] = $notifInspeksi;
                }
                
                if (count($notif) != 0) {
                    $notif[16] = $notif;
                }
            }          

            // dd($notif);
            if (count($notif) != 0) {
                return $notif;
            }
        }

        if ($mains->nama == "Gudang Rajut") {
            if ($user == 38) { //Gudang Rajut Keluar
                $notifRajutKeluar = GudangRajutKeluar::where('statusDiterima', 0)->get();      
                if (count($notifRajutKeluar) != 0) {
                    $notif[19] = count($notifRajutKeluar);      
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Cuci") {
            if ($user == 38) { //Gudang Cuci Keluar
                $notifCuciKeluar = GudangCuciKeluar::where('statusDiterima', 0)->get();            
                if (count($notifCuciKeluar) != 0) {
                    $notif[23] = count($notifCuciKeluar);
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Compact") {
            if ($user == 38) { //Gudang Compact Keluar
                $notifCompactKeluar = GudangCompactKeluar::where('statusDiterima', 0)->get();            
                if (count($notifCompactKeluar) != 0) {
                    $notif[27] = count($notifCompactKeluar);
                    return $notif;
                }
            }
            
        }

        if ($mains->nama == "Gudang Inspeksi") {
            if ($user == 38) { //Gudang Inspeksi Keluar
                $notifInspeksiKeluar = GudangInspeksiKeluar::where('statusDiterima', 0)->get();            
                if (count($notifInspeksiKeluar) != 0) {
                    $notif[31] = count($notifInspeksiKeluar);
                    return $notif;
                }
            }
            
        }

        if ($mains->nama == "Gudang Potong") {
            if ($user == 38) { //Gudang Potong Keluar
                $notifKeluar = GudangPotongKeluar::where('statusDiterima', 0)->get();   
                if (count($notifKeluar) != 0) {
                    $notif[37] = count($notifKeluar);
                }        
            }

            if ($user == 38) { //Gudang Potong Request Bahan Jadi
                $notifRequest = GudangPotongRequest::where('statusDiterima', 0)->get();            
                if (count($notifRequest) != 0) {
                    $notif[36] = count($notifRequest);
                }
            }            
            
            return $notif;
        }

        if ($mains->nama == "Gudang Jahit") {
            if ($user == 38) { //Gudang Jahit
                $notifJahitMasuk = GudangJahitMasuk::where('statusProses', 0)->get();            
                if (count($notifJahitMasuk) != 0) {
                    $notif[41] = count($notifJahitMasuk);
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Batil") {
            if ($user == 38) { //Gudang Batil
                $notifBatilMasuk = GudangBatilMasuk::where('statusDiterima', 0)->get();            
                if (count($notifBatilMasuk) != 0) {
                    $notif[46] = count($notifBatilMasuk);
                }
            }
            
            return $notif;
        }

        if ($mains->nama == "Gudang Control") {
            if ($user == 38) { //Gudang Control
                $notifControlMasuk = GudangControlMasuk::where('statusDiterima', 0)->get();            
                if (count($notifControlMasuk) != 0) {
                    $notif[51] = count($notifControlMasuk);
                }
            }
            
            return $notif;
        }
    }
?>