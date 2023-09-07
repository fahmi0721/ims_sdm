
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="Id" id="Id" value="">
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3">Nama Lengkap</label>
                <div class="col-md-4 col-sm-5">
                    <input type='text' class='form-control FormInput'name='Nama' id='Nama' placeholder='Nama Lengkap' />
                    
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3">Jabatan</label>
                <div class="col-md-4 col-sm-5">
                    <input type='text' class='form-control FormInput'name='Jabatan' id='Jabatan' placeholder='Jabatan' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3">Username</label>
                <div class="col-md-4 col-sm-5">
                    <input type='text' class='form-control FormInput'name='Username' id='Username' placeholder='Username' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3">Password</label>
                <div class="col-md-4 col-sm-5">
                    <input type='text' class='form-control FormInput'name='Password' id='Password' placeholder='Password' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3">Level</label>
                <div class="col-md-4 col-sm-5">
                    <select class='form-control' name='Level' id='Level'>
                        <option value=''>..:: Pilih Level ::..</option>
                        <option value='0'>Admin</option>
                        <option value='1'>Member</option>
                    </select>
                </div>
            </div>
           
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                    <button type="button" onclick="Clear()" class="btn btn-sm btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
                </div>
            </div>
        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <p>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
            </p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama Lengkap</th>
                            <th>Jabatan</th>
                            <th>Level</th>
                            <th>Username</th>
                            <th width="8%">Status</th>
                            <th width="8%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                </table>
            </div>
            </div>
            
        </div> 

    </div>
    



    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>


<div class='modal fade in' id='modal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Konfirmasi Delete</h5>
</div>
<div class='modal-body'>

    <div id="proses_del"></div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="SubmitData()"><i class="fa fa-check-square"></i> &nbsp;Hapus</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="Clear()"><i class="fa fa-mail-reply"></i> &nbsp;Batal</button>
    </div>

</div>
</div>
</div>
</div>