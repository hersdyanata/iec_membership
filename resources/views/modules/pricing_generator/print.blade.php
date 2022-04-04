<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @font-face {
            font-family: SourceSansPro;
            src: url(/assets/global/fonts/SourceSansPro-Regular.ttf);
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #EA7E24;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-family: SourceSansPro;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #logo img {
            width: 85px;
            height: 85px;
        }

        #company {
            float: right;
            text-align: right;
            font-size: 1em;
        }


        #details {
            margin-bottom: 20px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #EA7E24;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            /* font-weight: normal; */
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #EA7E24;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 5px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3 {
            color: #EA7E24;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #EA7E24;
        }

        table .desc {
            text-align: left;
            padding-left: 20px;
            padding-bottom: 10px;
            padding-top: 10px;
        }

        table .unit {
            text-align: right;
            background: #DDDDDD;
            padding-right: 20px;
        }

        table .qty {
            text-align: right;
            padding-right: 20px;
        }

        table .total {
            text-align: right;
            background: #DDDDDD;
            color: #00000;
            padding-right: 20px;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #EA7E24;
            font-size: 1.4em;
            border-top: 1px solid #EA7E24;

        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 0px;
            /* border-left: 6px solid #EA7E24;   */
        }

        #notices .notice {
            font-size: 1.0em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('assets/global/images/logo_ius_orange_noname.png') }}">
        </div>
        <div id="company">
            <h2 class="name"><strong>Indo Universal Spices</strong></h2>
            <div>Bojongmalaka Street, Kavling Agnes Block C, No. 13.</div>
            <div>Bandung, West Java, Indonesia</div>
            <div>Mobile: +62 878-3641-5796</div>
            <div>marketing@indouniversalspices.com</div>
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <h2 class="name"><strong>PRICE LIST</strong></h2>
                <div class="address">Printed at {{ date('M, d Y') }}</div>
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="no"><strong>#</strong></th>
                    <th class="desc"><strong>DESCRIPTION</strong></th>
                    <th class="unit"><strong>PRICE</strong></th>
                    <th class="qty"><strong>QUANTITY</strong></th>
                    <th class="total"><strong>TOTAL</strong></th>
                </tr>
            </thead>
            <tbody>
                @php $no = 0; @endphp
                @foreach ($items as $r)
                	@php 
						$no++;
					@endphp

					<tr>
						<td class="no">
							<center>{{ $no }}</center>
						</td>
						<td class="desc">
							<h3>{{ $r->komoditas_prefix }} - {{ $r->komoditas_nama }}</h3>
							{!! $r->komoditas_spesifikasi !!}
                            @isset($packing)
                                Packing: 
                                @php $separator = ','; @endphp
                                @foreach ($packing as $p)
                                    @if ($loop->last)
                                        @php $separator = ''; @endphp
                                    @endif

                                {{ $p->pack_label.' - '.$p->pack_size.' '.$p->pack_unit }} {!! $separator !!}
                                @endforeach
                            @endisset
						</td>
						<td class="unit">
							{{ $incoterms }} /MT: $ {{ number_format($r->prdetail_ton_usd) }}<br>
							Exw: $ {{ number_format(($r->prc_harga_supplier+$r->prc_margin_idr) / $r->prc_kurs,2) }} /kg
						</td>
						<td class="qty">{{ $r->prc_container_max_qty/1000 }} MT</td>
						<td class="total">$ {{ number_format($r->prdetail_ton_usd * ($r->prc_container_max_qty/1000)) }}</td>
					</tr>
                @endforeach
            </tbody>
        </table>
        <div id="notices">
            <div class="notice">
                <h3 class="name"><strong>NOTE</strong></h3>
                <ul>
                    <li>Another packaging may be charged additional cost.</li>
                    <li>Price may be changed without any notice because of fluctuation.</li>
                    <li>Shipping terms: {{ $incoterms }}.</li>
                </ul>
                {{-- <h3 class="name"><strong>PAYMENT TERMS</strong></h3>
                <ul>
                    <li>LC 100% at sight - Irrevocable.</li>
                    <li>T/T 50% in advance, 50% after original BL released.</li>
                </ul> --}}
            </div>
        </div>
    </main>
    <footer>
		This price list generated by system and not represented to offer letter.
	</footer>
</body>

</html>