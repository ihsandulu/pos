<?php echo $this->include("template/header_v"); ?>

<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <?php if (!isset($_GET['user_id']) && !isset($_POST['new']) && !isset($_POST['edit'])) {
                            $coltitle = "col-md-8";
                        } else {
                            $coltitle = "col-md-8";
                        } ?>
                        <div class="<?= $coltitle; ?>">
                            <h4 class="card-title"></h4>
                            <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                        </div>
                        
                        <form action="<?= base_url("purchase"); ?>" method="get" class="col-md-2">
                            <h1 class="page-header col-md-12">
                                <button class="btn btn-warning btn-block btn-lg" value="OK" style="">Back</button>
                            </h1>
                        </form>
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
                                    <input type="hidden" name="purchased_id" />
                                </h1>
                            </form>
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
                                    <label class="control-label col-sm-2" for="product_id">Product:</label>
                                    <div class="col-sm-10">
                                        <?php
                                        $product = $this->db->table("product")
                                            ->join("unit","unit.unit_id=product.unit_id","left")
                                            ->where("product.store_id",session()->get("store_id"))
                                            ->orderBy("product.product_name", "ASC")
                                            ->get();
                                        //echo $this->db->getLastQuery();
                                        ?>
                                        <select onchange="unitname()" class="form-control select" id="product_id" name="product_id">
                                            <option unit_name="" value="0" <?= ($product_id == "0") ? "selected" : ""; ?>>Pilih Product</option>
                                            <?php
                                            foreach ($product->getResult() as $product) { ?>
                                                <option unit_name="<?= $product->unit_name; ?>" value="<?= $product->product_id; ?>" <?= ($product_id == $product->product_id) ? "selected" : ""; ?>><?= $product->product_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <script>
                                            function unitname(){;
                                                let unit_name=$('#product_id option:selected').attr("unit_name");
                                                $("#unit_name").html(unit_name);
                                            }
                                        </script>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="purchased_outdate">Out of Date:</label>
                                    <div class="col-sm-10">
                                        <input type="date" autofocus class="form-control" id="purchased_outdate" name="purchased_outdate" placeholder="" value="<?= $purchased_outdate; ?>">
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="purchased_qty">Qty (<span id="unit_name"></span>):</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="purchased_qty" name="purchased_qty" placeholder="" value="<?= $purchased_qty; ?>">
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="purchased_price">Total Harga Keseluruhan (bukan satuan):</label>
                                    <div class="col-sm-12">
                                        <input onkeyup="tagihan()" type="number" autofocus class="form-control" id="purchased_price" name="purchased_price" placeholder="" value="<?= $purchased_price; ?>">
                                    </div>
                                </div>        
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="purchased_ppn">PPN(%):</label>
                                    <div class="col-sm-12">
                                        <input onkeyup="tagihan()" type="number" autofocus class="form-control" id="purchased_ppn" name="purchased_ppn" placeholder="" value="<?= $purchased_ppn; ?>">
                                    </div>
                                </div>   
                                <div class="form-group">
                                    <label class="control-label col-sm-12" for="purchased_bill">Tagihan setelah PPN:</label>
                                    <div class="col-sm-12">
                                        <input readonly type="number" autofocus class="form-control" id="purchased_bill" name="purchased_bill" placeholder="" value="<?= $purchased_bill; ?>">
                                    </div>
                                </div>  
                                <script>
                                    function tagihan(){
                                        let price = parseInt($("#purchased_price").val());
                                        let ppn = parseInt($("#purchased_ppn").val())/100*price;
                                        let bill = price+ppn;
                                        $('#purchased_bill').val(bill);
                                    }
                                </script>
                                

                                <input type="hidden" name="purchased_id" value="<?= $purchased_id; ?>" />
                                <input type="hidden" name="purchased_bill_before" value="<?= $purchased_bill; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href=<?= site_url("purchased"); ?>">Back</button>
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
                                        <th>Action</th>
                                        <th>No.</th>
                                        <th>Store</th>
                                        <th>Trans No.</th>
                                        <th>Out of Date</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Nominal</th>
                                        <th>PPN</th>
                                        <th>Tagihan(setelah PPN)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $builder = $this->db
                                        ->table("purchased")
                                        ->join("purchase", "purchase.purchase_id=purchased.purchase_id", "left")
                                        ->join("store", "store.store_id=purchased.store_id", "left")
                                        ->join("product", "product.product_id=purchased.product_id", "left")
                                        ->where("purchased.store_id",session()->get("store_id"))
                                        ->where("purchased.purchase_id",$this->request->getGet("purchase_id"));
                                    if(isset($_GET["from"])&&$_GET["from"]!=""){
                                        $builder->where("purchased.purchased_date >=",$this->request->getGet("from"));
                                    }
                                    if(isset($_GET["to"])&&$_GET["to"]!=""){
                                        $builder->where("purchased.purchased_date <=",$this->request->getGet("to"));
                                    }
                                    $usr= $builder
                                        ->orderBy("purchased_id", "DESC")
                                        ->get();
                                    // echo $this->db->getLastquery();
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
                                                            isset(session()->get("halaman")['18']['act_update']) 
                                                            && session()->get("halaman")['18']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                        <input type="hidden" name="purchased_id" value="<?= $usr->purchased_id; ?>" />
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
                                                        <input type="hidden" name="purchased_id" value="<?= $usr->purchased_id; ?>" />
                                                        <input type="hidden" name="purchased_bill" value="<?= $usr->purchased_bill; ?>" />
                                                    </form>
                                                    <?php }?>
                                                </td>
                                            <?php } ?>                                       
                                            <td><?= $no++; ?></td>
                                            <td><?= $usr->store_name; ?></td>
                                            <td><?= $usr->purchase_no; ?></td>
                                            <td><?= $usr->purchased_outdate; ?></td>
                                            <td><?= $usr->product_name; ?></td>
                                            <td><?= number_format($usr->purchased_qty,0,",","."); ?></td>
                                            <td><?= number_format($usr->purchased_price,0,",","."); ?></td>
                                            <td><?= $usr->purchased_ppn; ?> %</td>
                                            <td><?= number_format($usr->purchased_bill,0,",","."); ?></td>
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
    var title = "Report Detail Purchase";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>