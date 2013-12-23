<?php

/**
 * view class. Can be overridden in templates
 */

class follow_views {
    
    public static function showButton ($parent_id, $reference, $status) {
        $str = <<<EOF
<a href="javascript:" class="follow" parent-id ="{$parent_id}" reference="{$reference}">{$status}</a>
EOF;
        return $str;
    
    }
    
    public static function test () {
        
    }
     
}