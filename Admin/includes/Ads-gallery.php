<?php $Ads_info = getAllads();?>
<div class="ads">
<?php foreach($Ads_info as $Ads_infos):?>
<div  class="dash-gall-tab">
    <div class="image">
        <a href="<?php echo $Ads_infos['linkname'] ?>?AdsClick=<?php echo md5($Ads_infos['id']);?>">
            <img src="../CSS/MEDIA/<?php echo $Ads_infos['image']?>" alt="">
            <span class="ads-click"><?php echo getTotalClickcountById($Ads_infos['id']);?>clicks</span>
        </a>  
    </div>       
</div>
<?php endforeach ?>
</div>
