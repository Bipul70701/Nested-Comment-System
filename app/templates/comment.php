<?php
function helper($a)
{
    $date1 = strtotime($a);
    $date2 = strtotime("now");
    $diff = abs($date2 - $date1);
    
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 -$months*30*60*60*24)/ (60*60*24));
    $hours = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24)/ (60*60));
    $minutes = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24- $hours*60*60)/ 60);
    $seconds = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24- $hours*60*60 - $minutes*60));

    if($years!=0)
    return($years." Year ago");
    else if($months!=0)
    return($months." Month ago");
    else if($days!=0)
    return($days." Day ago");
    else if($hours!=0)
    return($hours." Hour ago");
    else if($minutes!=0)
    return($minutes." Minute ago");
    else
    return($seconds." Second ago");

}

function view_reply($loggedIn,$data,$wholedata,$name)
{
        // echo("<div>");
        // echo("<a href='javascript:void(0)' class='link-muted'float:'right'>View Replies</a>");
        // echo("</div>");
        create_reply_row($wholedata,$loggedIn,$data['CommentID'],$name);
}

function create_likes_dislikes($loggedIn,$data,$name)
{
    if($loggedIn==true)
    {
        echo("<button class='btn' id='Like' onclick='Like(this)' like=".$data['Likes']." data-commentID=".$data['CommentID']."><i class='fa fa-thumbs-up fa-lg' aria-hidden='true'></i></button>");
    }
    else
    {
        echo("<button class='btn'><i class='fa fa-thumbs-up fa-lg' aria-hidden='true'></i></button>");
    }
   echo($data['Likes']);

   if($loggedIn==true)
    {
        echo("<button class='btn' id='DisLike' onclick='DisLike(this)' dislike=".$data['Dislikes']." data-commentID=".$data['CommentID']."><i class='fa fa-thumbs-down fa-lg' aria-hidden='true'></i></button>");
    }
    else
    {
        echo("<button class='btn'><i class='fa fa-thumbs-down fa-lg' aria-hidden='true'></i></button>");
    }
   echo($data['Dislikes']);

   if($loggedIn==true)
   {
        echo("<button class='btn'  data-commentID=".$data['CommentID']." onclick='reply(this)'><i class='fa fa-reply' aria-hidden='true'>    Reply</i></button>");
        if($data['Name']==$name)
        echo("<button type='button' class='btn btn-link' data-commentID=".$data['CommentID']." onclick='edit(this)'>Edit</button>");
   }
   
}

