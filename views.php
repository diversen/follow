<?php

/**
 * view class. Can be overridden in templates
 */

class follow_views {
    
    public static function showButton ($parent_id, $reference, $status) { ?>
<div class="follow" parent-id ="<?=$parent_id?>" reference="<?=$reference?>"><?=$status?></div>
<? 
    
    }
     
}