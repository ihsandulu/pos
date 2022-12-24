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
        font-size:10px;
        margin: 0px !important;
        line-height:100%;
    }

    @page {
        
    }	
    .tebal10{font-size:10px; font-weight:bold;}		
    .tebal12{font-size:12px; font-weight:bold;}	
    .tebal14{font-size:14px; font-weight:bold;}	
    .tebal16{font-size:16px; font-weight:bold;}		
    th, td{padding:2px;}
    .pagebreak{page-break-after: always;}
} 
.border{border:black solid 1px;}
</style>
<?php 
$builder=$this->db->table("transaction")
->where("transaction_id",$this->request->getGet("transaction_id"));
$transaction=$builder->get();
if($builder->countAll()>0){
    foreach ($transaction->getResult() as $transaction) {
    ?>
    <div class='container-fluid'>
        <div class='row'>
            <?php echo $this->include("template/kop"); ?>
            <div class="col-12 mt-3 p-0">Invoice No. : <?=$transaction->transaction_no;?></div>
            <div class="col-12 mb-3 p-0">Date : <?=date("d M Y",strtotime($transaction->transaction_date));?></div>
            <div class="col-12" style="padding:0px;"> 
                <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <!-- <table id="dataTable" class="table table-condensed table-hover w-auto dtable"> -->
                    <thead class="">
                        <tr>
                            <th>No.</th>
                            <th>Batch</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $usr = $this->db
                            ->table("transactiond")
                            ->select("*,SUM(transactiond_qty)AS qty, SUM(transactiond_price)AS price,")
                            ->join("product", "product.product_id=transactiond.product_id", "left")
                            ->join("unit", "unit.unit_id=product.unit_id", "left")
                            ->where("product.store_id",session()->get("store_id"))
                            ->where("transactiond.transaction_id",$this->request->getGet("transaction_id"))
                            ->groupBy("transactiond.product_id")
                            ->orderBy("product_name", "ASC")
                            ->get();
                        //echo $this->db->getLastquery();
                        $no = 1;
                        $tprice=0;
                        foreach ($usr->getResult() as $usr) { 
                            ?>
                            <tr>                                
                                <td><?= $no++; ?></td>
                                <td><?= $usr->product_batch; ?></td>
                                <td><?= $usr->product_name; ?></td>
                                <?php 
                                $qty=$usr->qty; 
                                $price=$usr->price; 
                                $tprice+=$price; 
                                ?>
                                <td>
                                    <?= number_format($qty,0,",",".") ?> <?= $usr->unit_name; ?> 
                                </td>
                                <td><?= number_format($price,0,",",".") ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <th colspan="4" class="text-right"><h5>Total</h5></th>
                            <th>
                                <?= number_format($tprice,0,",","."); ?>
                                <input type="hidden" id="tagihan" value="<?=$tprice;?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right"><h5>Bayar</h5></th>
                            <th class="dibayar"><?=number_format($transaction->transaction_pay,0,",",".");?></th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right"><h5>Kembalian</h5></th>
                            <th class="kembalian"><?=number_format($transaction->transaction_change,0,",",".");?></th>
                        </tr>
                    </tbody>
                </table>                        
            </div>
            <div class="col-8 row mt-5 p-0">   
                <div class="col-2">
                    <div class="col-12" style="font-weight:bold; font-size:16px;">Note :</div>
                </div>
                <div class="col-10">
                    <?=session()->get("store_noteinvoice");?>
                </div>	
            </div>
            <div class="col-4 row mt-5 p-0" style=""  align="center">
                <div class="col-12"><strong class="tebal10">Hormat Kami,</strong></div>
                <div class="col-12" style="height:50px;">&nbsp;</div>
                <div class="col-12" style=""><strong><?=session()->get("user_name");?></strong></div>
            </div>
            
        </div>
    </div>
    <div class="pagebreak"></div>

<?php }
}else{?>
    <h1 class="centerpage">Data tidak ditemukan!</h1>
<?php }?>
<script>
window.print();
setTimeout(function(){ this.close(); }, 500);
</script>

<?php echo  $this->include("template/footersaja_v"); ?>