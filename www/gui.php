<?php
require '../credentials.php';
require 'class.mysqli.php';
require 'uarpc.php';

new uarpc;

$UserID = 1;
$uarpc = new UARPC_BASE($UserID);


if( !empty($_POST['value']) and !empty($_POST['command']) ){
    define('AJAX_APIv2_WITH_HTTP_STATUSES', 1);
    $jsondata = ['status'=>0];

    $cmd                = $_POST['command'] ?? '';
    $val                = $_POST['value'] ?? '';
    $desc               = $_POST['desc'] ?? '';
    $jsondata['ref']    = $_POST['reference'] ?? '';

    $allowed_commands = ['getpermissionid', 'getroleid', 'addpermission', 'addrole', 'deleteRole', 'deletePerm'];
    if( in_array($cmd, $allowed_commands) ){

        switch ($cmd) {
            case 'deleteRole':
                //$jsondata['id'] = $uarpc->roles->delete($val);
                $jsondata = ['errorcode' => -1, 'error' => 'Method does not exist'];
                break;
            case 'deletePerm':
                //$jsondata['id'] = $uarpc->permissions->delete($val);
                $jsondata = ['errorcode' => -1, 'error' => 'Method does not exist'];
                break;
            case 'getpermissionid':
                $jsondata['id'] = $uarpc->permissions->id($val);
                break;
            case 'getroleid':
                $jsondata['id'] = $uarpc->roles->id($val);
                break;
            case 'addpermission':
                $jsondata['id'] = $uarpc->permissions->add($val,'');
                $jsondata['html5id'] = 'permList';
                $jsondata['html'] = '<div class="d-flex align-items-center item">
                    <div class="flex-shrink-0 w-5 tx-10">' . $jsondata['id'] . '</div>
                    <div class="flex-grow-1">' . $val . '</div>
                    <div class="flex-shrink-0 actions w-6 tx-20">
                        <a href="#" class="action" data-command="delete"><span class="iconify" data-icon="fluent:delete-dismiss-24-regular" data-inline="false"></span></a>
                    </div>
                </div>';
                break;
            case 'addrole':
                $jsondata['id'] = $uarpc->roles->add($val,'');
                $jsondata['html5id'] = 'roleList';
                $jsondata['html'] = '<div class="d-flex align-items-center item">
                    <div class="flex-shrink-0 w-5 tx-10">' . $jsondata['id'] . '</div>
                    <div class="flex-grow-1">' . $val . '</div>
                    <div class="flex-shrink-0 actions w-6 tx-20">
                        <a href="#" class="action" data-command="delete"><span class="iconify" data-icon="fluent:delete-dismiss-24-regular" data-inline="false"></span></a>
                    </div>
                </div>';
                break;
            default:
                $jsondata = ['status' => -1, 'error' => 'Default error'];
                break;
        }

    } else {
        $jsondata = ['status' => -1, 'error' => 'Command error'];
    }
    thats_it_for_now_incomming_payload($jsondata);
}




function thats_it_for_now_incomming_payload($jsondata){
    if( defined('AJAX_APIv2_WITH_HTTP_STATUSES') and AJAX_APIv2_WITH_HTTP_STATUSES ){
        if( !empty($jsondata['errorcode']) ){
            header('HTTP/1.0 400 Error');
        } else if(($jsondata['status']!==0) and empty($jsondata['status'])){
            header('HTTP/1.0 400 Error');
        } else {
            header('HTTP/1.0 200 OK');
        }
        header("Content-type: application/json;charset=utf-8");
        echo json_encode($jsondata);
        exit;
    } else {
        header("Content-type: application/json;charset=utf-8");
        echo json_encode($jsondata);
        exit;
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title></title>
  <style>

    #permid, #roleid {
        width: 50px;
    }
    #permid em, #roleid em {
        color: #ccc;
        font-style: normal;
    }
    .trans-25 {
        opacity: 0.25;
    }
    .add-focus {
        border: 1px solid #086d1f;
        border-radius:3px
    }
    .add-focus .input-group-text {
        background-color: #28a745;
        color: #fff;
    }

  </style>
  <link href="/dist/css/arbeidsflyt.css" rel="stylesheet" id="pagemode">
  <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
