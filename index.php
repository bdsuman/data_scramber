<?php
session_start();
class Data
{

    public static $key='abcdefghijklmnopqrstuvwxy~!@#$%^&*()_+?><:;0123456789';
   
    public static function encode($originalData,$key){

                $originalKey= self::$key;
                $data = '';
                $lenth = strlen($originalData);
                for($i=0;$i<$lenth;$i++){
                    $currentChar = $originalData[$i];
                    $position = strpos($originalKey,$currentChar);
                    if($position!==false){
                        $data .= $key[$position];
                    }else{
                        $data .= $currentChar;
                    }
                }
                return $data;
    }
    public static function decode($originalData,$key){

                $originalKey= self::$key;
                $data = '';
                $lenth = strlen($originalData);
                for($i=0;$i<$lenth;$i++){
                    $currentChar = $originalData[$i];
                    $position = strpos($key,$currentChar);
                    if($position!==false){
                        $data .= $originalKey[$position];
                    }else{
                        $data .= $currentChar;
                    }
                }
                return $data;
    }

    public static function keyGenarate(){
                $originalKey= str_split(self::$key);
                shuffle($originalKey);
                $_SESSION['key'] = join('',$originalKey);     
    }
    
    public static function setActive($setLink,$getLink='encode'){
            if($setLink==$getLink){
                return 'active';
            }else{
                return '';
            }
    }
}

if(isset($_GET['reset'])){
    session_destroy();
    session_start();
    $_SESSION['key'] = Data::$key;
}

$type = (!empty($_GET['type']) || isset($_GET['type']))?$_GET['type']:'encode';
$result_msg ='';
if('key'==$type){
    Data::keyGenarate();
    $_SESSION['msg'] ='';
    $_SESSION['result'] = '';
}
if (isset($_POST['submit'])) {
    $key = $_POST['key'];
    $message = $_POST['message'];
    if (!empty($key) && !empty($message)) {
        $_SESSION['msg'] = $message;
        if('encode'==$type){
            $_SESSION['result'] = Data::encode($message,$key);
            $success_encode_msg = ' Successfully Encoded.';
        }

        if('decode'==$type){
            $_SESSION['result'] = Data::decode($message,$key);
            $success_msg = ' Successfully Decoded.';
        }
       
    } else {
        $error_msg = ' Key & Message field is required.';
    }
}
$msg = (isset($_SESSION['msg']) && !empty($_SESSION['msg']))?$_SESSION['msg']:'';
$result = (isset($_SESSION['result']) && !empty($_SESSION['result']))?$_SESSION['result']:'';

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>.::Data Scramber::.</title>
</head>

<body>
    <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Data Scramber</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo Data::setActive('encode',$type); ?>" aria-current="page" href="index.php?type=encode">Encode</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo Data::setActive('decode',$type); ?>" href="index.php?type=decode">Decode</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo Data::setActive('key',$type); ?>" href="index.php?type=key">Genarate Key</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
        <div class="card mt-5">
            <div class="card-header">
                Data Scramber Project </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <h5 class="card-title" style="border-bottom: 1px solid #bfbfbf;">Task Active: <span style="color:greenyellow; font-weight:bold;"><?php echo ucwords($type);?></h5>
                        <?php
                        // $error ='ok';
                        if (isset($error_msg)) {
                            echo <<<alert_error
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Caution!!</strong> {$error_msg}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                alert_error;
                        }

                        if (isset($success_msg)) {
                            echo <<<alert_succes
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Congratulations!!</strong> {$success_msg}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    alert_succes;
                        }
                        ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="key" class="form-label">Private Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="key" id="key" value="<?php echo $_SESSION['key']; ?>" placeholder="Write your private key here.">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" placeholder="Write your message here."><?php echo $msg; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="result" class="form-label">Result</label>
                                <textarea class="form-control" id="result" name="result" placeholder="Write your message here."><?php echo $result;?></textarea>
                            </div>
                            <div align="center">
                                <a href="?reset=true" class="btn btn-warning">RESET</a>
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="card-footer text-muted">
                Project by: Suman Sen  Email: <a href="mailto:mesuman@yahoo.com">mesuman@yahoo.com</a> &amp; GitHub: <a href="https://github.com/bdsuman">bdsuman</a>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>