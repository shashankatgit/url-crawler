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
                Enter URL : <input type="url" name="url" id="url"  style="width:60%">
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
                    <br><br>
                    Image : <br><br>
                    <img id="imageView" src="" hidden>
                    <br><br>
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






        <script type="text/javascript">
            function fetchDetails() {
                var url = document.getElementById("url").value;
                console.log(url);

                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                        if (xmlhttp.status == 200) {

                            var resultjson = JSON.parse(xmlhttp.responseText);

                            if(resultjson['image']==null)
                                    resultjson['image']='/res/na.png';

                            if(resultjson['description']==null)
                                    resultjson['description']='Not available';

                            if(resultjson['title']==null)
                                    resultjson['title']='Not available';

                            if(resultjson['success']=true) {
                                document.getElementById("titleText").innerHTML = resultjson['title'];

                                document.getElementById("imageView").setAttribute("src", resultjson['image']!=null?
                                        resultjson['image']:'/res/na.png');
                                document.getElementById("imageView").removeAttribute('hidden');

                                document.getElementById("descriptionText").innerHTML = resultjson['description'];


                                document.getElementById("urlForm").value = url;
                                document.getElementById("titleForm").value = resultjson['image'];
                                document.getElementById("imageForm").value = '/res/na.png';

                                document.getElementById("descriptionForm").value = resultjson['description'];
                                document.getElementById("saveButton").removeAttribute('disabled');
                            }


                        }
                    }
                };
                xmlhttp.open("GET","{{route('crawler.fetch')}}?url=" + url, true);
                xmlhttp.send();
            }


        </script>
        <script type="text/javascript" src="webassets/bootstrap.js"></script>
    </body>
</html>
