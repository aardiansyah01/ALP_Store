<h3>Hallo {{ $order->receiver_name }}</h3>

<p>
    Terimakasih telah membuat pesanan.
    Pesanan Anda sudah kami terima dan akan segera kami kirim ke alamat berikut:
</p>

<p>
    Nama   :{{ $order->receiver_name }}<br>
    Kota   :{{ $order->city }}<br>
    Desa   :{{ $order->village }}<br>
    Dusun  :{{ $order->dusun }}<br>
    RT {{ $order->rt }} / RW {{ $order->rw }}<br>
    Alamat :{{ $order->address }}
</p>

<hr>

<p><strong>Produk yang dipesan:</strong></p>
<ul>
@foreach($order->items as $item)
    <li>
        {{ $item->product->name }} —
        Size {{ $item->size }} —
        Qty {{ $item->qty }}
    </li>
@endforeach
</ul>

<p>
    Mohon segera batalkan pesanan jika alamat tidak sesuai dan lakukan checkout ulang
    dengan alamat yang benar.
</p>

<p>
    Terimakasih sudah berbelanja 🙏
</p>