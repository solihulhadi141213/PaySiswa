<form action="javascript:void(0);" id="ProsesSettingPayment">
    <div class="row mb-3 mt-4">
        <div class="col-md-4">
            <label class="form-label" for="aktif_payment_gateway">Status Pengaturan</label>
        </div>
        <div class="col-md-8">
            <select name="aktif_payment_gateway" id="aktif_payment_gateway" class="form-control">
                <option <?php if($aktif_payment_gateway==""){echo "selected";} ?> value="">-Pilih-</option>
                <option <?php if($aktif_payment_gateway=="Ya"){echo "selected";} ?> value="Ya">Aktif</option>
                <option <?php if($aktif_payment_gateway=="Tidak"){echo "selected";} ?> value="Tidak">Tidak Aktif</option>
            </select>
            <small>
                <code class="text text-grayish">
                    Apabila anda mengaktifkan pengaturan ini maka semua transaksi akan menggunakan metode pembayaran yang disediakan provider payment gateway.
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="api_payment_url">URL API's Payment</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="api_payment_url" id="api_payment_url" class="form-control" required value="<?php echo "$api_payment_url"; ?>">
            <small>
                <code class="text text-grayish">
                    Arahkan ke URL dimana API Service Payment Gateway disimpan.
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="urll_call_back">URL Call Back</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="urll_call_back" id="urll_call_back" class="form-control" value="<?php echo "$urll_call_back"; ?>">
            <small>
                <code class="text text-grayish">
                    URL yang digunakan untuk memproses pembaharuan status transaksi. (Apabila tidak digunakan, silahkan kosongkan)
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="url_status">URL Status</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="url_status" id="url_status" class="form-control" value="<?php echo "$url_status"; ?>" placeholder="https://">
            <small>
                <code class="text text-grayish">
                    URL yang digunakan untuk meminta status transaksi berdasarkan Order ID
                    <ul>
                        <li>Sanbox : https://api.sandbox.midtrans.com</li>
                        <li>Production : https://api.midtrans.com</li>
                    </ul>
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="api_key">API Key</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="api_key" id="api_key" class="form-control" required value="<?php echo "$api_key"; ?>">
            <small>
                <code class="text text-grayish">
                    Kode akses yang digunakan untuk validasi penggunaan service payment gateway.
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="id_marchant">ID Merchant</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="id_marchant" id="id_marchant" class="form-control" required value="<?php echo "$id_marchant"; ?>">
            <small>
                <code class="text text-grayish">
                    Diisi dengan <b>ID Merchant</b> yang sesuai pada <i>Access Key</i> yang disediakan provider.
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="client_key">Client Key</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="client_key" id="client_key" class="form-control" required value="<?php echo "$client_key"; ?>">
            <small>
                <code class="text text-grayish">
                    Diisi dengan <b>Client Key</b> yang sesuai pada <i>Access Key</i> yang disediakan provider.
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="server_key">Server Key</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="server_key" id="server_key" class="form-control" required value="<?php echo "$server_key"; ?>">
            <small>
                <code class="text text-grayish">
                    Diisi dengan <b>Server Key</b> yang sesuai pada <i>Access Key</i> yang disediakan provider.
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="snap_url">Snap URL</label>
        </div>
        <div class="col-md-8">
            <input type="text" name="snap_url" id="snap_url" class="form-control" required value="<?php echo "$snap_url"; ?>">
            <small>
                <code class="text text-grayish">
                    <b>Snap URL</b> sesuai pada dokumentasi yang disediakan provider.
                    <ul>
                        <li>Sanbox: https://app.sandbox.midtrans.com/snap/snap.js</li>
                        <li>Production: https://app.midtrans.com/snap/snap.js</li>
                    </ul>
                </code>
            </small>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label" for="production">Environment Status</label>
        </div>
        <div class="col-md-8">
            <select name="production" id="production"  class="form-control">
                <option <?php if($production=="false"){echo "selected";} ?> value="false">Sanbox</option>
                <option <?php if($production=="true"){echo "selected";} ?> value="true">Production</option>
            </select>
            <small>
                <code class="text text-grayish">
                    Diisi dengan <b>Snap URL</b> yang sesuai pada dokumentasi yang disediakan provider.
                </code>
            </small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="NotificationError">

        </div>
    </div>
    <div class="row mt-4 mb-3">
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-md btn-primary btn-rounded" id="NotifikasiSimpanSettingPayment">
                <i class="bi bi-save"></i> Simpan Pengaturan
            </button>
        </div>
    </div>
</form>