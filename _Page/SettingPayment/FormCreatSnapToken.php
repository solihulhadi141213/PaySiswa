<div class="row mb-3 mt-4">
    <div class="col-md-12">
        <label class="form-label" for="kode_transaksi">
            <small>Kode Transaksi</small>
        </label>
        <div class="input-group">
            <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" required>
            <span class="input-group-text" id="inputGroupPrepend">
                <a href="javascript:void(0);" id="GenerateKodeTransaksi">
                    <code class="text text-success">Generate</code>
                </a>
            </span>
        </div>
        <small class="text text-grayish">
            Kode transaksi adalah kode unik yang merepresentasikan transaksi yang sedang berlangsung. (Maksimal 36 karakter)
        </small>
    </div>
</div>
<div class="row mb-3 mt-4">
    <div class="col-md-12">
        <label class="form-label" for="order_id">
            <small>Order ID</small>
        </label>
        <div class="input-group">
            <input type="text" name="order_id" id="order_id" class="form-control" required>
            <span class="input-group-text" id="inputGroupPrepend">
                <a href="javascript:void(0);" id="GenerateOrderId">
                    <code class="text text-success">Generate</code>
                </a>
            </span>
        </div>
        <small class="text text-grayish">
            ID transaksi unik yang hanya dapat digunakan satu kali pada saat meminta kode token snap. 
            Karakter yang diperbolehkan adalah Alfanumerik, tanda hubung(-), garis bawah(_), tanda gelombang (~), dan titik (.) String, maksimal 50.
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <label class="form-label" for="gross_amount">
            <small>Jumlah Tagihan (Rp)</small>
        </label>
        <input type="text" name="gross_amount" id="gross_amount" class="form-control" required>
        <small class="text text-grayish">
            Diisi dengan nilai uang yang akan dibayarkan.
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <small>Nama Pelanggan</small>
    </div>
    <div class="col-6">
        <input type="text" name="first_name" id="first_name" class="form-control" required>
        <label for="first_name">
            <small class="text text-grayish">Nama Depan</small>
        </label>
    </div>
    <div class="col-6">
        <input type="text" name="last_name" id="last_name" class="form-control">
        <label for="last_name">
            <small class="text text-grayish">Nama Belakang</small>
        </label>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <label class="form-label" for="email">
            <small>Email</small>
        </label>
        <input type="email" name="email" id="email" class="form-control" required>
        <small class="text text-grayish">
            Provider akan mengirimkan email notifikasi dari status pembayaran yang telah dilakukan.
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <label class="form-label" for="phone">Kontak/HP</label>
        <input type="text" name="phone" id="phone" class="form-control" required>
        <small class="text text-grayish">
            Informasi nomor kontak pelanggan.
        </small>
    </div>
</div>

<script>
    
</script>