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

        if ($user == 8 || $user == 38) { //KDeptProd
        //     $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', 0)->where('isKaDeptPO', 0)->get();            
        // }elseif ($user == 7 || $user == 38) { //KDeptPO
        //     $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', 1)->where('isKaDeptPO', 0)->get();  
        // }elseif ($user == 4 || $user == 38) { //KDivPO
            $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', 0)->where('isKaDivFin', 0)->get();  
        // }elseif ($user == 6 || $user == 38) { //KDivFin
        //     $purchaseNotif = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', 1)->where('isKaDivFin', 0)->get();  
        }

        return $purchaseNotif;
    }

    function notifMenu($id)
    {
        $user = \Auth::user()->roleId;
        $mains = \DB::table('mst_menu')->where('id','=', $id)->first();
        $notif = [];

        if ($mains->nama == "PPIC" && $user == 38) { //PPIC Gudang Request
            $notif = PPICGudangRequest::where('statusDiterima', 1)->get();               
            if (count($notif) != 0) {
                $notif[5] = $notif;
                return $notif;
            }
        }

        if ($mains->nama == "Purchase") {
            if ($user == 38) { //KDeptProd
                $notif = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', 0)->where('isKaDeptPO', 0)->get();            
                if (count($notif) != 0) {
                    $notif[8] = $notif;
                }
            }
            if ($user == 38) { //KDeptPO
                $notif = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('isKaDeptProd', '!=', 0)->where('isKaDeptPO', 0)->get();  
                if (count($notif) != 0) {
                    $notif[8] = $notif;
                }
            }
            if ($user == 38 ) { //KDivPO
                $notif = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', 0)->where('isKaDivFin', 0)->get();  
                if (count($notif) != 0) {
                    $notif[9] = $notif;
                }
            }
            if ($user == 8) { //KDivFin
                $notif = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('isKaDivPO', '!=', 0)->where('isKaDivFin', 0)->get();  
                if (count($notif) != 0) {
                    $notif[9] = $notif;
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
            }          

            // dd($notif);
            if (count($notif) != 0) {
                $notif[16] = $notif;
                return $notif;
            }

        }

        if ($mains->nama == "Gudang Rajut") {
            if ($user == 38) { //Gudang Rajut Keluar
                $notif = GudangRajutKeluar::where('statusDiterima', 0)->get();      
            }
            
            if (count($notif) != 0) {
                $notif[19] = $notif;      
                return $notif;
            }
        }

        if ($mains->nama == "Gudang Cuci") {
            if ($user == 38) { //Gudang Cuci Keluar
                $notif = GudangCuciKeluar::where('statusDiterima', 0)->get();            
            }
            
            if (count($notif) != 0) {
                $notif[23] = $notif;
                return $notif;
            }
        }

        if ($mains->nama == "Gudang Compact") {
            if ($user == 38) { //Gudang Compact Keluar
                $notif = GudangCompactKeluar::where('statusDiterima', 0)->get();            
            }
            
            if (count($notif) != 0) {
                $notif[27] = $notif;
                return $notif;
            }
        }

        if ($mains->nama == "Gudang Inspeksi") {
            if ($user == 38) { //Gudang Inspeksi Keluar
                $notif = GudangInspeksiKeluar::where('statusDiterima', 0)->get();            
            }
            
            if (count($notif) != 0) {
                $notif[31] = $notif;
                return $notif;
            }
        }

        if ($mains->nama == "Gudang Potong") {
            if ($user == 38) { //Gudang Potong Keluar
                $notifKeluar = GudangPotongKeluar::where('statusDiterima', 0)->get();            
            }

            if ($user == 38) { //Gudang Potong Request Bahan Jadi
                $notifRequest = GudangPotongRequest::where('statusDiterima', 0)->get();            
            }

            if (count($notifRequest) != 0) {
                $notif[36] = count($notifRequest);
            }
            
            if (count($notifKeluar) != 0) {
                $notif[37] = count($notifKeluar);
            }
            return $notif;
        }
    }
?>