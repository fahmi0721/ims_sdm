<?php
if(isset($_GET['key'])){
    $Key = $_GET['key'];
    $From = $_GET['From'];
    
?>
<form id='FormData'>
    <input type='hidden' id='bData' value='<?php echo $_GET['key']; ?>' />
</form>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Rekap Data Percabang</h3>
        <div class='pull-right box-tools'>
            <div class='btn-group' id='BtnControl'>
                <a target='_blank' href='Export/TenagaKerja.php?aksi=<?php echo $From; ?>&Id=<?php echo $Key; ?>' class='btn btn-sm btn-success' title='Expor Data Tenaga Kerja' data-toggle='tooltip'><i class='fa fa-file-excel-o'></i> Export</a>
                <a href='#' class='btn btn-sm btn-danger btn-roolback' title='Kembali kehalaman sebelumnya' data-toggle='tooltip'><i class='fa fa-mail-reply'></i> Kembali</a>
            </div>
        </div>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <div class='row'>
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="JumL">0</h3>
                        <p>LAKI LAKI</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-male"></i>
                    </div>
                    <a href="#" class="small-box-footer"> <i class="fa fa-key"></i></a>
                </div>
            </div>
            
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-maroon disabled">
                    <div class="inner">
                        <h3 id='JumP'>0</h3>
                        <p>PEREMPUAN</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-female"></i>
                    </div>
                    <a href="#" class="small-box-footer"> <i class="fa fa-key"></i></a>
                </div>
            </div>
                
        </div>
    </div>
    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>
<hr>
<div id='ShowData'>
    <div class='row' class='box-toll'>
        <div class='col-md-1'>
            <select class='form-control' id='RowPage' onchange='LoadData()'>
                <option value='12'>12</option>
                <option value='24'>24</option>
                <option value='48'>48</option>
                <option value='60'>60</option>
                <option value='100'>100</option>
            </select>
        </div>
        <div class='col-md-11'>
            <div class='pull-right' style='position:relative;top:-22px'>
                <div class='Paging'></div>
                
            </div>
        </div>
    </div>

    <div id='TampilData'></div>

    <div class='row' class='box-toll'>
        <div class='col-md-12'>
            <center><div class='Paging'></div></center>
            <center><span id='PagingInfo'></span></center>
            <center><span id='PagingTime'></span></center>
        </div>
    </div>
    <div class="overlay LoadingState" >
            <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>
</div>
<?php }else{ 
    echo "<div class='error-page'>
	        <h2 class='headline text-yellow' style='margin-top:-15px;'> 404</h2>

	        <div class='error-content'>
	          <h2><i class='fa fa-warning text-yellow'></i> Oops! Page not found.</h2>
	          <h5>Halaman Yang Anda Pilih Tidak Ditemukan Oleh Sistem. Silahkan Hubungi Administrator.</h5>
	        </div>
	      </div>";   
}?>
