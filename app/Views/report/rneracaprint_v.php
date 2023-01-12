<?php echo $this->include("template/headersaja_v"); ?>
<style>
.separator {
  border-bottom: 1px dashed #aaa;
}
.text-small{font-size: 8px;}
.img_product{
    width:100%; 
    height:150px !important;    
  border:rgba(155, 155, 155, 0.5) solid 1px;
  border-radius:4px;
}
.pointer{cursor: pointer;}
.centerpage{
    position: fixed;
    left:50%;
    top:50%;
    transform:translate(-50%,-50%);
}
.hide{display: none;}
.absolute-top-right{
    position: absolute;
    right:5px;
    top:5px;
}
@media print {
    html, body, div{
        font-family:Arial, Helvetica, sans-serif;
        font-size:16px;
        margin: 0px !important;
        line-height:100%;
    }
    #storename_title{margin: bottom 30px, im !important;}
    p{margin-bottom:0px; font-size:50px;}
    @page {
        
    }	
    .tebal10{font-size:50px; font-weight:bold;}		
    .tebal12{font-size:52px; font-weight:bold;}	
    .tebal14{font-size:54px; font-weight:bold;}	
    .tebal16{font-size:56px; font-weight:bold;}		
    th, td{padding:0px 1px 0px 1px; font-size:12px; line-height: 100% !important; width:50%;}
    .pagebreak{page-break-after: always;}
    .kiri{text-alignment:left;}
} 
.border{border:black solid 1px;}
</style>
    <div class='container-fluid'>
        <div class='row'>
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
                            $tnom=0;
                            foreach($kas->getResult() as $kas){$tnom=$kas->tnom;}
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
                            $tnom=0;
                            foreach($kas->getResult() as $kas){$tnom=$kas->tnom;}
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
            </script>
        </div>
    </div>
<script>
window.print();
setTimeout(function(){ this.close(); }, 500);
</script>

<?php echo  $this->include("template/footersaja_v"); ?>