<?php
namespace Center\Functions;

class MemberFunctions {
    static public function getAvatar($member_id) {
        if (file_exists('private/source/members/'.$member_id.'/avatar.png')) {
            return '/files/member-avatar/'.$member_id.'/avatar.png';
        } else
            return false;
    }
}

?>