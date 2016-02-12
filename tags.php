<?php
require_once __DIR__ . '/includes/header.php';
require_once dirname(__FILE__) . '/theme/header.php';

$table = 'tag';
$user_id = $cr_user['id'];
$fieldarray = "";
$audiences = $db_util->SelectTable($table, "user_id = '" . $user_id . "'", $fieldarray);
?>

<div class="row">
    <div class="col-lg-11">
        <h1 class="page-header">Tag</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Tags
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-tags">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Description</th>
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
                                ?>
                                <tr class="<?php echo $level . ' ' . $rowClass; ?> ">
                                    <td><?php echo $audience['inf_id']; ?></td>
                                    <td><?php echo html_entity_decode($audience['name']); ?></td>
                                    <td><?php echo html_entity_decode($audience['inf_description']); ?></td>
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
        $('#dataTables-tags').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
<?php require_once 'theme/footer.php'; ?>
