<?php
$DOCUEMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
include_once $DOCUEMENT_ROOT."/php/lib/db/pages/PublicationAuthorView/PublicationAuthorViewHandler.php";
include_once $DOCUEMENT_ROOT."/php/lib/db/pages/PublicationUserViewHandler/publicationUserViewHandler.php";



$id = isCookiesThere();
if(!$id){
    $id = null;
}


$postTitle = null;
$postDescription = null;
$postLanguage = null;
$postMainCategory = null;
$postSubCategory = null;
$postSize=null;
$postPublishedDate= null;
$postLikeCount =null;
$postCommentCount=null;
$postComments=null;
$postPublicationThumbnalFilePath=null;
$postPublicationPdfFilePath=null;
$publicationId = null;
$authorId = null;
$liked = false;


if($_SERVER["REQUEST_METHOD"]=="GET"){
    global $publicationId,$authorId;

    $publicationId = (int)$_GET["PID"];
    $authorId = (int)$_GET["AID"];
    $publicationDetails = getPublication($publicationId,$authorId);

    $postTitle = $publicationDetails["Title"];
    $postDescription = $publicationDetails["Description"];
    $postLanguage = $publicationDetails["Language"];
    $postSize = $publicationDetails["Size"];
    $postPublishedDate = $publicationDetails["PublishedDate"];
    $postMainCategory = $publicationDetails["MainCategory"];
    $postSubCategory = $publicationDetails["SubCategory"];
    $postLikeCount = $publicationDetails["LikeCount"];
    $postCommentCount = $publicationDetails["CommentCount"];
    $postCommentCount = $publicationDetails["CommentCount"];
    $postPublicationThumbnalFilePath = getThumbnailLocation($authorId,$publicationId);
    $postPublicationPdfFilePath = getPdfLocation($authorId,$publicationId);
    $postComments = getComments($publicationId);
    if($id){
        $liked = getLike($id,$publicationId);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<nav class="navBar">
        <script>
            function profileRedirect(){
                window.location.href = "/pages/AuthorProfileView/index.php";
            }
        </script>
        <div class="hamburgerMenu">
            <div id="asideBarActivator">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <img src="/shared/img/navBar/CompanyLogo.png" alt="CompanyLogo" id="Logo" />
        <aside class="links-container">
            <a href="/index.php">Home</a>
            <a href="/pages/Category/index.php" id="category"><span>Category</span><img src="/shared/icon/navBar/arrowHead.png" />
            </a>
            <a href="/pages/Services/index.php">Services</a>
            <a href="/pages/contact us/index.php">contact us</a>
            <a href="/pages/About us/index.php">About us</a>
            <?php
                if(!$id){
                    echo "<a href='/pages/SignUp/index.php' id='SignUpButton'>Sign Up</a>";
                }
                else{
                    echo "<a href='/pages/SignUp/index.php' id='SignUpButton' style='display:none;'>Sign Up</a>";
                }
            ?>
        </aside>
        <?php
            if(!$id){
                echo "<a href='/pages/Login/index.php' id='SignInButton'>Sign In</a>";
            }
            else{
                echo "<img onclick='profileRedirect()' class='profileImage'  src='".getProfilePictureLocation($id)."'></img>";
            }
        ?>
        
    </nav>
    <?php
    if($id){
        echo "<img src='".getProfilePictureLocation($id)."'></img>";
    }
    ?>
        <table>
            <tr>
                <td>Title</td>
                <td><input type="text" name="Title" readonly <?php echo "value = \"$postTitle\""?>></td>
            </tr>
            <tr>
                <td>Thumbnail</td>
                <td><img <?php echo "src= '$postPublicationThumbnalFilePath'" ?>></td>
            </tr>
            <tr>
                <td>Publication</td>
                <td><a <?php echo "href = '$postPublicationPdfFilePath'" ?> download="download.pdf">Download</a></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name="Description" cols="30" rows="10" readonly style="resize: none;"><?php echo "$postDescription"?></textarea></td>
            </tr>
            <tr>
                <td>Language</td>
                <td><input type="text" name="Language" readonly <?php echo "value = '$postLanguage'"?>></td>
            </tr>
            <tr>
                <td>Size</td>
                <td><input type="text" name="Size" readonly <?php echo "value = '$postSize'"?>></td>
            </tr>
            <tr>
                <td>Published Date</td>
                <td><input type="text" name="Size" readonly <?php echo "value = '$postPublishedDate'"?>></td>
            </tr>
            <tr>
                <td>Main Category</td>
                <td><input type="text" name="Size" readonly <?php echo "value = '$postMainCategory'"?>></td>
            </tr>
            <tr>
                <td>Sub Category</td>
                <td><input type="text" name="Size" readonly <?php echo "value = '$postSubCategory'"?>></td>
            </tr>
            <tr>
                <td>Like Count</td>
                <td><input type="text" name="Size" readonly <?php echo "value = '$postLikeCount'"?>></td>
            </tr>
            <tr>
                <td>Comment Count</td>
                <td><input type="text" name="Size" readonly id="commentCount" <?php echo "value = '$postCommentCount'"?>></td>
            </tr>
            <?php
            if($id){
                echo "<tr><td>Like</td><td>";
                    if($liked){
                        echo "<button userId='$id' publicationId='$publicationId' onclick=\"likeHandler(event)\" class='unLiked liked'>Like</button>";
                    }
                    else{
                        echo "<button userId='$id' publicationId='$publicationId' onclick=\"likeHandler(event)\" class='unLiked'>Like</button>";
                    }
                echo "</td></tr>";
                }
            ?>
        </table>
        <?php
        if($id){
        echo "<label for='Comment'>Leave a comment</label>";
            echo "<textarea userId='$id' publicationId='$publicationId' name=\"Comment\" cols=\"30\" rows=\"10\" id=\"commentbox\"></textarea>";
            
        
            echo "<button onclick='comment(event)'>Submit</button><div id='commentViewBox'>";
        
                foreach ($postComments as $comment){
                    echo "<h4>$comment</h4>";
                }
            echo "</div>";
        }
        ?>

<button onclick="goback()">Back</button>
<a href="/pages/Login/index.php">Sing in</a>

<script src="./js/index.js"></script>
</body>
</html>