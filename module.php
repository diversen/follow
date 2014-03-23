<?php

/**
 * check to se if any override functions exists in templates
 */
view::includeOverrideFunctions('follow', 'views.php');

class follow {
    
    /**
     * shows a single click item. 
     * If user is not logged in he can not follow
     * If user follows: Message = 'Click to unfollow'
     * If user does not follow. Message = 'Click to unfollow'
     * 
     * @param type $user_id
     * @param type $parent_id
     * @param type $reference
     */
    public function getFollowHtml ($user_id, $parent_id, $reference = '') { 
        $status = $this->getStatus($user_id, $parent_id, $reference);
        return follow_views::showButton($parent_id, $reference, $status);
    }
    
    /**
     * when used as a sub module
     * @param array $options e.g array ('reference' => 'blog', 'parent_id' => 100);
     * @return string $html
     */
    public function subModulePostContent($options) {

        $hash = "follow-$options[parent_id]-$options[reference]";
        $str = <<<EOF
<div class="follow_box">
<a name="$hash"></a>
EOF;
        
        $f = new follow();
        $return_url = "/account/index?return_to =" .
        rawurlencode($options['return_url'] . "#" . $hash) . 
                "&message=" . rawurlencode(lang::translate('You need to log in if you want to follow'));
        if (!session::isUser()) {
            $str.=$f->getNotLoggedInHtml($return_url);
        } else {
            $f->initJs();
            $follow_html = $f->getFollowHtml(session::getUserId(), $options['parent_id'], $options['reference']);
            $str.= html::getHeadline($follow_html);
        }
        
        $str.= <<<EOF
</div>
EOF;
        return $str;
    }
    
    /**
     * get html for display when a user is not logged in. 
     * @param string $return_to the url to return to after log in including # mark 
     * @return type
     */
    public function getNotLoggedInHtml ($url) {
        //$return_to = rawurlencode($url);
        $extra = array ('title' => lang::translate('Log in in order to follow this post'));
        $str = html::getHeadline(html::createLink($url, lang::translate('Follow'), $extra));
        return $str;
    }

    
    /**
     * some javascript to generate the rpc call
     */
    public function initJs () { ?>
<script>
$(function() {
    $(".follow").on("click", function(event){
        var parent_id = $(this).attr('parent-id');
        var reference = $(this).attr('reference');
        var formData = {
            parent_id:parent_id,
            reference:reference}; //Array 

        $.ajax({
            url: "/follow/rpc",
            type: "POST",
            data: formData,
            success: function(data, textStatus, jqXHR)
            {
                $('.follow[parent-id=' + parent_id +']').text(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                //alert(textStatus)
            }
        });
    });
});


</script><?php
    }
    
    /**
     *
     * @var string $status status message
     */
    public $status = null;
    
    /**
     * get status message from user_id, parent_id, reference
     * @param int $user_id
     * @param int $parent_id
     * @param string $reference
     * @return string $message (status message)
     */
    public function getStatus($user_id, $parent_id, $reference) {
        if (!session::isUser()) {
            return lang::translate('Log in to follow');
        }
        
        $row = $this->userDoFollow($user_id, $parent_id, $reference);
        if (empty($row)) {
            return lang::translate('Click to follow');
        } else {
            return lang::translate('Click to stop following');
        }
    }
    
    /**
     * find out if user follows an item
     * @param int $user_id
     * @param int $id
     * @param string $reference
     * @return array $row if not empty the user follow the item. If empty the user does not follow
     */
    public function userDoFollow ($user_id, $parent_id, $reference) {
        $values = compact('user_id', 'parent_id', 'reference');   

        $row = db_q::select('follow')->
                filterArrayDirect($values)->
                fetchSingle();
        return $row;
    }
    
    /**
     * toggle follow. If user has a row in follow table it will be deleted. 
     * If user does not have a row one will be inserted
     * @param int $user_id
     * @param int $parent_id
     * @param string $reference
     * @return boolean $res 
     */
    public function toggleFollow ($user_id, $parent_id, $reference) {
        $row = $this->userDoFollow($user_id, $parent_id, $reference);
        if (empty($row)) {
            $res = $this->userClickFollow($user_id, $parent_id, $reference);
        } else {
            $res = $this->userClickUnFollow($user_id, $parent_id, $reference);
        }
        return $res;
    }
    
    /**
     * adds row (user_id, parent_id, reference) to follow table
     * @param int $user_id
     * @param int $parent_id
     * @param string $reference
     * @return boolean
     */
    public function userClickFollow ($user_id, $parent_id, $reference) {
        $row = $this->userDoFollow($user_id, $parent_id, $reference);
    
        if (empty($row)) { 
            $values = compact('user_id', 'parent_id', 'reference');
            $res = db_q::insert('follow')->values($values)->exec();
            $this->status = lang::translate('Click to unfollow');
        }
        return true;
                    
    }
    
    /**
     * remove row (user_id, parent_id, reference) from follow table
     * @param int $user_id
     * @param int $id
     * @param string $reference
     * @return boolean
     */
    public function userClickUnFollow ($user_id, $parent_id, $reference) {
        $row = $this->userDoFollow($user_id, $parent_id, $reference);
    
        if (!empty($row)) { 
            $values = compact('user_id', 'parent_id', 'reference');
            $res = db_q::delete('follow')->filterArrayDirect($values)->exec();
            $this->status = lang::translate('Click to follow');
        }
        return true;
    }
}
