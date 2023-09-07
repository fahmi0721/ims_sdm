
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
                <label class="control-label col-md-2 col-sm-3">Nama User</label>
                <div class="col-md-4 col-sm-5">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-user'></i></span>
                        <input type='hidden' class='form-control FormInput'name='IdUser' id='IdUser' />
                        <input type='text' data-toggle='tooltip' title='Press Enter To Select' autocomplete=off class='form-control FormInput'name='Nama' id='Nama' placeholder='Nama User' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3">Menu</label>
                <div class="col-md-4 col-sm-5">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-folder'></i></span>
                        <input type='hidden'  class='form-control FormInput' name='IdMenu' id='IdMenu' placeholder='' />
                        <input type='text' autocomplete=off class='form-control FormInput' name='NamaMenu' id='NamaMenu' placeholder='Nama Menu' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3">Level Akses</label>
                <div class="col-md-4 col-sm-5">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-toggle-down'></i></span>
                        <select class='form-control FormInput' name='Status' id='Status'>
                            <option value=''>..:: Pilih Level ::..</option>
                            <option value='0'>Publisher</option>
                            <option value='1'>Author</option>
                            <option value='2'>Guest</option>
                        </select>
                    </div>
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
                            <th>Nama User</th>
                            <th>Jabatan</th>
                            <th width="10%"><center>Menu Akses</center></th>
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

<div class='modal fade in' id='modal_data' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal1" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Detail Akses</h5>
</div>
<div class='modal-body'>
    
    <div class='table-responsive'>
        <table class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th width='5%' class='text-center'>No</th>
                    <th>Menu</th>
                    <th>Level</th>
                    <th class='text-center' width='20%'>Level</th>
                </tr>
            </thead>
            <tbody id='data_show'></tbody>
        </table>
    </div>


</div>
</div>
</div>
</div>