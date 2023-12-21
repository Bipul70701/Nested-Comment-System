<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

session_start();

$loggedIn = false;

if (isset($_SESSION['loggedIn']) && isset($_SESSION['name'])) {
    $loggedIn = true;
}



$app->get('/',function(Request $request,Response $response)
{
    
    $renderer = new PhpRenderer('../templates');

    return $renderer->render($response, "1.php");
});

$app->get('/comment/{page}',function(Request $request,Response $response,array $args)
{
    $renderer = new PhpRenderer('../templates');

    global $loggedIn;

    $Name="";
    if(isset($_SESSION['name']))
    $Name=$_SESSION['name'];

    $queryBuilder=$this->get('DB')->getQueryBuilder();
    $queryBuilder->select('*')->from('Comments')->innerJoin('Comments','CommentSystem','c','Comments.UserID=c.ID')->where('Comments.Page=?')->setParameter(1,$args['page']);
    $results=$queryBuilder->executeQuery();
    $total=(int)$results->rowCount();
    $results=$results->fetchAll();
    
    

    return $renderer->render($response, "comment.php",array(
        'results' => $results,
        'loggedIn'=> $loggedIn,
        'page'=>    $args['page'],
        'name'=>    $Name,
        'Total_Comments'=>$total,
        ));
});

$app->post('/register',function(Request $request,Response $response)
{
    
    $_input = $request->getParsedBody();

    $_name = $_input['name'];
    $_email = $_input['email'];
    $_password = $_input['password'];

    if (!filter_var($_email, FILTER_VALIDATE_EMAIL))
    {
        $response->getBody()->write("failedEmail");
        return $response->withHeader('Content-Type','application/json');
    }

    $queryBuilder=$this->get('DB')->getQueryBuilder();

    $queryBuilder->select('ID')->from('CommentSystem')->where('Email=?')->setParameter(1,$_email);
    $results=$queryBuilder->executeQuery()->fetchAssociative();
    if($results!=null)
    {
        $response->getBody()->write("failedUserExists");
        return $response->withHeader('Content-Type','application/json');
    }
    

    $queryBuilder->insert('CommentSystem')
    ->setValue('Name','?')
    ->setValue('Email','?')
    ->setValue('Password','?')
    ->setParameter(1,$_name)
    ->setParameter(2,$_email)
    ->setParameter(3,$_password);
    
    $results2=$queryBuilder->executeStatement();



    if($results2==null)
    {
        $response->getBody()->write("NULL");
        return $response->withHeader('Content-Type','application/json');
    }
    else
    {
        $_SESSION['loggedIn'] = 1;
        $_SESSION['name'] = $_name;
        $_SESSION['email'] = $_email;
        exit(json_encode($_SESSION['name']));
    }

});

$app->post('/login',function(Request $request,Response $response){
    $_input = $request->getParsedBody();

    $_email = $_input['email'];
    $_password = $_input['password'];

    if (!filter_var($_email, FILTER_VALIDATE_EMAIL))
    {
        $response->getBody()->write("failed");
        return $response->withHeader('Content-Type','application/json');
    }

    $queryBuilder=$this->get('DB')->getQueryBuilder();

    $queryBuilder->select('ID','Name','Email','Password')->from('CommentSystem')->where('Email=?')->setParameter(1,$_email);
    $results=$queryBuilder->executeQuery()->fetchAssociative();
    if($results==null)
    {
        $response->getBody()->write("failed");
        return $response->withHeader('Content-Type','application/json');
    }

    $xd=$results['Password'];
    if($xd!=$_password)
    {
        $response->getBody()->write("failed");
        return $response->withHeader('Content-Type','application/json');
    }

    $_SESSION['loggedIn'] = 1;
    $_SESSION['name'] = $results['Name'];
    $_SESSION['email'] = $_email;
    $_SESSION['ID']=$results['ID'];

    exit(json_encode($_SESSION['ID']));
});

