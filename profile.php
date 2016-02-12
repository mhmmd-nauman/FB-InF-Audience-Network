<?php
require_once __DIR__ . '/includes/header.php';
require_once dirname(__FILE__) . '/theme/header.php';

$user_id = $cr_user['id'];
$table = "user";
$user = current($db_util->SelectTable($table, "id='" . $user_id . "'"));
?>
<div class="row">

    <div class="col-lg-12">
        <h1 class="page-header">Profile</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Your Current Profile Infromation</h4>
            </div>
            <br>
            <div class="col-lg-12">
                <?php
                if (!isset($_SESSION['flash_msg']))
                    $_SESSION['flash_msg'] = array('info' => "All fields are required to fill with correct values to work system properly.");

                include_once __DIR__ . '/includes/message.php';
                ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form name="frmprofile" role="form" method="post" action="profileinc.php">
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" placeholder="Enter your name" type="text" name="name" maxlength="250" required="required" value="<?php echo $user['name']; ?>">                                
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" placeholder="Enter your valid email address" type="email" name="email" maxlength="250" required="required" value="<?php echo $user['email']; ?>">
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <b>InfusionSoft:</b>
                                </div><div class="panel-body">
                                    <div class="form-group">
                                        <label>InfusionSoft Hostname</label>
                                        <input class="form-control" placeholder="Enter Infusion Hostname" type="text" name="inf_host" maxlength="255" required="required" value="<?php echo $user['inf_host']; ?>">
                                        <p class="help-block">Like <i>something</i>.infusionsoft.com</p>
                                    </div>
                                    <div class="form-group">
                                        <label>InfusionSoft API key</label>
                                        <input class="form-control" placeholder="Enter Infusion Hostname" type="text" name="inf_api_key" maxlength="255" required="required" value="<?php echo $user['inf_api_key']; ?>">
                                    </div>  
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <b>Facebook:</b>
                                </div><div class="panel-body">
                                    <div class="form-group">
                                        <label>Facebook Account ID</label>
                                        <input class="form-control" placeholder="Enter Facebook Account ID" type="text" name="fb_account_id" maxlength="255" required="required" value="<?php echo $user['fb_account_id']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Facebook App ID</label>
                                        <input class="form-control" placeholder="Enter Facebook App ID" type="text" name="fb_app_id" maxlength="255" required="required" value="<?php echo $user['fb_app_id']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Facebook App Secret</label>
                                        <input class="form-control" placeholder="Enter Facebook App Secret key" type="text" name="fb_app_secret" maxlength="255" required="required" value="<?php echo $user['fb_app_secret']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Facebook Access Token</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter Facebook Access Token" name="fb_app_token" required="required"><?php echo $user['fb_app_token']; ?></textarea>
                                        <p class="help-block">NOTE: Please update this token before getting expired.</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </form>
                    </div>
                    <!-- /.col-lg-6 (nested) -->                   
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<?php
require_once dirname(__FILE__) . '/theme/footer.php';
?>