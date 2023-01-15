<?php echo $this->include("template/header_v"); ?>
<style>
td{padding: 0px  10px 0px 10px  !important;}
</style>
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
                            <!-- <h4 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h4> -->
                        </div>
                    </div>

                    
                    <?php 
                    if(isset($_GET["from"])&&$_GET["from"]!=""){
                        $from=$_GET["from"];
                    }else{
                        $from=date("Y-m-d");
                    }

                    if(isset($_GET["to"])&&$_GET["to"]!=""){
                        $to=$_GET["to"];
                    }else{
                        $to=date("Y-m-d");
                    }
                    ?>

                    <form class="form-inline" >
                        <label for="from">Dari:</label>&nbsp;
                        <input type="date" id="from" name="from" class="form-control" value="<?=$from;?>">&nbsp;
                        <label for="to">Ke:</label>&nbsp;
                        <input type="date" id="to" name="to" class="form-control" value="<?=$to;?>">&nbsp;
                        <?php 
                        if(isset($_GET["tanpamodal"])&&$_GET["tanpamodal"]!=""){
                            $checked="checked";
                        }else{$checked="";} ?>
                        <button type="submit" class="btn btn-primary">Submit</button>&nbsp;
                        <button type="button" onclick="print();" class="btn btn-warning"><span class="fa fa-print"></span> Print</button>
                    </form>

                       

                    <div class="bold text-primary mt-5 h4">Pemasukan : <span id="pemasukan" class=""></span></div>
                    <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                        <thead class="">
                            <tr>
                                <th>Akun</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $usr = $this->db
                            ->table("account")
                            ->where("store_id",session()->get("store_id"))
                            ->where("account_type","Debet")
                            ->orderBy("account_sort", "ASC")
                            ->get();
                            // echo $this->db->getLastquery();
                            $pemasukan=0;
                            foreach ($usr->getResult() as $usr) {
                                $builder = $this->db
                                ->table("kas")                                            
                                ->select("SUM(kas_nominal)AS tnom")
                                ->join("store", "store.store_id=kas.store_id", "left")
                                ->where("kas.account_id",$usr->account_id)
                                ->where("kas.store_id",session()->get("store_id"))
                                ->where("kas.kas_type",'masuk');
                                if(isset($_GET["from"])&&$_GET["from"]!=""){
                                    $builder->where("kas.kas_date >=",$this->request->getGet("from"));
                                }else{
                                    $builder->where("kas.kas_date",date("Y-m-d"));
                                }
                                if(isset($_GET["to"])&&$_GET["to"]!=""){
                                    $builder->where("kas.kas_date <=",$this->request->getGet("to"));
                                }else{
                                    $builder->where("kas.kas_date",date("Y-m-d"));
                                }
                                $kas= $builder
                                    ->groupBy("kas.account_id")
                                    ->get();
                            // echo $this->db->getLastquery();
                                    $tnom=0;
                                    foreach($kas->getResult() as $kas){$tnom=$kas->tnom;}
                                    if($tnom==null){$tnom=0;}
                                ?>
                                <tr>                        
                                    <td class="text-left"><?= $usr->account_name; ?></td>
                                    <td class="text-right"><?= number_format($tnom,0,".",",");$pemasukan+=$tnom; ?></td>
                                </tr>
                            <?php } ?>
                            
                            <tr>
                                <td class="text-left">Total&nbsp;</td>
                                <td class="text-right"><?= number_format($pemasukan,0,".",","); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bold text-primary mt-1 h4">Pengeluaran : <span id="pemasukan" class=""></span></div>
                    <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                        <thead class="">
                            <tr>
                                <th>Akun</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $usr = $this->db
                            ->table("account")
                            ->where("account_type","Kredit")
                            ->orderBy("account_sort", "ASC")
                            ->get();
                            // echo $this->db->getLastquery();
                            $pengeluaran=0;
                            foreach ($usr->getResult() as $usr) {
                                $builder = $this->db
                                ->table("kas")                                            
                                ->select("SUM(kas_nominal)AS tnom")
                                ->join("store", "store.store_id=kas.store_id", "left")
                                ->where("kas.account_id",$usr->account_id)
                                ->where("kas.store_id",session()->get("store_id"))
                                ->where("kas.kas_type",'keluar');
                                if(isset($_GET["from"])&&$_GET["from"]!=""){
                                    $builder->where("kas.kas_date >=",$this->request->getGet("from"));
                                }else{
                                    $builder->where("kas.kas_date",date("Y-m-d"));
                                }
                                if(isset($_GET["to"])&&$_GET["to"]!=""){
                                    $builder->where("kas.kas_date <=",$this->request->getGet("to"));
                                }else{
                                    $builder->where("kas.kas_date",date("Y-m-d"));
                                }
                                $kas= $builder
                                    ->groupBy("kas.account_id")
                                    ->get();
                                    $tnom=0;
                                    foreach($kas->getResult() as $kas){$tnom=$kas->tnom;}
                                    if($tnom==null){$tnom=0;}
                                ?>
                                <tr>                        
                                    <td class="text-left"><?= $usr->account_name; ?></td>
                                    <td class="text-right"><?= number_format($tnom,0,".",",");$pengeluaran+=$tnom; ?></td>
                                </tr>
                            <?php } ?>
                            
                            <tr>
                                <td class="text-left">Total&nbsp;</td>
                                <td class="text-right"><?= number_format($pengeluaran,0,".",","); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bold text-primary mt-1 h4">Produk Terjual : <span id="pemasukan" class=""></span></div>
                    <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">                        
                        <tbody>                           
                            <tr>                        
                                <td class="text-left">Total&nbsp;</td>
                                <td class="text-right"><?php 
                                
                                $builder = $this->db
                                ->table("transactiond")                                            
                                ->select("SUM(transactiond_qty)AS tnom")
                                ->join("transaction", "transaction.transaction_id=transactiond.transaction_id", "left")
                                ->where("transactiond.store_id",session()->get("store_id"));
                                if(isset($_GET["from"])&&$_GET["from"]!=""){
                                    $builder->where("transaction.transaction_date >=",$this->request->getGet("from"));
                                }else{
                                    $builder->where("transaction.transaction_date",date("Y-m-d"));
                                }
                                if(isset($_GET["to"])&&$_GET["to"]!=""){
                                    $builder->where("transaction.transaction_date <=",$this->request->getGet("to"));
                                }else{
                                    $builder->where("transaction.transaction_date",date("Y-m-d"));
                                }
                                $transactiond= $builder
                                    ->get();
                                    $tnom=0;
                                    foreach($transactiond->getResult() as $transactiond){$tnom=$transactiond->tnom;}
                                    if($tnom==null){$tnom=0;}
                                echo number_format($tnom,0,".",",");
                                ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="bold text-success mt-1 h4">
                        Laba/Rugi : <span class="text-info">Rp. <?=number_format($pemasukan-$pengeluaran,0,".",",");?></span>
                    </div>
                    <script>
                        $("#pemasukan").html('Rp. <?= number_format($pemasukan,0,".",",");?>');
                        $("#pengeluaran").html('Rp. <?= number_format($pengeluaran,0,".",",");?>');
                        function print(){
                            window.open('<?=base_url("rneracaprint?");?>','_blank');
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.select').select2();
    var title = "Neraca";
    $("title").text(title);
    $(".card-title").text(title);
    $("#page-title").text(title);
    $("#page-title-link").text(title);
</script>

<?php echo  $this->include("template/footer_v"); ?>