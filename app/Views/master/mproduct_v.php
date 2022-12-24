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
                                    isset(session()->get("halaman")['8']['act_create']) 
                                    && session()->get("halaman")['8']['act_create'] == "1"
                                )
                            ) { ?>
                            <form method="post" class="col-md-2">
                                <h1 class="page-header col-md-12">
                                    <button name="new" class="btn btn-info btn-block btn-lg" value="OK" style="">New</button>
                                    <input type="hidden" name="product_id" />
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
                                    <label class="control-label col-sm-2" for="category_id">Category:</label>
                                    <div class="col-sm-10">
                                        <?php
                                        $category = $this->db->table("category")
                                            ->where("store_id",session()->get("store_id"))
                                            ->orderBy("category_name", "ASC")
                                            ->get();
                                        //echo $this->db->getLastQuery();
                                        ?>
                                        <select class="form-control select" id="category_id" name="category_id">
                                            <option value="0" <?= ($category_id == "0") ? "selected" : ""; ?>>Pilih Kategori</option>
                                            <?php
                                            foreach ($category->getResult() as $category) { ?>
                                                <option value="<?= $category->category_id; ?>" <?= ($category_id == $category->category_id) ? "selected" : ""; ?>><?= $category->category_name; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>                               
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="unit_id">Unit:</label>
                                    <div class="col-sm-10">
                                        <?php
                                        $unit = $this->db->table("unit")
                                            ->where("store_id",session()->get("store_id"))
                                            ->orderBy("unit_name", "ASC")
                                            ->get();
                                        //echo $this->db->getLastQuery();
                                        ?>
                                        <select class="form-control select" id="unit_id" name="unit_id">
                                            <option value="0" <?= ($unit_id == "0") ? "selected" : ""; ?>>Pilih Unit</option>
                                            <?php
                                            foreach ($unit->getResult() as $unit) { ?>
                                                <option value="<?= $unit->unit_id; ?>" <?= ($unit_id == $unit->unit_id) ? "selected" : ""; ?>><?= $unit->unit_name; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>                             
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_name">Product Name:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="product_name" name="product_name" placeholder="" value="<?= $product_name; ?>">
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_description">Description:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="product_description" name="product_description" placeholder="" value="<?= $product_description; ?>">
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_countlimit">Limit Stock:</label>
                                    <div class="col-sm-10">
                                        <input type="number" autofocus class="form-control" id="product_countlimit" name="product_countlimit" placeholder="" value="<?= $product_countlimit; ?>">
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_stock">Stock Real Time:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="product_stock" name="product_stock" placeholder="" value="<?= $product_stock; ?>">
                                    </div>
                                </div>                                 
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_buy">Buy:</label>
                                    <div class="col-sm-10">
                                        <input type="number" autofocus class="form-control" id="product_buy" name="product_buy" placeholder="" value="<?= $product_buy; ?>">
                                    </div>
                                </div>                            
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_sell">Sell:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="product_sell" name="product_sell" placeholder="" value="<?= $product_sell; ?>">
                                    </div>
                                </div>                               
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_ube">UBE No.:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="product_ube" name="product_ube" placeholder="" value="<?= $product_ube; ?>">
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_batch">Batch No.:</label>
                                    <div class="col-sm-10">
                                        <input type="text" autofocus class="form-control" id="product_batch" name="product_batch" placeholder="" value="<?= $product_batch; ?>">
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_expiredate">Exp Date:</label>
                                    <div class="col-sm-10">
                                        <input type="date" autofocus class="form-control" id="product_expiredate" name="product_expiredate" placeholder="" value="<?= $product_expiredate; ?>">
                                    </div>
                                </div>                            
                               <div class="form-group">
                                    <label class="control-label col-sm-2" for="product_picture">Picture:</label>
                                    <div class="col-sm-10">
                                        <input type="file" autofocus class="form-control" id="product_picture" name="product_picture" placeholder="" value="<?= $product_picture; ?>">
                                        <?php if($product_picture!=""&&$product_picture!="product.png"){$user_image="images/product_picture/".$product_picture;}else{$user_image="images/product_picture/no_image.png";}?>
                                          <img id="product_picture_image" width="100" height="100" src="<?=base_url($user_image);?>"/>
                                          <script>
                                            function readURL(input) {
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                        
                                                    reader.onload = function (e) {
                                                        $('#product_picture_image').attr('src', e.target.result);
                                                    }
                                        
                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                        
                                            $("#product_picture").change(function () {
                                                readURL(this);
                                            });
                                          </script>
                                    </div>
                                </div>

                                <input type="hidden" name="product_id" value="<?= $product_id; ?>" />
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" id="submit" class="btn btn-primary col-md-5" <?= $namabutton; ?> value="OK">Submit</button>
                                        <button class="btn btn-warning col-md-offset-1 col-md-5" onClick="location.href=<?= site_url("product"); ?>">Back</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
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
                                        <?php if (!isset($_GET["report"])) { ?>
                                            <th>Action</th>
                                        <?php } ?>
                                        <th>No.</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Product</th>
                                        <th>Ube</th>
                                        <th>Batch</th>
                                        <th>Exp Date</th>
                                        <th>Limit</th>
                                        <th>Stock</th>
                                        <th>Buy</th>
                                        <th>Sell</th>
                                        <th>Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $usr = $this->db
                                        ->table("product")
                                        ->join("category", "category.category_id=product.category_id", "left")
                                        ->join("unit", "unit.unit_id=product.unit_id", "left")
                                        ->join("store", "store.store_id=product.store_id", "left")
                                        ->where("product.store_id",session()->get("store_id"))
                                        ->orderBy("product_name", "ASC")
                                        ->get();
                                    //echo $this->db->getLastquery();
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
                                                            isset(session()->get("halaman")['8']['act_update']) 
                                                            && session()->get("halaman")['8']['act_update'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-warning " name="edit" value="OK"><span class="fa fa-edit" style="color:white;"></span> </button>
                                                        <input type="hidden" name="product_id" value="<?= $usr->product_id; ?>" />
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
                                                            isset(session()->get("halaman")['8']['act_delete']) 
                                                            && session()->get("halaman")['8']['act_delete'] == "1"
                                                        )
                                                    ) { ?>
                                                    <form method="post" class="btn-action" style="">
                                                        <button class="btn btn-sm btn-danger delete" onclick="return confirm(' you want to delete?');" name="delete" value="OK"><span class="fa fa-close" style="color:white;"></span> </button>
                                                        <input type="hidden" name="product_id" value="<?= $usr->product_id; ?>" />
                                                    </form>
                                                    <?php }?>
                                                </td>
                                            <?php } ?>
                                            <td><?= $no++; ?></td>
                                            <td><?= $usr->category_name; ?></td>
                                            <td><?= $usr->unit_name; ?></td>
                                            <td><?= $usr->product_name; ?></td>
                                            <td><?= $usr->product_ube; ?></td>
                                            <td><?= $usr->product_batch; ?></td>
                                            <td><?= $usr->product_expiredate; ?></td>
                                            <?php 
                                            $limit=$usr->product_countlimit; 
                                            $stock=$usr->product_stock;
                                            if($limit>=$stock){$alstock="danger";}else{$alstock="default";}
                                            ?>
                                            <td><?= number_format($limit,0,",","."); ?></td>
                                            <td><i class="text-<?=$alstock;?>"><?= number_format($stock,0,",","."); ?></i></td>
                                            <?php 
                                            $buy=$usr->product_buy; 
                                            $sell=$usr->product_sell;
                                            $margin=$sell-$buy;
                                            ?>
                                            <td><?= number_format($buy,0,",","."); ?></td>
                                            <td><?= number_format($sell,0,",","."); ?></td>
                                            <td><?= number_format($margin,0,",","."); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Master Product";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>