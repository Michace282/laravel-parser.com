<!DOCTYPE html>
<html>
<head>
    <title>Laravel Parser Page</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
<div class="container">
    <div class="title m-b-md">
        Laravel Parser Page
    </div>
    <form id="parserform" method="POST" action="{{ route('parser-send') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label>URL 1:</label>
            <input type="text" name="url_1" class="form-control" placeholder="Введите Url 1" required="required">
        </div>

        <div class="form-group">
            <label>URL 2:</label>
            <input type="text" name="url_2" class="form-control" placeholder="Введите Url 2" required="required">
        </div>
        <div class="form-group">
            <button class="btn btn-success save-data">ОТПРАВИТЬ</button>
        </div>
    </form>
    <?php
       if (isset($QAV))
           foreach ($QAV as $answer) {
               ?>
               <p><?=$answer['question']?></p>
               <video tabindex="-1" preload="" autoplay="autoplay" src="<?=$answer['video_url']?>" loop="loop" style="object-fit: fill;" controls></video>
               <?php
           }
    ?>
</div>
</body>
</html>