function fetchDetails() {
    var url = document.getElementById("url").value;
    console.log(url);

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {

                var resultjson = JSON.parse(xmlhttp.responseText);

                if(resultjson['success']=true) {
                    document.getElementById("titleText").innerHTML = resultjson['title'];
                    document.getElementById("imageView").getAttribute("src").value = resultjson['image'];
                    document.getElementById("descriptionText").innerHTML = resultjson['description'];


                    document.getElementById("urlForm").innerHTML = url;
                    document.getElementById("titleForm").innerHTML = resultjson['title'];
                    document.getElementById("imageForm").getAttribute("src").value = resultjson['image'];
                    document.getElementById("imageForm").removeAttribute('hidden');
                    document.getElementById("descriptionForm").innerHTML = resultjson['description'];
                    document.getElementById("saveButton").removeAttribute('disabled');
                }


            }
        }
    };
    xmlhttp.open("GET", "/fetch?url="+url, true);
    xmlhttp.send();
}