$app->post('/Edit',function(Request $request,Response $response){
    $_input = $request->getParsedBody();
    $_commentid=$_input['commentid'];
    $_comment=$_input['comment'];
    $queryBuilder=$this->get('DB')->getQueryBuilder();

    $queryBuilder->update('Comments')->set('Comment','?')->where('Comments.CommentID=?')
    ->setParameter(1,$_comment)
    ->setParameter(2,$_commentid);

    $results2=$queryBuilder->executeStatement();

    exit($_input['commentid']);
});

$app->post('/add',function(Request $request,Response $response){
    $_input = $request->getParsedBody();

    $_comment=$_input['comment'];
    $_page=$_input['page'];
    $_userid=1;

    if(isset($_SESSION['ID']))
    $_userid=$_SESSION['ID'];

    $queryBuilder=$this->get('DB')->getQueryBuilder();

    $queryBuilder->insert('Comments')
    ->setValue('Comment','?')
    ->setValue('Page','?')
    ->setValue('UserID','?')
    ->setValue('Likes',0)
    ->setValue('Dislikes',0)
    ->setParameter(1,$_comment)
    ->setParameter(2,$_page)
    ->setParameter(3,$_userid);

    $results2=$queryBuilder->executeStatement();

    exit('success');
});

$app->post('/reply',function(Request $request,Response $response){
    $_input = $request->getParsedBody();

    $_comment=$_input['comment'];
    $_page=$_input['page'];
     
    $_parentid=$_input['comment_id'];
    $_userid=1;
    
    if(isset($_SESSION['ID']))
    $_userid=$_SESSION['ID'];

    $queryBuilder=$this->get('DB')->getQueryBuilder();

    $queryBuilder->insert('Comments')
    ->setValue('Comment','?')
    ->setValue('Page','?')
    ->setValue('UserID','?')
    ->setValue('Likes',0)
    ->setValue('Dislikes',0)
    ->setValue('Parent_ID','?')
    ->setParameter(1,$_comment)
    ->setParameter(2,$_page)
    ->setParameter(3,$_userid)
    ->setParameter(4,$_parentid);

    $results2=$queryBuilder->executeStatement();

    exit('success');
});

$app->post('/logout',function(Request $request,Response $response){
    unset($_SESSION['loggedIn']);
    session_destroy();
    exit('Success');
});

$app->post('/DisLike',function(Request $request,Response $response)
{
    $_input = $request->getParsedBody();
    $_commentid=$_input['commentid'];
    $_dislike=(int)($_input['DisLike']);
    $_dislike=$_dislike+1;

    $queryBuilder=$this->get('DB')->getQueryBuilder();
    $queryBuilder->update('Comments')->set('Dislikes','?')->where('Comments.CommentID=?')
    ->setParameter(1,$_dislike)
    ->setParameter(2,$_commentid);

    $results2=$queryBuilder->executeStatement();

    exit(json_encode($_dislike));
});

$app->post('/Like',function(Request $request,Response $response)
{
    $_input = $request->getParsedBody();
    $_commentid=$_input['commentid'];
    $_like=(int)($_input['Like']);
    $_like=$_like+1;

    $queryBuilder=$this->get('DB')->getQueryBuilder();
    $queryBuilder->update('Comments')->set('Likes','?')->where('Comments.CommentID=?')
    ->setParameter(1,$_like)
    ->setParameter(2,$_commentid);

    $results2=$queryBuilder->executeStatement();

    exit(json_encode($_like));
});

$app->get('/test',function(Request $request,Response $response)
{
    $queryBuilder=$this->get('DB')->getQueryBuilder();
    $queryBuilder->select('ID','Name','Team','Category')->from('Players');
    $results=$queryBuilder->executeQuery()->fetchAll();

    $response->getBody()->write(json_encode($results));
    return $response->withHeader('Content-Type','application/json');
});

$app->get('/test/{id}',function(Request $request,Response $response,array $args)
{
    $queryBuilder=$this->get('DB')->getQueryBuilder();

    $queryBuilder->select('ID','Name','Team','Category')->from('Players')->where('ID = ?')->setParameter(1,$args['id']);

    $results=$queryBuilder->executeQuery()->fetchAssociative();

    $response->getBody()->write(json_encode($results));
    return $response->withHeader('Content-Type','application/json');
});
