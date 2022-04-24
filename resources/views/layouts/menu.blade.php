<!-- need to remove -->
<?php
    $mains = \DB::table('mst_menu')
                ->where('parentId','=',0)
                ->where('isActive','=',1)
                ->orderBy('urutan','asc')
                ->get();
?>

@foreach($mains as $main)
    @if(checkPermission($main->id,\Auth::user()->roleId))
        <?php
            $menus = \DB::table('mst_menu')
                    ->where('parentId','=',$main->id)
                    ->where('isActive','=',1)
                    ->orderBy('urutan','asc')
                    ->get();
        ?>
        @if(count($menus)==0)
            <li class="nav-item">
                <a href="{{ route($main->alias) }}" class="nav-link active">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        {{ $main->nama }}                        
                    </p>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-home"></i>
                    <p style="font-size: 14px">
                        {{ $main->nama }}
                        @if(notifMenu($main->id)) 
                            <sup class="badge bg">
                                <i class="fa fa-star" style="color: #48ff00;"></i>
                            </sup>
                        @endif
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
            
                <ul class="nav nav-treeview">
                    @foreach($menus as $menu)
                        @if(checkPermission($main->id,\Auth::user()->roleId))
                            <li class="nav-item">
                                <a href="{{ route($menu->alias) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p style="font-size: 14px">
                                        {{ $menu->nama }}
                                        @if($cekMenu = notifMenu($main->id)) 
                                            @if (isset($cekMenu[$menu->id]))
                                                <span class="badge bg" style="background-color: #1AAD19; ">
                                                    {{ $cekMenu[$menu->id] }}
                                                </span>
                                            @endif
                                        @endif
                                    </p>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif
    @endif
@endforeach