</head>
<body>

    <!-- Page Content -->
    <div class="container"><div id="contents-body">


        <h5 class="mt-3">OARPC</h5>
        <div class="row">
            <div class="col-lg-4">

                    <div class="form-group">
                        <div class="input-group id-fetch">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="permid"><em>PID</em></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Permission to get ID">
                            <div class="input-group-append">
                                <button class="btn btn-secondary btn-icon" id="permidbtn"><span class="iconify" data-icon="fluent:document-search-20-regular" data-inline="false"></span></button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group id-fetch">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="roleid"><em>RID</em></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Role to get ID">
                            <div class="input-group-append">
                                <button class="btn btn-secondary btn-icon" id="roleidbtn"><span class="iconify" data-icon="fluent:document-search-20-regular" data-inline="false"></span></button>
                            </div>
                        </div>
                    </div>
                            
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header card-title">ROLES</div>
                    <div class="card-body" id="roleList">
                    <?php
                        $items = $uarpc->roles->list();
                        foreach ($items as $perm) {
                            // RoleID, title, description
                            echo '
                            <div class="d-flex align-items-center item">
                                <div class="flex-shrink-0 w-5 tx-10">' . $perm['RoleID'] . '</div>
                                <div class="flex-grow-1">' . $perm['title'] . '</div>
                                <div class="flex-shrink-0 actions w-6 tx-20">
                                    <a href="#" class="action" data-command="delete"><span class="iconify" data-icon="fluent:delete-dismiss-24-regular" data-inline="false"></span></a>
                                </div>
                            </div>
                            ';
                        }
                    ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header card-title">PERMISSIONS</div>
                    <div class="card-body" id="permList">
                        <?php
                            $items = $uarpc->permissions->list();
                            foreach ($items as $perm) {
                                // PermissionID, title, description
                                echo '
                                <div class="d-flex align-items-center item">
                                    <div class="flex-shrink-0 w-5 tx-10">' . $perm['PermissionID'] . '</div>
                                    <div class="flex-grow-1">' . $perm['title'] . '</div>
                                    <div class="flex-shrink-0 actions w-6 tx-20">
                                        <a href="#" class="action" data-command="delete"><span class="iconify" data-icon="fluent:delete-dismiss-24-regular" data-inline="false"></span></a>
                                    </div>
                                </div>
                                ';
                            }
                        ?>
                    </div>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Role name to add">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" id="addrole"><span class="iconify" data-icon="fluent:apps-add-in-20-filled" data-inline="false"></span></button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4">

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Permission name to add">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon" id="addperm"><span class="iconify" data-icon="fluent:apps-add-in-20-filled" data-inline="false"></span></button>
                        </div>
                    </div>
                </div>

            </div>

        </div>




  </div></div>
  <script src="/dist/vendor/jquery/jquery.min.js"></script>
  <script src="/dist/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){

    $("#permidbtn").on('click', function() {
        var dataTitle = $(this).parent().parent().find('input[type="text"]').val();
        if( dataTitle.length ){
            // ajax call
            ajaxCall(dataTitle, 'getpermissionid', 'insertPermId');
            $(this).parent().parent().find('input[type="text"]').val('');
        }
    });
    $("#roleidbtn").on('click', function() {
        var dataTitle = $(this).parent().parent().find('input[type="text"]').val();
        if( dataTitle.length ){
            // ajax call
            ajaxCall(dataTitle, 'getroleid', 'insertRoleId');
            $(this).parent().parent().find('input[type="text"]').val('');
        }
    });
    $("#addperm").on('click', function() {
        var dataTitle = $(this).parent().parent().find('input[type="text"]').val();
        if( dataTitle.length ){
            // ajax call
            ajaxCall(dataTitle, 'addpermission');
            $(this).parent().parent().find('input[type="text"]').val('')
        }
    });
    $("#addrole").on('click', function() {
        var dataTitle = $(this).parent().parent().find('input[type="text"]').val();
        if( dataTitle.length ){
            // ajax call
            ajaxCall(dataTitle, 'addrole');
            $(this).parent().parent().find('input[type="text"]').val('')
        }
    });


    $("#roleList").on('click.action', 'a.action', function(event) {
        event.preventDefault();
        var id = $(this).parent().parent().children().first().html();
        ajaxCall(id, 'deleteRole');
    });
    $("#permList").on('click.action', 'a.action', function(event) {
        event.preventDefault();
        var id = $(this).parent().parent().children().first().html();
        ajaxCall(id, 'deletePerm');
    });

});




function ajaxCall(value, command, reference){

        if(typeof reference === 'undefined')  {
            var reference = 'false';
        }

        var data = {
            'value' : value,
            'command' : command,
            'reference' : reference
        };

        $.ajax({
            type: 'post',
            url: '<?=$_SERVER['REQUEST_URI']?>',
            data: data,
            cache: false,
            error: __ajax_error_handler_v2,
            success: function (response, status, obj) {
                console.log( response );
                if(response.hasOwnProperty('html')){
                    $("#" + response.html5id).append(response.html);
                } else if(response.ref == 'insertPermId'){
                    $("#permid").html( response.id ).closest('.id-fetch').children().addClass('trans-25').first().removeClass('trans-25');
                } else if(response.ref == 'insertRoleId'){
                    $("#roleid").html( response.id ).closest('.id-fetch').children().addClass('trans-25').first().removeClass('trans-25');
                }
            }
        }).always(function (data) { 
            //$('#spinner').hide();
        });


}

    function __ajax_error_handler_v2(response, status, errorThrown) {
        $('#ajaxErrorModal').find(".modal-body").html( response.responseText );
        $('#ajaxErrorModal').find('.modal-header .modal-title').html( response.status + ': ' + errorThrown.toString() );
        $('#ajaxErrorModal').modal('show');
    }
    var isEmpty=function(data){
        if(typeof(data)==='object'){if(JSON.stringify(data)==='{}'||JSON.stringify(data)==='[]'){return!0}else if(!data){return!0}
        return!1}else if(typeof(data)==='string'){if(!data.trim()){return!0}
        return!1}else if(typeof(data)==='undefined'){return!0}else{return!1}
    }

</script>

<div id="ajaxErrorModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="spinner" style="display:none;">
    <div class="center">
        <img src="images/spinner.balls.gif" width="64" height="64" alt="">
        Loading..
    </div>
</div>
</body>
</html>
