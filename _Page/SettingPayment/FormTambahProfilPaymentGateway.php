
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label" for="env_name">Nama Profil</label>
    </div>
    <div class="col-md-8">
        <input type="text" name="env_name" id="env_name" class="form-control" required>
        <small>
            <code class="text text-grayish">
                Nama profil pengaturan (Contoh: Staging, Development, Production)
            </code>
        </small>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label" for="urll_call_back">URL Call Back</label>
    </div>
    <div class="col-md-8">
        <input type="text" name="urll_call_back" id="urll_call_back" class="form-control">
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
        <input type="text" name="url_status" id="url_status" class="form-control" placeholder="https://">
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
        <label class="form-label" for="id_marchant">ID Merchant</label>
    </div>
    <div class="col-md-8">
        <input type="text" name="id_marchant" id="id_marchant" class="form-control" required>
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
        <input type="text" name="client_key" id="client_key" class="form-control" required>
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
        <input type="text" name="server_key" id="server_key" class="form-control" required>
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
        <input type="text" name="snap_url" id="snap_url" class="form-control" required>
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
        <label class="form-label" for="production">Environment</label>
    </div>
    <div class="col-md-8">
        <select name="production" id="production"  class="form-control">
            <option value="false">Sanbox</option>
            <option value="true">Production</option>
        </select>
        <small>
            <code class="text text-grayish">
                Diisi dengan <b>Snap URL</b> yang sesuai pada dokumentasi yang disediakan provider.
            </code>
        </small>
    </div>
</div>
<div class="row mb-3 mt-4">
    <div class="col-md-4">
        <label class="form-label" for="status_profil">Status</label>
    </div>
    <div class="col-md-8">
        <select name="status" id="status_profil" class="form-control">
            <option value="">-Pilih-</option>
            <option value="active">Active</option>
            <option value="none">None</option>
        </select>
        <small>
            <code class="text text-grayish">
                Apabila anda mengaktifkan pengaturan ini maka semua transaksi akan menggunakan metode pembayaran yang disediakan provider payment gateway.
            </code>
        </small>
    </div>
</div>