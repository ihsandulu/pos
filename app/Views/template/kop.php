<div class="col-12 row" style=" border-bottom:black solid 1px; padding-top:5px; ">	
    <div class="col-1 p-0">
        <img src="<?=base_url("images/store_picture/".session()->get("store_picture"));?>" style="width:50px; height:auto; max-width:100%; "/>  
    </div>
    <div class="col-11 p-0">  
        <div style="font-weight:bold; padding:0px; font-size:10px;"><?=session()->get("store_name");?></div>
        <div style="font-weight:bold; padding:0px; font-size:10px;"><?=session()->get("store_address");?></div>
        <div style="font-weight:bold; padding:0px; font-size:10px;">Phone : <?=session()->get("store_phone");?></div> 
    </div>
</div>