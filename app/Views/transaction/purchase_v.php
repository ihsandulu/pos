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
                                    isset(session()->get("halaman")['18']['act_create']) 
                                    && session()->get("halaman")['18']['act_create'] == "1"
                                )
                            ) { ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                    <input type="hidden" name="purchase_id" />
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
                                        $supplier = $this->db->table("supplier")
                                            ->where("store_id",session()->get("store_id"))
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
                                

                                <input type="hidden" name="purchase_id" value="<?= $purchase_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href=<?= site_url("purchase"); ?>">Back</button>
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
                                        <th>Purchase No.</th>
                                        <th>Cashier</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $builder = $this->db
                                        ->table("purchase")
                                        ->join("(SELECT purchase_id AS purchaseid,SUM(purchased_bill)AS nominal FROM purchased GROUP BY purchase_id)purchased", "purchased.purchaseid=purchase.purchase_id", "left")
                                        ->join("supplier", "supplier.supplier_id=purchase.supplier_id", "left")
                                        ->join("store", "store.store_id=purchase.store_id", "left")
                                        ->join("user", "user.user_id=purchase.cashier_id", "left")
                                        ->where("purchase.store_id",session()->get("store_id"));
                                    if(isset($_GET["from"])&&$_GET["from"]!=""){
                                        $builder->where("purchase.purchase_date >=",$this->request->getGet("from"));
                                    }
                                    if(isset($_GET["to"])&&$_GET["to"]!=""){
                                        $builder->where("purchase.purchase_date <=",$this->request->getGet("to"));
                                    }
                                    $usr= $builder
                                        ->orderBy("purchase_id", "DESC")
                                        ->get();
                                    // echo $this->db->getLastquery();die;
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) { 
                                        if($usr->nominal>0){$usr->nominal=$usr->nominal;}else{$usr->nominal=0;}
                                        ?>
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
                                                            isset(session()->get("halaman")['18']['act_read']) 
                                                            && session()->get("halaman")['18']['act_read'] == "1"
                                                        )
                                                    ) { ?>
                                                    <a href="<?=base_url("purchased?supplier_id=".$usr->supplier_id."&purchase_id=".$usr->purchase_id);?>" class="btn btn-xs btn-info"><span class="fa fa-cubes"></span> <?= $no++; ?></a>
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
                                                            isset(session()->get("halaman")['18']['act_create']) 
                                                            && session()->get("halaman")['18']['act_create'] == "1"
                                                        )
                                                    ) { ?>
                                                   <a href="<?=base_url("payment?purchase_id=".$usr->purchase_id."&purchase_no=".$usr->purchase_no."&kas_nominal=".$usr->nominal."&supplier_id=".$usr->supplier_id);?>" class="btn btn-xs btn-success"><span class="fa fa-money"></span></a>
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
                                                            isset(session()->get("halaman")['18']['act_update']) 
                                                            && session()->get("halaman")['18']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                        <input type="hidden" name="purchase_id" value="<?= $usr->purchase_id; ?>" />
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
                                                            isset(session()->get("halaman")['18']['act_delete']) 
                                                            && session()->get("halaman")['18']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="purchase_id" value="<?= $usr->purchase_id; ?>" />
                                                    </form>
                                                    <?php }?>
                                                </td>
                                            <?php } ?>
                                            <td><?= $usr->purchase_date; ?></td>
                                            <td><?= $usr->store_name; ?></td>
                                            <td><?= $usr->supplier_name; ?></td>
                                            <td><?= $usr->purchase_no; ?></td>
                                            <td><?= $usr->user_name; ?></td>
                                            <td><?= number_format($usr->nominal,0,",","."); ?></td>
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
    var title = "Report Purchase";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>