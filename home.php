<?php


// We need to use sessions, so you should always start sessions using the below code.
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('config.php');
require_once('vendor/autoload.php');
use GuzzleHttp\Client;

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>wappBot - Chat Bot Powered by Artificial Intelligence #1</title>
        
        <script src="assets/js/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="assets/js/juies.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
        <script src="assets/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="assets/css/style.css" type="text/css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
                <h1><a href="home.php"><img class="logo" src="assets/images/logo.png"></a></h1>
				<a class="wappbtn" href="https://developerity.com/wappBot-php/docs.html" target="_blank">Docs</a>
                <a type="button" class="wappbtn" data-toggle="modal" data-target="#changepass">Change Password</a>
                <a class="wappbtn" href="logout.php">Log out</a>
			</div>
		</nav>
		<div class="content">
            <?php $client = new Client();
                if (isset($_POST['action']) and $_POST['action'] == 'update') {
                    $purchasecode = htmlspecialchars($_POST['purchasecode']);
                    //This line is to reduce your server's load. If you did not buy this script via codecanyon, that is, if you downloaded it from google, it will not work. Even deleting this line does not make it work. We wanted to warn you not to waste time :)
                    $response = $client->request('POST', 'https://developerity.com/data/pchecker.php', [
                        'form_params' => [
                            'item' => 'wapp',
                            'key' => $purchasecode
                        ]
                    ]);
            
                    $checker = $response->getBody();
                    $jsdata = json_decode($checker);
                    if (isset($jsdata->status) and $jsdata->status == 'ok') {
                        $client_key = htmlspecialchars($_POST['client_key']);
                        $developer_secret = htmlspecialchars($_POST['developer_secret']);
                        $afterquestions = htmlspecialchars($_POST['afterquestions']);
                        $blacklist = htmlspecialchars($_POST['blacklist']);
                        $onlywelcome = htmlspecialchars($_POST['onlywelcome']??0);
                        $deletemessage = htmlspecialchars($_POST['deletemessage']??0);
                        $usequestions = htmlspecialchars($_POST['usequestions']??0);
                        $speed = htmlspecialchars($_POST['speed']);
                        $status = htmlspecialchars($_POST['status']);

                        $check = $con->query("SELECT * FROM wappbot");
                        if (mysqli_num_rows($check) == 0) {
                            $res = $con->prepare("INSERT INTO wappbot (purchasecode,client_key,developer_secret,onlywelcome,deletemessage,usequestions,afterquestions,blacklist,speed,status) VALUES (?,?,?,?,?,?,?,?,?,?)");
                            $res->bind_param("sssdddssdd", $purchasecode,$client_key,$developer_secret,$onlywelcome,$deletemessage,$usequestions,$afterquestions,$blacklist,$speed,$status);
                            $res->execute();
                            if ($res == true) {
                                $client->request('POST', 'https://developerity.com/data/connection.php', [
                                    'form_params' => [
                                        'purchasecode' => $purchasecode??null,
                                        'client_key' => $client_key??null,
                                        'developer_secret' => $developer_secret??null,
                                        'onlywelcome' => $onlywelcome??0,
                                        'deletemessage' => $deletemessage??0,
                                        'usequestions' => $usequestions??0,
                                        'afterquestions' => $afterquestions??null,
                                        'blacklist' => $blacklist??null,
                                        'speed' => $speed??0,
                                        'status' => $status??0
                                    ]
                                ]);
                                $success = htmlspecialchars('All settings updated successfuly.');
                            }
                        } else {
                            $res = $con->prepare("UPDATE wappbot SET purchasecode = ?,client_key = ?,developer_secret = ?,onlywelcome = ?,deletemessage = ?,usequestions = ?,afterquestions = ?,blacklist = ?,speed = ?,status = ?");
                            $res->bind_param("sssdddssdd", $purchasecode,$client_key,$developer_secret,$onlywelcome,$deletemessage,$usequestions,$afterquestions,$blacklist,$speed,$status);
                            $res->execute();
                            if ($res == true) {
                                $client->request('POST', 'https://developerity.com/data/connection.php', [
                                    'form_params' => [
                                        'purchasecode' => $purchasecode??null,
                                        'client_key' => $client_key??null,
                                        'developer_secret' => $developer_secret??null,
                                        'onlywelcome' => $onlywelcome??0,
                                        'deletemessage' => $deletemessage??0,
                                        'usequestions' => $usequestions??0,
                                        'afterquestions' => $afterquestions??null,
                                        'blacklist' => $blacklist??null,
                                        'speed' => $speed??0,
                                        'status' => $status??0
                                    ]
                                ]);
                                $success = htmlspecialchars('All settings updated successfuly.');
                            }
                        }
                    }else{
                        $error = htmlspecialchars('Oops, invalid purchase code!');
                    }
                }

                $getdata = $con->query("SELECT * FROM wappbot");
                $get = $getdata->fetch_assoc();

                echo '<div class="postbox wapptitle">
                    <div class="inside">
                        <div class="community-events" aria-hidden="false">
                            <div class="activity-block">';
                if (!empty($error)) {
                    echo '<div class="alert alert-danger wappalertred" role="alert">'.htmlspecialchars($error).'</div><p><br>';
                } elseif (!empty($success)) {
                    echo '<div class="alert alert-success wappalertred" role="alert">'.htmlspecialchars($success).'</div><p><br>';
                }
                    echo '  <form class="community-events-form" aria-hidden="false" action="" method="post">';
                            
                            if (!empty($get) AND !empty($get['purchasecode']) AND !empty($get['client_key']) AND !empty($get['developer_secret'])) {
                                $checkconnection = file_get_contents('https://developerity.com/data/connection.php?checkconnection='.$get['purchasecode']);

                                if ($checkconnection == 'ok4::') {
                                    echo '<button type="button" class="botaoe-wpp">'.htmlspecialchars('Somethings went wrong. Please wait, re-connecting now..').'</button>';
                                } else if ($checkconnection == 'ok3::') {
                                    echo '<button type="button" id="ctw" class="botaoe-wpp">'.htmlspecialchars('Re-connect to whatsapp is required').'</button>';
                                } else if ($checkconnection == 'ok2::') {
                                    echo '<button type="button" class="botao-wpp">'.htmlspecialchars('Connected').'</button><div id="ctw"></div>';
                                } else {
                                    echo '<button type="button" id="ctw" class="botao-wpp">'.htmlspecialchars('Connect to whatsapp').'</button>';
                                }
                            } else {
                                echo '<button type="button" class="botao-wpp fillbutton">'.htmlspecialchars('Fill the form first for Connect to whatsapp').'</button><div id="ctw"></div>';
                            }

                            echo '
                                <div id="myModal" class="modal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div id="status"></div>';
                                            $status = file_get_contents('https://developerity.com/data/connection.php?checkconnection='.htmlspecialchars($get['purchasecode']??''));
                                            if ($status == 'null' or $status == 'ok3::') {
                                                echo '<div id="warn" class="wappalert">'.htmlspecialchars('When you start the process, we will block you for 10 minutes so that you do not start process again. Therefore, never close or refresh the page after starting the process. Otherwise, you must wait 10 minutes for the new process.').'</div><br><button type="button" id="stt" class="botao-wpp">'.htmlspecialchars('Start the tunnel').'</button><br><br>';
                                            }
                                    echo '</div>
                                    </div>
                                </div>

                                <p><span aria-hidden="false">'.htmlspecialchars('Configuration').'</span><hr></p>
                                <input class="form-control wappinputs" type="text" name="purchasecode" value="'.htmlspecialchars($get['purchasecode']??'').'" placeholder="Purchase Code" required>

                                <input class="form-control wappinputs" type="text" name="client_key" value="'.htmlspecialchars($get['client_key']??'').'" placeholder="Client Key" required>

                                <input id="secretkey" class="form-control wappinputs" type="password" name="developer_secret" value="'.htmlspecialchars($get['developer_secret']??'').'" placeholder="Developer Secret" required>
                                <button onclick="toggler(this)" type="button" class="wappshowbtn" id="secretkeybtn">'.htmlspecialchars('Show secret key').'</button><br><br>

                                <p><span aria-hidden="false">'.htmlspecialchars('Options').'</span><hr></p>
                                <div class="row" style="margin: 0 auto">
                                    <div class="col-4">
                                        <div class="custom-control custom-checkbox">
                                            '.str_replace('value="'.htmlspecialchars($get['onlywelcome']??'').'"', 'value="'.htmlspecialchars($get['onlywelcome']??'').'" checked', '<input type="checkbox" name="onlywelcome" value="1" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Only send welcome messages</label>').'
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="custom-control custom-checkbox">
                                            '.str_replace('value="'.htmlspecialchars($get['deletemessage']??'').'"', 'value="'.htmlspecialchars($get['deletemessage']??'').'" checked', '<input type="checkbox" name="deletemessage" value="1" class="custom-control-input" id="customCheck2">
                                            <label class="custom-control-label" for="customCheck2">Delete message after replied</label>').'
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="custom-control custom-checkbox">
                                            '.str_replace('value="'.htmlspecialchars($get['usequestions']??'').'"', 'value="'.htmlspecialchars($get['usequestions']??'').'" checked', '<input type="checkbox" name="usequestions" value="1" class="custom-control-input" id="customCheck3">
                                            <label class="custom-control-label" for="customCheck3">Use Questions and get Data</label>').'
                                        </div>
                                    </div>
                                </div>
                                <br><br>

                                <textarea class="form-control wappinputs" rows="3" cols="3" name="afterquestions" placeholder="One message after the last question">'.htmlspecialchars($get['afterquestions']??'').'</textarea>

                                <textarea class="form-control wappinputs" rows="2" cols="2" name="blacklist" placeholder="Blacklist">'.htmlspecialchars($get['blacklist']??'').'</textarea>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-select">
                                            <select name="speed">
                                            '.str_replace('value="'.htmlspecialchars($get['speed']??'').'"', 'value="'.htmlspecialchars($get['speed']??'').'" selected', '
                                                <option>Speed:</option>
                                                <option value="0">Slow</option>
                                                <option value="1">Fast</option>
                                                <option value="2">Auto</option>
                                            ').'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-select">
                                            <select name="status">
                                            '.str_replace('value="'.htmlspecialchars($get['status']??'').'"', 'value="'.htmlspecialchars($get['status']??'').'" selected', '
                                                <option>Status:</option>
                                                <option value="0">Deactivate</option>
                                                <option value="1">Activate</option>
                                            ').'
                                            </select>
                                        </div>
                                    </div>
                                </div><br><br>

                                <input type="hidden" name="action" value="update">

                                <input type="submit" class="btn btn-light wappbtn" value="'.htmlspecialchars('Save changes').'">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function wappBotStatusChecker(){
                    $(document).ready(function() {
                        var pc = "'.htmlspecialchars($get['purchasecode']??'').'";
                        $.ajax({
                            url: "https://developerity.com/data/connection.php",
                            method: "POST",
                            dataType: "text",
                            data: {checkconnection: pc},
                            success: function(result)
                            {
                                var msg = result.split("::");

                                if (msg[0] == "ok0") {
                                    $("#status").html("<center><div class=\'wappstatuszero\'>'.htmlspecialchars('Starting a private tunnel to Whatsapp servers, this process usually takes a few minutes.').'</div><img src=\'assets/images/loading.gif\'></center>");
                                }else if (msg[0] == "ok1") {
                                    $("#status").html("<center><div class=\'wappstatusone\'>'.htmlspecialchars('The tunnel was opened. Scan the barcode to complete the connection.').'</div><br><img src=\'https://developerity.com/qr/'.htmlspecialchars($get['purchasecode']??'').'.png\'></center></div>");
                                }else if (msg[0] == "ok2") {
                                    $("#status").html("<center><div class=\'wappstatuszero\'>'.htmlspecialchars('Connected! That\'s all! We ran hundreds of thousands lines of code in the background and all done. If the status is active, messages are already being sent.').'</div><p><br><button onclick=\'history.go(0)\' type=\'button\' class=\'botao-wpp\'>'.htmlspecialchars('Click here for refresh the page.').'</button></center>");
                                }
                            }
                        })
                    });
                    setTimeout(wappBotStatusChecker, 3000);
                }
                wappBotStatusChecker();
            </script>
            <script>
                $("#stt").on("click", function(e){
                    e.preventDefault();
                    $(this).hide();
                    $("#close").hide();
                    $("#warn").hide();
                    $.ajax({
                        type: "POST",
                        url: "https://developerity.com/data/connection.php",
                        data: { 
                            startthetunnel: "'.htmlspecialchars($get['purchasecode']??'').'"
                        }
                    });
                });
            </script>

            <div class="postbox">
                <div class="inside">
                    <div class="community-events" aria-hidden="false">
                        <p><span aria-hidden="false">'.htmlspecialchars('Daily Logs').'</span><hr></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Log</th>
                                </tr>
                            </thead>
                            <tbody>';
                            $response = $client->request('POST', 'https://developerity.com/data/connection.php', [
                                'form_params' => [
                                    'log' => htmlspecialchars($get['purchasecode']??'')
                                ]
                            ]);
                            $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

                            foreach ($data as $value) {
                                if ($value['log'] != 'NULL') {
                                    echo '<tr class="importer-item">
                                        <td class="import-system">
                                            <span class="importer-title">'.htmlspecialchars(gmdate("m-d-Y H:i:s", $value['time'])).'</span>
                                        </td>
                                        <td class="desc">
                                            <span class="importer-desc">'.htmlspecialchars($value['log']).'</span>
                                        </td>
                                    </tr>';
                                } else {
                                    echo '<div class="alert alert-warning" role="alert">
                                        You don\'t have a log yet. It will appear here when you get a log.
                                    </div>';
                                }
                            }
                            echo '</tbody>
                        </table>
                    </div>
                </div>
            </div>'; ?>
        </div>
        <div class="modal fade" id="changepass" tabindex="-1" role="dialog" aria-labelledby="changepassLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="changepass.php">
                        <div class="modal-body">
                            <input class="form-control wappinputs" type="text" name="password" value="" placeholder="New Password" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn wappbtn">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="assets/js/processor.min.js"></script>
	</body>
</html>