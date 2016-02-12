<?php
require_once __DIR__ . '/includes/header.php';
require_once dirname(__FILE__) . '/theme/header.php';

$tblName = "tag";
$db_tags = $db_util->SelectTable($tblName, "inf_id != '' AND user_id = '".$cr_user['id']."'", "");

$db_tags = utils::array_orderby($db_tags, 'name', SORT_ASC, 'id', SORT_ASC);

if (isset($_GET['audi_id']) && !empty($_GET['audi_id'])) {

    $audi_id = $_GET['audi_id'];
    $tblName2 = "audience";
    $tmp_db_audience = $db_util->SelectTable($tblName2, "id = '" . $audi_id . "'", "");

    if (count($tmp_db_audience) && !empty($tmp_db_audience[0]))
        $audience = reset($tmp_db_audience);
    if (!empty($audience['groups'])) {
        $audience_tags = explode(',', $audience['groups']);
    }
    ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit Audience</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Audience Details: <?php echo $audience['name']; ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form role="form" action="fbcustomAudience.php" name="CreateAudience" method="post" enctype="">
                                <div class="form-group">
                                    <label>Audience Name:</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter Audience Name" required="required" value="<?php echo $audience['name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Description"><?php echo $audience['description']; ?></textarea>
                                </div> 
                                <div class="form-group">
                                    <label>Tags</label>
                                    <select name="tags[]" multiple class="form-control" required="required">
                                        <?php
                                        if (isset($db_tags) && count($db_tags)) {
                                            foreach ($db_tags as $key => $db_tag) {
                                                ?>
                                                <option value="<?php echo $db_tag['inf_id'] . '|' . $db_tag['id']; ?>"  <?php
                                                if (in_array($db_tag['inf_id'], $audience_tags)) {
                                                    echo 'selected = "selected"';
                                                }
                                                ?>><?php echo html_entity_decode($db_tag['name']); ?></option>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </div>
                                <input type="hidden" name="audi_id" value="<?php echo $audi_id; ?>">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-primary">Reset</button>
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->

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
<?php } else { ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create New Audience</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Audience Details
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form role="form" action="fbcustomAudience.php" name="CreateAudience" method="post" enctype="">
                                <div class="form-group">
                                    <label>Audience Name:</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter Audience Name" required="required">
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea>
                                </div> 
                                <div class="form-group">
                                    <label>Tags</label>
                                    <select name="tags[]" multiple class="form-control" required="required">
                                        <?php
                                        if (isset($db_tags) && count($db_tags)) {
                                            foreach ($db_tags as $key => $db_tag) {
                                                ?>
                                                <option value="<?php echo $db_tag['inf_id'] . '|' . $db_tag['id']; ?>"><?php echo $db_tag['name']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-primary">Reset</button>
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->

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
<?php }
require_once 'theme/footer.php';
?>