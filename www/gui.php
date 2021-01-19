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

    $allowed_commands = ['assign','unassign','listPermissions','getpermissionid', 'getroleid', 'addpermission', 'addrole', 'deleteRole', 'deletePerm'];
    if( in_array($cmd, $allowed_commands) ){

        switch ($cmd) {
            case 'assign':
                $vals = explode(',', $val);
                $jsondata['return'] = $uarpc->permissions->assign($vals[0], $vals[1]);
                $jsondata['command'] = '$uarpc->permissions->assign(' . $vals[0] . ',' . $vals[1] . ')';
                break;
            case 'unassign':
                $vals = explode(',', $val);
                $jsondata['return'] = $uarpc->permissions->unassign($vals[0], $vals[1]);
                $jsondata['command'] = '$uarpc->permissions->unassign(' . $vals[0] . ',' . $vals[1] . ')';
                break;
            case 'listPermissions':
                $list = $uarpc->permissions->list($val);
                $jsondata['permissions'] = array_keys($list);
                $jsondata['command'] = '$uarpc->permissions->list(' . $val . ')';
                break;
            case 'deleteRole':
                $jsondata['id'] = $uarpc->roles->delete($val);
                $jsondata['command'] = '$uarpc->roles->delete(' . $val . ')';
                break;
            case 'deletePerm':
                $jsondata['id'] = $uarpc->permissions->delete($val);
                $jsondata['command'] = '$uarpc->permissions->delete(' . $val . ')';
                break;
            case 'getpermissionid':
                $jsondata['id'] = $uarpc->permissions->id($val);
                $jsondata['command'] = '$uarpc->permissions->id(' . $val . ')';
                break;
            case 'getroleid':
                $jsondata['id'] = $uarpc->roles->id($val);
                $jsondata['command'] = '$uarpc->roles->id(' . $val . ')';
                break;
            case 'addpermission':
                $jsondata['id'] = $uarpc->permissions->add($val,'');
                $jsondata['command'] = '$uarpc->permissions->add(' . $val . ',\'\')';
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
                $jsondata['command'] = '$uarpc->roles->add(' . $val . ',\'\')';
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
        .selected {
            border: 2px solid red;
            padding-left: 9px;
            margin-left: -10px;
            margin-right: -10px;
            padding-right: 9px;
        }
        .connected {
            background-color: #cfc;
            margin-left: -10px;
            padding-left: 9px;
            margin-right: -10px;
            padding-right: 9px;
        }
        #commandLog {
            background-color: #fff;
            padding: 20px;
        }
        #permLoader {
            position: relative;
            background-color: #fff;
            display: none;
        }
        #permLoader .loaderBack {
            background-color: rgba(255,255,255,0.8);
            position: absolute;
            width: 100%;
            top: 0;
            height: 150px;
            z-index: 1000;
        }
        #permLoader .loader {
            position: absolute;
            left: 25%;
            top: -30px;
            z-index: 1001;
        }
        .loader,
        .loader:before,
        .loader:after {
            border-radius: 50%;
        }
        .loader {
            color: #ffffff;
            font-size: 11px;
            text-indent: -99999em;
            margin: 55px auto;
            position: relative;
            width: 10em;
            height: 10em;
            box-shadow: inset 0 0 0 1em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }
        .loader:before,
        .loader:after {
            position: absolute;
            content: '';
        }
        .loader:before {
            width: 5.2em;
            height: 10.2em;
            background: #0dc5c1;
            border-radius: 10.2em 0 0 10.2em;
            top: -0.1em;
            left: -0.1em;
            -webkit-transform-origin: 5.1em 5.1em;
            transform-origin: 5.1em 5.1em;
            -webkit-animation: load2 2s infinite ease 1.5s;
            animation: load2 2s infinite ease 1.5s;
        }
        .loader:after {
            width: 5.2em;
            height: 10.2em;
            background: #0dc5c1;
            border-radius: 0 10.2em 10.2em 0;
            top: -0.1em;
            left: 4.9em;
            -webkit-transform-origin: 0.1em 5.1em;
            transform-origin: 0.1em 5.1em;
            -webkit-animation: load2 2s infinite ease;
            animation: load2 2s infinite ease;
        }
        @-webkit-keyframes load2 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes load2 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
        }
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
                        <div id="permLoader"><div class="loaderBack"></div><div class="loader">Loading...</div></div>
                        <?php
                            $items = $uarpc->permissions->list();
                            foreach ($items as $perm) {
                                // PermissionID, title, description
                                echo '
                                <div class="d-flex align-items-center item">
                                    <div class="flex-shrink-0 w-5 tx-10">' . $perm['PermissionID'] . '</div>
                                    <div class="flex-grow-1">' . $perm['title'] . '</div>
                                    <div class="flex-shrink-0 actions tx-20">
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

        <div class="row">
            <div class="col">
                <div id="commandLog"></div>
            </div>
        </div>

  </div></div>
  <script src="/dist/vendor/jquery/jquery.min.js"></script>
  <script src="/dist/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>

let icon_branch_filled = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none"><path d="M4 5.5a3.5 3.5 0 1 1 4.489 3.358a5.502 5.502 0 0 0 5.261 3.892h.33a3.501 3.501 0 0 1 6.92.75a3.5 3.5 0 0 1-6.92.75h-.33a6.988 6.988 0 0 1-5.5-2.67v3.5A3.501 3.501 0 0 1 7.5 22a3.5 3.5 0 0 1-.75-6.92V8.92A3.501 3.501 0 0 1 4 5.5z" fill="#626262"/></g></svg>';
let icon_branch_regular = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none"><path d="M4 5.5a3.5 3.5 0 1 1 4.489 3.358a5.502 5.502 0 0 0 5.261 3.892h.33a3.501 3.501 0 0 1 6.92.75a3.5 3.5 0 0 1-6.92.75h-.33a6.987 6.987 0 0 1-5.5-2.67v3.5A3.501 3.501 0 0 1 7.5 22a3.5 3.5 0 0 1-.75-6.92V8.92A3.501 3.501 0 0 1 4 5.5zm3.5-2a2 2 0 1 0 0 4a2 2 0 0 0 0-4zm0 13a2 2 0 1 0 0 4a2 2 0 0 0 0-4zm8-3a2 2 0 1 0 4 0a2 2 0 0 0-4 0z" fill="#626262"/></g></svg>';

let RoleID          = null;
let PermissionID    = null;

$(document).ready(function(){

    // GET ID
    $("#permidbtn").on('click', function() {
        var dataTitle = $(this).parent().parent().find('input[type="text"]').val();
        if( dataTitle.length ){
            ajaxCall(dataTitle, 'getpermissionid', 'insertPermId');
            $(this).parent().parent().find('input[type="text"]').val('');
        }
    });
    $("#roleidbtn").on('click', function() {
        var dataTitle = $(this).parent().parent().find('input[type="text"]').val();
        if( dataTitle.length ){
            ajaxCall(dataTitle, 'getroleid', 'insertRoleId');
            $(this).parent().parent().find('input[type="text"]').val('');
        }
    });
    // ADD
    $("#addperm").on('click', function() {
        var dataTitle = $(this).parent().parent().find('input[type="text"]').val();
        if( dataTitle.length ){
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
    // ACTIONS FOR ROLES
    $("#roleList").on('click.action', 'a.action', function(event) {
        event.preventDefault();
        var id = $(this).parent().parent().children().first().html();
        if( $(this).data('command') == 'delete' ){
            ajaxCall(id, 'deleteRole');
            $(this).parent().parent().remove();
        }
    });
    // ACTIONS FOR PERMISSIONS
    $("#permList").on('click.action', 'a.action', function(event) {
        event.preventDefault();
        var id = $(this).parent().parent().children().first().html();
        if( $(this).data('command') == 'delete' ){
            ajaxCall(id, 'deletePerm');
            $(this).parent().parent().remove();
        } else if( $(this).data('command') == 'disconnect' ){
            ajaxCall(id + ',' + RoleID, 'unassign');
            el = $(this).closest('.item');
            $(el).removeClass('connected').find('[data-command="disconnect"]').remove();
            $(el).find('.actions').append('<a href="#" data-command="connect" class="action">' + icon_branch_regular + '</a>');
        } else if( $(this).data('command') == 'connect' ){
            ajaxCall(id + ',' + RoleID, 'assign');
            el = $(this).closest('.item');
            $(el).addClass('connected').find('[data-command="connect"]').remove();
            $(el).find('.actions').append('<a href="#" data-command="disconnect" class="action">' + icon_branch_filled + '</a>');
        } else {
            console.log( 'ERROR: command missing, ' + $(this).data('command') ); 
        }
    });

    // SETTING THE ROLE
    $("#roleList").on('click.action', 'div.item', function(event) {
        event.preventDefault();

        if( $(this).hasClass('selected') ){
            $(this).removeClass('selected');
            RoleID = null;
            PermissionID = null;

            $('#permList .item').each(function(index, value){
                $(this).removeClass('connected');
                $(this).find('.actions').html('<a href="#" class="action" data-command="delete"><span class="iconify" data-icon="fluent:delete-dismiss-24-regular" data-inline="false"></span></a>');
            });

            return false;
        }

        $(this).parent().children().each(function(index, value){
            $(this).removeClass('selected');
        });

        $(this).addClass('selected');
        RoleID = $(this).children().first().html();
        console.log( 'RoleID: ' + RoleID);
        PermissionID = null;

        $('#permList .item').each(function(index, value){
            $(this).removeClass('connected').find('.actions').html('<a href="#" data-command="connect" class="action">' + icon_branch_regular + '</a>');
        });
        ajaxCall(RoleID,'listPermissions');
        var height = $('#permLoader').parent().height();
        $('#permLoader .loaderBack').height( height ).parent().show();
    });

    /*
    $("#permList").on('click.action', 'div.item', function(event) {
        event.preventDefault();

        $(this).parent().children().each(function(index, value){
            $(this).removeClass('selected');
        });

        $(this).addClass('selected');
    });
    */

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

                if (response.hasOwnProperty('command')) {
                    $('#commandLog').append( response.command + '<br>' );
                }

                if (response.hasOwnProperty('permissions')) {
                    console.log( response.permissions );
                    $('#permList .item').each(function(index, value){
                        var PID = parseInt( $(this).children().first().html(), 10 );
                        if(jQuery.inArray(PID, response.permissions) !== -1){
                            $(this).addClass('connected').find('[data-command="connect"]').remove();
                            $(this).find('.actions').append('<a href="#" data-command="disconnect" class="action">' + icon_branch_filled + '</a>');
                            console.log('HIT');
                        }
                    });
                    $('#permLoader').hide();
                } else if(response.hasOwnProperty('html')){
                    $("#" + response.html5id).append(response.html);
                } else if(response.ref == 'insertPermId'){
                    $("#permid").html( response.id ).closest('.id-fetch').children().addClass('trans-25').first().removeClass('trans-25').addClass('add-focus');
                } else if(response.ref == 'insertRoleId'){
                    $("#roleid").html( response.id ).closest('.id-fetch').children().addClass('trans-25').first().removeClass('trans-25').addClass('add-focus');
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
