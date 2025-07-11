<h2>Pesanan Anda Berhasil Dibuat</h2>
<p>Terima kasih telah berbelanja di Toko Furniture.</p>
<p><strong>Total:</strong> Rp {{ number_format($transaction->total,0,',','.') }}</p>
<p><strong>Status:</strong> {{ $transaction->status }}</p>
<p><strong>Alamat Pengiriman:</strong> {{ $transaction->address }}</p>
<p>Silakan lakukan pembayaran dan konfirmasi melalui aplikasi.</p> 