function create_reply_row($wholedata,$loggedIn,$Parent_Comment_ID,$name)
{
    foreach($wholedata as $res)
    {
        if($res['Parent_ID']==$Parent_Comment_ID)
        {

            echo(
                "<div class='replies'> 
                <div class='comments'>
                <div class='user'>".$res['Name']."<span class='time'> ".helper($res['Time'])."</span></div>
                <div class='userComment'>".$res['Comment']." </div>
            ");
            create_likes_dislikes($loggedIn,$res,$name);

            view_reply($loggedIn,$res,$wholedata,$name);
            
            echo("
                    </div>
                    </div>"
                );
        }
    }
}

function Create_Comment_Row($data,$loggedIn,$wholedata,$name)
{
    echo("
   
    <div class='userComments'>
    <div class='comments'>
        <div class='user'>".$data['Name']."<span class='time'> ".helper($data['Time'])."</span></div>
        <div class='userComment'>".$data['Comment']." </div>
        
        <div class='justify-content-between align-items-center'>
        <div class='align-items-center'>");
        create_likes_dislikes($loggedIn,$data,$name);
        echo("</div></div>");

        view_reply($loggedIn,$data,$wholedata,$name);
            
   echo("
   
   </div>
    </div>
   
    ");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style type="text/css">
        .link-muted { color: #aaa; } .link-muted:hover { color: #1266f1; }
        .comments {
            margin-bottom: 20px;
        }
        .user{
            font-weight:bold;
            color:black;
        }
        .time{
            color:gray;
        }
        .userComment{
            color:#000;
        }
        .replies.comments{
            margin-top:20px;
        }
        .replies{
            margin-left:20px;
           
        }
        #registerModal input, #logInModal input {
            margin-top: 10px;
        }
        body{
        margin: 40px;
        }

        button{
        cursor: pointer;
        outline: 0;
        color: #AAA;

        }

        .btn:focus {
        outline: none;
        }

        .Like{
        color: green;
        }

        .DisLike{
        color: red;
        }
        </style>
</head>
<body>
<script src="https://use.fontawesome.com/fe459689b4.js"></script>
<div class="modal" id="registerModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Registration Form</h5>
                </div>
                    <div class="modal-body">
                        
                        <input type="text" id="userName" class="form-control" name="ii" placeholder="Your Name">
                        <input type="email" id="userEmail" class="form-control" placeholder="Your Email">
                        <input type="password" id="userPassword" class="form-control" placeholder="Password">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" id="registerBtn">Register</button>
                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

            </div>
        </div>
    </div>

    <div class="modal" id="logInModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log In Form</h5>
                </div>
                <div class="modal-body">
                    <input type="email" id="userLEmail" class="form-control" placeholder="Your Email">
                    <input type="password" id="userLPassword" class="form-control" placeholder="Password">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="loginBtn">Log In</button>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


<!-- Buttons -->
<div class="container" style="margin-top:20px;">
    <div class="row">
        <div class="col-rd-12" align="right">
        <?php
            if(isset($loggedIn))
            {
                if (!$loggedIn)
                    echo '
                            <button class="btn btn-primary" data-toggle="modal" data-target="#registerModal">Register</button>
                            <button class="btn btn-success" data-toggle="modal" data-target="#logInModal">Log In</button>
                    ';
                else
                    echo '
                        <button class="btn btn-warning" id="logout">Log Out</button>
                    ';
            }
        ?>
        </div>
    </div>
</div>

<!-- Blog -->
<div class="container">
    <div class="row" style="margin-top:20px;margin-bottom:20px;">
        <div class="col-rd-12" align="center">
            <h1><?php  echo("Blog No. ".$page);?></h1>
           <p> "On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains ow is the deep bosom of the ocean buried.
Now is the souls of a lady's changed war hath smooth'd his sun of fearful marches to merry meetings,
Now is the winter of a lute.
But I, that am rudely stamp'd, and wanton ambling nymph;
I, that lour'd upon our house
In the souls of a lute.
But I, that am curtail'd of York;
And now, instead of fearful marches to delightful marches to merry meetings,
Our brows bound with victorious pleasing barded stern alarums changed war hath smooth'd his wreaths;
Our stern alarums changed want lour'd upon our house
In the souls of York;
And now, instead of fearful adversaries,
Our discontent
Made to court an amorous wrinkled front;
And all the winter of York;
And now, instead of a lute.
But I, that lour'd upon our house
In the winter of York;
And now, instead of our brows bound with victorious looking-glass;
I, that am rudely stamp'd, and with victorious wrinkled for monuments;
Our brows bound war hath smooth'd his fair proportion,
'd, and with victorious pleasing barded steeds
To the souls of a lady's changed to court an amorous wreaths;
Our brows bound with victorious looking-glass;
I, that am not shaped front;
And now, instead of our bruised arms hung up for sportion,
To the ocean buried.
Nor made to court an amorous sun of this wrinkled front;
And now, instead of fearful marches to court an ambling nymph;
I, that am rudely stamp'd, and with victorious looking-glass;
Our discontent
Made to delight the winter of the winter of York;
And now, instead of a lady's chamber
To the ocean buried.
Now are our house
In the deep bosom of a lute.
But I, that am not shaped front;
And now, instead of our dreadful adversaries,
Nor made glorious summer by this fair proportion,
of a lute.
But I, that am curtail'd of our house
In the deep bosom of a lute.
But I, that am rudely stamp'd, and with victorious looking-glass;
I, that am curtail'd of York;
And all the deep bosom of fearful measures.
Grim-visaged to court an amorous pleasing nymph;
I, that am rudely stamp'd, and want lour'd upon our house
In the clouds that am curtail'd of this wreaths;
I, that am not shaped for sportive tricks,
Our steeds
To the deep bosom of York;
And now, instead of a lute.
But I, that am not shaped for monuments;
Our discontent
Made glorious summer by this sun of our discontent
Made glorious pleasing nymph;
I, that am curtail'd of a lady's chamber
To the ocean buried.
Nor monuments;"</p>
        </div>
    </div>
</div>


<!-- Add Comment -->
<?php
if(isset($loggedIn))
{
    if($loggedIn==true)
    {
        echo("
    <div class='container'>
        <div class='row'>
            <div class='col-rd-12'>
                <textarea class='form-control' id='mainComment' placeholder='Add Public Comment' cols='30' rows='2'></textarea><br>
                <button style='float:right' class='btn-primary btn' id='addComment'>Add Comment</button>
            </div>
        </div>
    </div>
    ");
    }
}

?>



<!-- Comments -->
<div class="container">
    <div class="row">
        <div class="col-rd-12">
            <h2><b><?php
            if(isset($Total_Comments))
            {
                echo $Total_Comments;
            }
            ?> Comments</b></h2>
            <!-- Comments -->
            <?php
            if(isset($results)) {
                foreach($results as $res)
                {
                    $b=false;
                    if(isset($loggedIn))
                    {
                        $b=$loggedIn;
                    }
                    if($res['Parent_ID']==null)
                    Create_Comment_Row($res,$b,$results,$name);    
                }
            }
            ?>
            
            <div class="row replyRow" style="display:none">
                <div class="col-md-12">
                    <textarea class="form-control" id="replyComment" placeholder="Add Public Comment" cols="20" rows="2"></textarea><br>
                    <button style="float:right" class="btn-primary btn" onclick="isReply = true;" id="addReply">Add Reply</button>
                    <button style="float:right" class="btn-default btn" onclick="$('.replyRow').hide();">Close</button>
                </div>
            </div>

            <div class="row editRow" style="display:none">
                <div class="col-md-12">
                    <textarea class="form-control" id="editComment" placeholder="Edit Comment" cols="20" rows="2"></textarea><br>
                    <button style="float:right" class="btn-primary btn" id="EditComment">Edit Comment</button>
                    <button style="float:right" class="btn-default btn" onclick="$('.editRow').hide();">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>


<center>
    <p class="mt-5 mb-3 text-muted"> Made By:- Bipul Kumar Sharma</p>
</center>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script type="text/javascript">
    var Comment_ID=0;
    function DisLike(caller)
    {
                    $.ajax({
                        url: '/DisLike',
                        method: 'POST',
                        dataType: 'text',
                        data:{
                            commentid:$(caller).attr('data-commentID'),
                            DisLike:$(caller).attr('dislike'),
                        },success: function(response){
                            console.log(response);
                            window.location.replace("/comment/<?php
                            if(isset($page))
                            {
                                echo $page;
                            }
                            else echo 1;
                            ?>");
                        }
                        });
    }

    function Like(caller)
    {
        
                    $.ajax({
                        url: '/Like',
                        method: 'POST',
                        dataType: 'text',
                        data:{
                            commentid:  $(caller).attr('data-commentID'),
                            Like:   $(caller).attr('like'),
                        },success: function(response){
                            console.log(response);
                            window.location.replace("/comment/<?php
                            if(isset($page))
                            {
                                echo $page;
                            }
                            else echo 1;
                            ?>");
                        }
                    });
        
    }

    $("#addReply").on('click', function () {
               var comment = $("#replyComment").val();
               if (comment.length > 5) {
                    $.ajax({
                        url: '/reply',
                        method: 'POST',
                        dataType: 'text',
                        data: {
                            addComment: 1,
                            comment: comment,
                            comment_id:Comment_ID,
                            page:<?php
                            if(isset($page))
                            {
                                echo $page;
                            }
                            else echo 1;
                            ?>
                        }, success: function (response) {
                            console.log(response);
                            window.location.replace("/comment/<?php
                            if(isset($page))
                            {
                                echo $page;
                            }
                            else echo 1;
                            ?>");
                        }
                    });
               } else
                   alert('Comment Should be more than 5 Characters');
           });
    

    $("#addComment").on('click', function () {
               var comment = $("#mainComment").val();

               if (comment.length > 5) {
                    $.ajax({
                        url: '/add',
                        method: 'POST',
                        dataType: 'text',
                        data: {
                            addComment: 1,
                            comment: comment,

                            page:<?php
                            if(isset($page))
                            {
                                echo $page;
                            }
                            else echo 1;
                            ?>
                        }, success: function (response) {
                            console.log(response);
                            window.location.replace("/comment/<?php
                            if(isset($page))
                            {
                                echo $page;
                            }
                            else echo 1;
                            ?>");
                        }
                    });
               } else
                   alert('Please Check Your Inputs');
           });

           $("#logout").on('click', function () {
               
                    $.ajax({
                        url: '/logout',
                        method: 'POST',
                        dataType: 'text',
                        data: {
                            logout: 1,
                           
                        }, success: function (response) {
                            console.log(response);
                            window.location.replace("/comment/<?php
                            if(isset($page))
                            {
                                echo $page;
                            }
                            else echo 1;
                            ?>");
                        }
                    });
           });

     $("#registerBtn").on('click', function () {
               var name = $("#userName").val();
               var email = $("#userEmail").val();
               var password = $("#userPassword").val();

               if (name != "" && email != "" && password != "") 
               {
                    $.ajax({
                        url: '/register',
                        method: 'POST',
                        dataType: 'text',
                        data: {
                            register:1,
                            name: name,
                            email: email,
                            password: password
                        }, success: function (response) 
                        {
                            if (response === "failedEmail")
                                alert('Please insert valid email address!');
                            else if (response === "failedUserExists")
                                alert('User with this email already exists!');  
                            else
                            {
                                window.location = window.location;
                                console.log(response);
                            }
                        }
                    });
               } else
                   alert('Please Check Your Inputs');
           });

           $("#loginBtn").on('click', function () {
               var email = $("#userLEmail").val();
               var password = $("#userLPassword").val();

               if (email != "" && password != "") {
                    $.ajax({
                        url: '/login',
                        method: 'POST',
                        dataType: 'text',
                        data: {
                            logIn: 1,
                            email: email,
                            password: password
                        }, success: function (response) {
                            console.log(response);
                            if (response === 'failed')
                                alert('Please check your login details!');
                            else
                            {
                                window.location = window.location;
                            }
                        }
                    });
               } else
                   alert('Please Check Your Inputs');
           });

           function reply(caller) 
           {
                Comment_ID = $(caller).attr('data-commentID');
                $(".replyRow").insertAfter($(caller));
                $('.replyRow').show();
           }

           function edit(caller) 
           {
                $(".editRow").insertAfter($(caller));
                $('.editRow').show();
                $("#EditComment").on('click', function () {
                    var comment = $("#editComment").val();
                    $.ajax({
                            url: '/Edit',
                            method: 'POST',
                            dataType: 'text',
                            data:{
                                commentid:  $(caller).attr('data-commentID'),
                                comment: comment
                            },success: function(response){
                                console.log(response);
                                window.location.replace("/comment/<?php
                                if(isset($page))
                                {
                                    echo $page;
                                }
                                else echo 1;
                                ?>");
                            }
                        });
                });
           }
</script>

</body>
</html>