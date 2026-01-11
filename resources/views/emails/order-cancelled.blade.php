<h3>Hallo {{ $order->receiver_name }}</h3>

<h4 style="color:red;">PESANAN DIBATALKAN</h4>

<p>
    Terimakasih telah membuat pesanan, namun pesanan Anda telah dibatalkan.
</p>

<p><strong>Produk dengan pesanan:</strong></p>
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
    Pesanan bisa dibatalkan oleh Anda sendiri atau oleh Admin.
    Jika dibatalkan oleh Admin, kemungkinan alamat tidak valid atau stok habis.
</p>

<p>
    Mohon maaf atas ketidaknyamanannya dan terimakasih atas pengertiannya 🙏
</p>
