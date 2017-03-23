<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
    <head>
        <title>Laravel</title>

        <link href="webassets/bootstrap.css" rel="stylesheet" type="text/css">


    </head>
    <body>
    <div class="container" style="width: 60%; margin: auto; margin-top: 40px;">
        <div class="panel panel-primary center-block">
            <div class="panel-heading"></div>
            <div class="panel-body">
                Enter URL : <input type="url" name="url" id="url" onblur="fetchDetails()" style="width:60%">
                <br><br>
                <button onclick="fetchDetails()">Fetch</button>
            </div>

        </div>
        <div style="height:30px"></div>
        <div class="panel panel-primary center-block">
            <div class="panel-heading">Details of the URL</div>
            <div class="panel-body">
                <div>
                    Title : <span id="titleText">N/A</span><br>
                    <br>
                    Image : <br>
                    <img id="imageView" src="" hidden>
                    <br>
                    Description : <span id="descriptionText"></span>
                    <br><br>
                </div>

                <form method="GET" action="{{route('crawler.save')}}">
                    <input type="hidden" id="urlForm" name="url">
                    <input type="hidden" id="titleForm" name="title">
                    <input type="hidden" id="imageForm" name="image">
                    <input type="hidden" id="descriptionForm" name="description">
                    <button id="saveButton" type="submit" disabled>Save in database</button>
                </form>

            </div>

        </div>
    </div>






        <script type="text/javascript" src="webassets/script.js"></script>
        <script type="text/javascript" src="webassets/bootstrap.js"></script>
    </body>
</html>
