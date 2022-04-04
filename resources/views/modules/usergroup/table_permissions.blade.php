@if(count($menus) > 0)
    @foreach ($menus as $r)
        @if ($loop->last)
            @php $koma = ''; @endphp
        @else
            @php $koma = ','; @endphp
        @endif
        
        {!! '<code>'.$r['menu_nama_ina'].'</code>'.$koma !!}
    @endforeach
@else
    <code>-</code>
@endif