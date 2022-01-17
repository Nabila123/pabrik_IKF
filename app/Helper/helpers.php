<?php
    function checkPermission($id_menu='', $id_role='') {
        $id_role = $id_role == '' ? \Session::get('id_role') : $id_role;
        
        return \DB::table('permission')
                    ->where('roleId','=',$id_role)
                    ->where('menuId','=',$id_menu)
                    ->count()>0?1:0;
    }
?>