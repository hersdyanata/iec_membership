<div class="text-left">
    <button type="button" onclick="tambah_produk()" class="btn btn-warning">Tambah Produk</button>
</div><br><br>
<table class="table datatable-show-all table-bordered table-hover" id="tableData">
    <thead>
        <tr>
            <th class="text-center" width="5%">#</th>
            <th width="10%">HS Code Induk</th>
            <th width="20%">Sub HS Code</th>
            <th width="30%">HS Code</th>
            <th width="10%" class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


<div id="modal_produk" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data produk merujuk pada HS Code</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <h6 class="font-weight-semibold">HS Code yang digunakan sebagai produk adalah kode yang berisikan 8 digit angka</h6>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>

                <hr>

                <form id="form_produk">
                    <fieldset class="mb-12">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Nama Supplier</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="supplier_nama" id="supplier_nama">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Alamat</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="supplier_alamat" id="supplier_alamat">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    function tambah_produk(){
        $('#modal_produk').modal('show');
    }
</script>