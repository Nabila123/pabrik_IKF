<!-- need to remove -->
<?php
    $mains = \DB::table('mst_menu')
                ->where('parentId','=',0)
                ->where('isActive','=',1)
                ->orderBy('urutan','asc')
                ->get();
?>

@foreach($mains as $main)
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
                    <p>
                        {{ $main->nama }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
            
                <ul class="nav nav-treeview">
                    @foreach($menus as $menu)
                            <li class="nav-item">
                                <a href="{{ route($menu->alias) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ $menu->nama }}</p>
                                </a>
                            </li>
                    @endforeach
                </ul>
            </li>
        @endif
@endforeach

<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Admin Rajut
        </p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Admin Inspeksi
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Admin Potong
        </p>
    </a>
</li>
<li class="nav-item">
</li>
