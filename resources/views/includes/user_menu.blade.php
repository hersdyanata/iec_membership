@php
    $collection = collect(session('all_menus'));
@endphp
@foreach ($dividers as $d)
    @php
        $parentMenu = $collection->where('menu_div_id', $d['div_id'])->whereNull('menu_parent_id');
    @endphp
    <li class="nav-item-header">
        <div class="text-uppercase font-size-xs line-height-xs">{{ $d['div_nama_ina'] }}</div> <i class="icon-menu" title="{{ $d['div_nama_ina'] }}"></i>
    </li>

    @foreach ($parentMenu as $m)
        @php
            $childMenu1 = $collection->where('menu_parent_id', $m['menu_id']);
            
            if($m['menu_controller'] != '#'){
                if($m['menu_route_group'] != null){
                    $route_parent = route($m['menu_route_group'].'.'.$m['menu_route_name'].'.index');
                }else{
                    $route_parent = route($m['menu_route_name'].'.index');
                }
            }else{
                $route_parent = '/#';
            }
        @endphp
        <li class="nav-item" id="navigate_{{ $m['menu_id'] }}">
            <a href="{{ $route_parent }}" class="nav-link {{ (Request::segment(2) === $m['menu_route_name'] || Request::segment(1) === $m['menu_route_name']) ? "active" : "" }}">
                <i class="{{ $m['menu_icon'] }}"></i>
                <span>
                    {{ $m['menu_nama_ina'] }}
                </span>
            </a>

            @if(count($childMenu1) > 0)
                <script>
                    $('#navigate_{{ $m['menu_id'] }}').addClass('nav-item-submenu');
                </script>

                <ul class="nav nav-group-sub" data-submenu-title="{{ $m['menu_nama_ina'] }}">
                @foreach ($childMenu1 as $c)
                    @php
                        if($c['menu_controller'] != '#'){
                            $route_child1 = route($c['menu_route_group'].'.'.$c['menu_route_name'].'.index');
                        }else{
                            $route_child1 = '/#';
                        }

                        $childMenu2 = $collection->where('menu_parent_id', $c['menu_id']);
                    @endphp
                    <li class="nav-item" id="navigate2_{{ $c['menu_id'] }}">
                        @php
                            if(Request::segment(2) == $c['menu_route_name']){
                                $active1 = 'active';
                            }else{
                                $active1 = '';
                            }
                        @endphp
                        <a href="{{ $route_child1 }}" class="nav-link {{ $active1 }}" data-active1="{{ $active1 }}" id="child1_{{ $c['menu_id'] }}">
                            <i class="{{ $c['menu_icon'] }}"></i>
                            <span>
                                {{ $c['menu_nama_ina'] }}
                            </span>
                        </a>
                        @if ($active1 != '')
                            <script>
                                $('#navigate_{{ $m['menu_id'] }}').addClass('nav-item-expanded nav-item-open');
                            </script>
                        @endif

                        @if (count($childMenu2) > 0)
                            <script>
                                $('#navigate2_{{ $c['menu_id'] }}').addClass('nav-item-submenu')
                            </script>
                            <ul class="nav nav-group-sub">
                                @foreach ($childMenu2 as $c2)
                                    @php
                                        if($c2['menu_controller'] != '#'){
                                            $route_child2 = route($c2['menu_route_group'].'.'.$c2['menu_route_name'].'.index');
                                        }else{
                                            $route_child2 = '/#';
                                        }
                                    @endphp
                                    <li class="nav-item">
                                        @php
                                            if(Request::segment(1) == $c2['menu_route_name']){
                                                $active2 = 'active';
                                            }else{
                                                $active2 = '';
                                            }
                                        @endphp
                                        <a href="{{ $route_child2 }}" class="nav-link {{ $active2 }}" data-active2="{{ $active2 }}" id="child2_{{ $c2['menu_id'] }}">
                                            <i class="{{ $c2['menu_icon'] }}"></i>
                                            <span>
                                                {{ $c2['menu_nama_ina'] }}
                                            </span>
                                        </a>
                                        @if ($active2 != '')
                                            <script>
                                                $('#navigate_{{ $m['menu_id'] }}').addClass('nav-item-expanded nav-item-open');
                                                $('#navigate2_{{ $c['menu_id'] }}').addClass('nav-item-expanded nav-item-open');
                                            </script>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                </ul>
            @endif
        </li>
    @endforeach
@endforeach