<?php

require_once __DIR__ . '/includes/header.php';

$success = FALSE;
if (isset($_POST) && !empty($_POST)) {
    $fld_array = array(
        'name',
        'email',
        'fb_account_id',
        'fb_app_id',
        'fb_app_secret',
        'fb_app_token',
        'inf_host',
        'inf_api_key',
    );

    if (is_array($_POST) && count($_POST)) {
        $updata = array();
        foreach ($fld_array as $field) {
            if (isset($_POST[$field]))
                $updata[$field] = $_POST[$field];
        }
        if (count($updata)) {
            $table = "user";
            $success = $db_util->UpdateRecords($table, "id='" . $cr_user['id'] . "'", $updata);
            if ($success) {
                $_SESSION['flash_msg']['success'] = "Your profile updated successfully.";
                $rslogin = $db_util->SelectTable("user", "id=" . $cr_user['id'], "");
                $totalRows_rslogin = count($rslogin);
                if ($totalRows_rslogin > 0) {
                    $_SESSION['cr_user'] = reset($rslogin);
                }
            }
        }
    }
}

header('Location: profile.php');
exit;
