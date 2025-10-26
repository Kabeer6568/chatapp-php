<?php
session_start();
// require_once "config/db.php";

include "includes/header.php";
require_once "classes/user.php";
require_once "classes/chat.php";

$user = new User;
$chat = new Chat;


$user->sessionCheck();

$users = $chat->fetchUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Chat Room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container bootstrap snippets bootdey">
    <div class="row">
		<div class="col-md-4 bg-white ">
            <div class=" row border-bottom padding-sm" style="height: 40px;">
            	Member
            </div>
            
            <!-- =============================================================== -->
            <!-- member list -->
            <ul class="friend-list">
				<?php foreach ($users as $user): {
					
			
					
				 ?>
                <li class="active bounceInDown" onclick="openChat(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>');">
                	<a href="#" class="clearfix">
                		<img src="<?php  echo htmlspecialchars($user['profile_pic']); ?>" alt="" class="img-circle">
                		<div class="friend-name">	
                			<strong><?php  echo htmlspecialchars($user['username']); ?></strong>
                		</div>
                		<div class="last-message text-muted"><?php  echo htmlspecialchars($user['status']); ?></div>
                		<small class="time text-muted">Just now</small>
                		<small class="chat-alert label label-danger">1</small>
                	</a>
                </li>
                     <?php } endforeach; ?>      
            </ul>
		</div>
        
        <!--=========================================================-->
        <!-- selected chat -->
    	<div class="col-md-8 bg-white ">
            <div class="chat-message">
                <ul class="chat">
                    <li class="left clearfix">
                    	<span class="chat-img pull-left">
                    		<img src="https://bootdey.com/img/Content/user_3.jpg" alt="User Avatar">
                    	</span>
                    	<div class="chat-body clearfix">
                    		<div class="header">
                    			<strong class="primary-font">John Doe</strong>
                    			<small class="pull-right text-muted"><i class="fa fa-clock-o"></i> 12 mins ago</small>
                    		</div>
                    		<p>
                    			Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    		</p>
                    	</div>
                    </li>
                    <li class="right clearfix">
                    	<span class="chat-img pull-right">
                    		<img src="https://bootdey.com/img/Content/user_1.jpg" alt="User Avatar">
                    	</span>
                    	<div class="chat-body clearfix">
                    		<div class="header">
                    			<strong class="primary-font">Sarah</strong>
                    			<small class="pull-right text-muted"><i class="fa fa-clock-o"></i> 13 mins ago</small>
                    		</div>
                    		<p>
                    			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. 
                    		</p>
                    	</div>
                    </li>
                                     
                </ul>
            </div>
            <div class="chat-box bg-white">
            	<div class="input-group" id="chatInput">
            		<input class="form-control border no-shadow no-rounded" placeholder="Type your message here">
            		<span class="input-group-btn">
            			<button class="btn btn-success no-rounded" type="button">Send</button>
            		</span>
            	</div><!-- /input-group -->	
            </div>            
		</div>        
	</div>
</div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="assets/js/style.js"></script>

</body>
</html>