<?php $Ads_info = getAds("premium");?>
<div class="ads">
<?php if($Ads_info['ads_type'] == "premium"):?>
<div  class="dash-gall-tab">
    <div class="image">
        <a target="_blank" href="<?php echo $Ads_info['linkname']?>">
            <img src="./CSS/MEDIA/<?php echo $Ads_info['image']?>" alt="pic">
        </a>  
    </div>       
</div>
<?php endif ?>
</div>