<style type="text/css">
    .table th {
        border-bottom: none;
        border-right: 2px solid #949ba0 !important;
    }

    .table tr:first-child th,
    .table tr:first-child td {
        border-top: none;
    }

    .table th {
        width: 25%;
    }

    .table tr td:nth-child(2) {
        width: 65%;
    }

    .table tr td:nth-child(3) {
        width: 10%;
        text-align: right;
    }
</style>
<?php
$os = php_uname ( 's' ) . ' ' . php_uname ( 'r' ) . ' ' . php_uname ( 'v' ) . ', ' . php_uname ( 'm' );
$php = phpversion () . ', ' . php_sapi_name ();
$dir = dirname ( $_SERVER[ 'SCRIPT_FILENAME' ] );
$mysql_connection = $this->db->conn_id;
$this->load->helper ( 'sys_info' );
$max_up_size = file_upload_max_size ();
?>
<div class="row-fluid">
    <div class="span12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4><?php echo lang ( "system_info" ) ?></h4>
            </div>
            <div class="grid-body no-border"><br>
                <table class="table table-hover table-striped" dir="ltr">
                    <tbody>
                    <tr>
                        <th>PHP</th>
                        <td><?php echo $php; ?></td>
                        <td><?php echo phpversion () < 5 ? not_ok () : is_ok () ?></td>
                    </tr>
                    <tr>
                        <th>MySQL server</th>
                        <td><?php echo $mysql_connection->server_info; ?></td>
                        <td><?php echo $mysql_connection->server_version < 50000 ? not_ok () : is_ok () ?></td>
                    </tr>
                    <tr>
                        <th>MySQL client</th>
                        <td><?php echo $mysql_connection->client_info; ?></td>
                        <td><?php echo $mysql_connection->client_version < 50000 ? not_ok () : is_ok () ?></td>
                    </tr>
                    <tr>
                        <th>Local path</th>
                        <td><?php echo $dir; ?></td>
                        <td><?php is_ok () ?></td>
                    </tr>
                    <tr>
                        <th>User agent</th>
                        <td><?php echo $_SERVER[ 'HTTP_USER_AGENT' ]; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>IP</th>
                        <td><?php echo $_SERVER[ 'REMOTE_ADDR' ]; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Operation system</th>
                        <td><?php echo $os; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Upload path</th>
                        <td><?php echo "uploads/"; ?></td>
                        <td><?php echo (!is_writable ( "uploads/" )) ? not_ok () : is_ok () ?></td>
                    </tr>
                    <tr>
                        <th>Max upload size</th>
                        <td><?php echo ini_get ( 'upload_max_filesize' ); ?></td>
                        <td><?php echo ($max_up_size < (1024 * 1024 * 200)) ? not_ok () : is_ok () ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>