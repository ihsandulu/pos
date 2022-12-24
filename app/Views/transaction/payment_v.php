<?php echo $this->include("template/header_v"); ?>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <?php if (!isset($_GET['user_id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-10";
                        } else {
                            $coltitle = "col-md-8";
                        } ?>
                        <div class="<?= $coltitle; ?>">
                            <h4 class="card-title"></h4>
                            <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                        </div>
                        <?php if (isset($_GET['report'])) { ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <a href="<?= site_url("saran"); ?>" class="btn btn-danger btn-block btn-lg" value="OK" style="">Suggestion</a>

                                </h1>
                            </form>
                        <?php } ?>
                        <?php if (!isset($_POST['new']) && !isset($_POST['edit']) && !isset($_GET['report'])) { ?>
                            <?php if (isset($_GET["user_id"])) { ?>
                                <form action="<?= site_url("user"); ?>" method="get" class="col-md-2">
                                    <h1 class="page-header col-md-12">
                                        <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                                    </h1>
                                </form>
                            <?php } ?>
                            <?php 
                            if (
                                (
                                    isset(session()->get("position_administrator")[0][0]) 
                                    && (
                                        session()->get("position_administrator") == "1" 
                                        || session()->get("position_administrator") == "2"
                                    )
                                ) ||
                                (
                                    isset(session()->get("halaman")['20']['act_create']) 
                                    && session()->get("halaman")['20']['act_create'] == "1"
                                )
                            ) { ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                    <input type="hidden" name="payment_id" />
                                </h1>
                            </form>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php if (isset($_POST['new']) || isset($_POST['edit'])) { ?>
                        <div class="">
                            <?php if (isset($_POST['edit'])) {
                                $namabutton = 'name="change"';
                                $judul = "Update Product";
                            } else {
                                $namabutton = 'name="create"';
                                $judul = "Add Product";
                            } ?>
                            <div class="lead">
                                <h3><?= $judul; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">   
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="supplier_id">Supplier:</label>
                                    <div class="col-sm-10">
                                        <?php
                                        $builder = $this->db->table("supplier")
                                            ->where("store_id",session()->get("store_id"))
                                            ->where("supplier_bill >","0");
                                        if(isset($_GET["supplier_id"])){
                                            $builder->where("supplier_id",$this->request->getGet("supplier_id"));
                                        }
                                        $supplier=$builder
                                            ->orderBy("supplier_name", "ASC")
                                            ->get();
                                        //echo $this->db->getLastQuery();
                                        ?>
                                        <select class="form-control select" id="supplier_id" name="supplier_id">
                                            <option value="0" <?= ($supplier_id == "0") ? "selected" : ""; ?>>Pilih Supplier</option>
                                            <?php
                                            foreach ($supplier->getResult() as $supplier) { ?>
                                                <option value="<?= $supplier->supplier_id; ?>" <?= ($supplier_id == $supplier->supplier_id) ? "selected" : ""; ?>><?= $supplier->supplier_name; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>   
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="payment_nominal">Nominal:</label>
                                    <div class="col-sm-10">
                                        <input type="number" autofocus class="form-control" id="payment_nominal" name="payment_nominal" placeholder="" value="<?= $payment_nominal; ?>">
                                    </div>
                                </div>                             
                                

                                <input type="hidden" name="payment_id" value="<?= $payment_id; ?>" />
                                <input type="hidden" name="payment_nominal_before" value="<?= $payment_nominal; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href=<?= site_url("payment"); ?>">Back</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <form class="form-inline" >
                            <label for="from">Dari:</label>&nbsp;
                            <input type="date" id="from" name="from" class="form-control">&nbsp;
                            <label for="to">Ke:</label>&nbsp;
                            <input type="date" id="to" name="to" class="form-control">&nbsp;
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                        <?php if ($message != "") { ?>
                            <div class="alert alert-info alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong><?= $message; ?></strong>
                            </div>
                        <?php } ?>

                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                                <thead class="">
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Store</th>
                                        <th>Supplier</th>
                                        <th>Payment No.</th>
                                        <th>Cashier</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $builder = $this->db
                                        ->table("payment")
                                        ->join("supplier", "supplier.supplier_id=payment.supplier_id", "left")
                                        ->join("store", "store.store_id=payment.store_id", "left")
                                        ->join("user", "user.user_id=payment.cashier_id", "left")
                                        ->where("payment.store_id",session()->get("store_id"));
                                    if(isset($_GET["from"])&&$_GET["from"]!=""){
                                        $builder->where("payment.payment_date >=",$this->request->getGet("from"));
                                    }
                                    if(isset($_GET["to"])&&$_GET["to"]!=""){
                                        $builder->where("payment.payment_date <=",$this->request->getGet("to"));
                                    }
                                    if(isset($_GET["purchase_id"])&&$_GET["purchase_id"]>0){
                                        $builder->where("payment.purchase_id",$this->request->getGet("purchase_id"));
                                    }
                                    $usr= $builder
                                        ->orderBy("payment_id", "DESC")
                                        ->get();
                                    // echo $this->db->getLastquery();die;
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) { ?>
                                        <tr>      
                                            <?php if (!isset($_GET["report"])) { ?>
                                                <td style="padding-left:0px; padding-right:0px;">                                                    
                                                    <?php 
                                                    if (
                                                        (
                                                            isset(session()->get("position_administrator")[0][0]) 
                                                            && (
                                                                session()->get("position_administrator") == "1" 
                                                                || session()->get("position_administrator") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['20']['act_update']) 
                                                            && session()->get("halaman")['20']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                        <input type="hidden" name="payment_id" value="<?= $usr->payment_id; ?>" />
                                                    </form>
                                                    <?php }?>
                                                    
                                                    <?php 
                                                    if (
                                                        (
                                                            isset(session()->get("position_administrator")[0][0]) 
                                                            && (
                                                                session()->get("position_administrator") == "1" 
                                                                || session()->get("position_administrator") == "2"
                                                            )
                                                        ) ||
                                                        (
                                                            isset(session()->get("halaman")['20']['act_delete']) 
                                                            && session()->get("halaman")['20']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="payment_id" value="<?= $usr->payment_id; ?>" />
                                                        <input type="hidden" name="supplier_id" value="<?= $usr->supplier_id; ?>" />
                                                        <input type="hidden" name="payment_nominal" value="<?= $usr->payment_nominal; ?>" />
                                                    </form>
                                                    <?php }?>
                                                </td>
                                            <?php } ?>
                                            <td><?= $usr->payment_date; ?></td>
                                            <td><?= $usr->store_name; ?></td>
                                            <td><?= $usr->supplier_name; ?></td>
                                            <td><?= $usr->payment_no; ?></td>
                                            <td><?= $usr->user_name; ?></td>
                                            <td><?= number_format($usr->payment_nominal,0,",","."); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>                        
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Payment";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>