<?php
require_once __DIR__ . '/includes/header.php';
require_once dirname(__FILE__) . '/theme/header.php';

$table = 'audience';
$user_id = $cr_user['id'];
$audiences = $db_util->SelectTable($table, "user_id='" . $user_id . "' ORDER BY date_entered", "");
?>

<div class="row">
    <div class="col-lg-11">
        <h1 class="page-header">Audience</h1>
    </div>
    <div class="col-lg-1">
        <a href="createAudience.php" class="page-header pull-right fa fa-plus-circle fa-2x" title="Create New audience"></a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <?php include_once __DIR__ . '/includes/message.php'; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Custom Audiences
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-audiences">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Tags</th>
                                <th>Created date</th>                                
                                <!--<th>Last sych at</th>-->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 0;
                            foreach ($audiences as $key => $audience) {
                                $level = ($cnt % 2 == 0) ? 'even' : 'odd';
                                switch ($cnt % 2) {
                                    case 0:
                                        $level = 'even';
                                        $rowClass = 'gradeA';
                                        break;
                                    default :
                                        $level = 'odd';
                                        $rowClass = 'gradeC';
                                }

                                $tmp_db_tags = $db_util->SelectTable('tag', "inf_id IN ('" . str_replace(",", "','", $audience['groups']) . "')", "id,name,inf_id");
                                $db_tags = array();
                                if (count($tmp_db_tags)) {
                                    foreach ($tmp_db_tags as $key => $db_tag) {
                                        $db_tags[$db_tag['id']] = $db_tag['name'];
                                    }
                                }
                                ?>

                                <tr class="<?php echo $level . ' ' . $rowClass; ?> ">
                                    <td><?php echo $audience['fb_id']; ?></td>
                                    <td><?php echo $audience['name']; ?></td>
                                    <td class="center"><ul><li><?php echo html_entity_decode(implode('</li><li>', $db_tags)); ?></li></ul></td>
                                    <td><?php echo date("m/d/Y H:i", strtotime($audience['date_entered'])); ?></td>
                                    <!--<td class="center"><?php // echo date("m/d/Y", strtotime($audience['sync_date']));     ?></td>-->
                                    <td class="center text-center">
                                        <a title="Edit" href="createAudience.php?audi_id=<?php echo $audience['id']; ?>"><span class="fa fa-edit"></span></a> 
                                        <a title="Delete" href="deleteAudiences.php?del_id=<?php echo $audience['id']; ?>" onclick="return delete_tag('<?php echo $audience['fb_id']; ?>');" <span class="fa fa-trash-o"></span></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>           
<script>
    $(document).ready(function () {
        $('#dataTables-audiences').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
<?php require_once 'theme/footer.php'; ?>
