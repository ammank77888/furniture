<h2>Status Pesanan Anda Diperbarui</h2>
<p>Pesanan Anda dengan total <strong>Rp {{ number_format($transaction->total,0,',','.') }}</strong> kini berstatus <strong>{{ $transaction->status }}</strong>.</p>
<p>Alamat Pengiriman: {{ $transaction->address }}</p> 