<div class="col-12 row" style=" border-bottom:black solid 1px; padding-top:5px; ">	
    <div class="col-3">
        <?php if(session()->get("store_picture")==""){
            $gambar="logo.png";
        }else{
            $gambar=session()->get("store_picture");
        } ?>
        <img src="<?=base_url("images/store_picture/".$gambar);?>" style="width:100%; height:auto;   "/>  
    </div>
    <div class="col-offset-1 col-8 p-l-5">  
        <div id="storename_title" style="font-weight:bold; padding:0px; font-size:60px;"><?=session()->get("store_name");?></div>
        <div style="padding:5px;"></div>
        <div style="font-weight:bold; padding:0px; font-size:50px;"><?=session()->get("store_address");?></div>
        <div style="padding:5px;"></div>
        <div style="font-weight:bold; padding:0px; font-size:55px;">Phone : <?=session()->get("store_phone");?></div> 
    </div>
</div>