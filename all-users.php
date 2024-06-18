<?php
include_once('head.php');
include_once('autoload.php');

$records_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;
?>
<h1> System Users</h1>
<style>
    h1 {
            text-align: center;
            color: #02000d; /* Dark blue text */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }
</style>
<div class='row' id='row' style='margin-top:24px;padding:4px;'>
    <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
        <ul class='breadcrumb'>
            <li class='breadcrumb-item'><a href='all-users.php'><i class='fa fa-list'></i>&nbsp;&nbsp;All USERS</a></li>
        </ul>
    </div>
</div>

<div class='row' id='searchbar'>
    <div class='col-sm-6 col-md-6 col-xs-12 col-lg-6'>
        <form class='form-inline'>
            <select name='type' class='form-control select' required='required'>
                <option value='full_names'>FULL NAMES</option>
                <option value='username'>USERNAME</option>
                <option value='tel'>TEL</option>
                <option value='business_name'>BUSINESS NAME</option>
                <option value='type_of_business'>TYPE OF BUSINESS</option>
                <option value='addresses'>ADDRESSES</option>
                <option value='role'>ROLE</option>
            </select>
            <input type='text' name='query' class='form-control' required='required'>
            <button style='margin-left:4px;border:1px solid lightgrey;' name='check' type='submit' class='btn btn-info'><i class='fa fa-search'></i> SEARCH</button>
        </form>
    </div>
</div>

<div class='row' id='row'>
    <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
        <?php
        if (isset($_GET['id'])) {
            $r = new users($_GET['id']);
            $r->delete_users();
            echo '<script>alert("ITEM WAS DELETED!"); window.location.href="all-users.php";</script>';
        }
        $alldata = users::read_users();
        $column = users::users_constants();
        if (isset($_GET['check']) && isset($_GET['type']) && isset($_GET['query'])) {
            if (in_array($_GET['type'], $column)) {
                $alldata = users::search_users($_GET['type'], $_GET['query']);
            } else {
                $alldata = users::read_users();
            }
        }
        ?>

       <div class='table-responsive'>
            <table style='width:100%;' border='1' cellpadding='2' class='table table-striped table-hover table-bordered table-condensed' id='table'>
                <thead>
                    <tr>
                        <th>Numbers</th>
                        <th class='full_names'>FULL NAMES</th>
                        <th class='username'>USERNAME</th>
                        <th class='tel'>TEL</th>
                        <th class='business_name'>BUSINESS NAME</th>
                        <th class='type_of_business'>TYPE OF BUSINESS</th>
                        <th class='addresses'>ADDRESSES</th>
                        <th class='role'>ROLE</th>
                        <td class='Edit'><i class='fa fa-edit'></i> Update</td>
                        <td class='Delete'><i class='fa fa-trash'></i> Delete</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display records for the current page
                    $total_records = count($alldata);
                    $total_pages = ceil($total_records / $records_per_page);
                    $start_index = ($page - 1) * $records_per_page;
                    $end_index = min($start_index + $records_per_page - 1, $total_records - 1);

                    if ($total_records > 0) {
                        echo "<center><b>$total_records Records Found.</b></center>";
                        if (isset($_GET['type']) && isset($_GET['query'])) {
                            echo "<center>Search results for: $_GET[type] / $_GET[query]</center>";
                        }
                    } else {
                        echo "<center><b>No records found.</b></center>";
                    }

                    for ($i = $start_index; $i <= $end_index; $i++) {
                        $raw = $alldata[$i];
                    ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td class='full_names'><?php echo $raw['full_names']; ?></td>
                            <td class='username'><?php echo $raw['username']; ?></td>
                            <td class='tel'><?php echo $raw['tel']; ?></td>
                            <td class='business_name'><?php echo $raw['business_name']; ?></td>
                            <td class='type_of_business'><?php echo $raw['type_of_business']; ?></td>
                            <td class='addresses'><?php echo $raw['addresses']; ?></td>
                            <td class='role'><?php echo $raw['role']; ?></td>
                            <td class='Edit'><a href='add-users.php?id=<?php echo $raw['user_id']; ?>' class='btn btn-success'><i class='fa fa-edit'></i> EDIT</a></td>
                            <td class='Delete'><a href='all-users.php?id=<?php echo $raw['user_id']; ?>' class='btn btn-danger'><i class='fa fa-trash'></i> TRASH</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php
        // Pagination controls
        echo "<div class='text-center'>";
        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . "' class='previous'>&laquo; Previous</a>&nbsp;&nbsp;";
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<a href='?page=$i' class='active'>$i</a>&nbsp;&nbsp;";
            } else {
                echo "<a href='?page=$i'>$i</a>&nbsp;&nbsp;";
            }
        }
        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . "' class='next'>Next &raquo;</a>";
        }
        echo "</div>";
        ?>
    </div>
</div>

<style>
a {
    text-decoration: none;
    display: inline-block;
    padding: 8px 16px;
}

a:hover {
    background-color: #ddd;
    color: black;
}

.previous {
    background-color: #f1f1f1;
    color: black;
}

.next {
    background-color: #04AA6D;
    color: white
    ;
}

.round {
    border-radius: 50%;
}
</style>

<?php
include_once('foot.php');
?>
