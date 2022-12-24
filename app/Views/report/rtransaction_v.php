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
                    </div>

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
                                        <th>Trans No.</th>
                                        <th>Shift</th>
                                        <th>Cashier</th>
                                        <th>Bill</th>
                                        <th>Pay</th>
                                        <th>Change</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $builder = $this->db
                                        ->table("transaction")
                                        ->join("store", "store.store_id=transaction.store_id", "left")
                                        ->join("user", "user.user_id=transaction.cashier_id", "left")
                                        ->where("transaction.store_id",session()->get("store_id"));
                                    if(isset($_GET["from"])&&$_GET["from"]!=""){
                                        $builder->where("transaction.transaction_date >=",$this->request->getGet("from"));
                                    }
                                    if(isset($_GET["to"])&&$_GET["to"]!=""){
                                        $builder->where("transaction.transaction_date <=",$this->request->getGet("to"));
                                    }
                                    $usr= $builder
                                        ->orderBy("transaction_id", "DESC")
                                        ->get();
                                    //echo $this->db->getLastquery();
                                    $no = 1;
                                    foreach ($usr->getResult() as $usr) { ?>
                                        <tr>                                            
                                            <td><a href="<?=base_url("rtransactiond?transaction_id=".$usr->transaction_id);?>" class="btn btn-xs btn-info"><span class="fa fa-cubes"></span> <?= $no++; ?></a></td>
                                            <td><?= $usr->transaction_date; ?></td>
                                            <td><?= $usr->store_name; ?></td>
                                            <td><?= $usr->transaction_no; ?></td>
                                            <td><?= $usr->transaction_shift; ?></td>
                                            <td><?= $usr->user_name; ?></td>
                                            <td><?= number_format($usr->transaction_bill,0,",","."); ?></td>
                                            <td><?= number_format($usr->transaction_pay,0,",","."); ?></td>
                                            <td><?= number_format($usr->transaction_change,0,",","."); ?></td>
                                            <td>
                                                <?php
                                                $status=array("sukses", "batal","pending");
                                                echo $status[$usr->transaction_status]; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Report Transaction";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>