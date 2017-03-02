<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cloud9!</title>

        <script type = "text/javascript">

            var name = "";

            var ar = "";

            function getTextString() {

                if (name == ""){
                    name = "<?php echo $textString ?>";
                }
                document.getElementById('myText').value = name;

                if (document.getElementById("myText").value == ""){
                    document.getElementById("myDropdown").style.visibility="hidden";
                }
                else {
                    document.getElementById("myDropdown").style.visibility="visible";
                }
                document.getElementById("myText").focus();
            };

            function countChar(val) {
                var len = document.getElementById('myText').value.length;
                if (len >= 3) {
                    getInputText();
                    getTextString();
                    document.getElementById("myText").focus();
                }
                else {
                    document.getElementById("myDropdown").style.visibility="hidden";
                }
            };


            function getInputText() {

                <?php
                if (count($artistSuggestions)>0){
                    $artistArray = $artistSuggestions;
                    $hasArtistSuggestions = true;
                }
                else {
                    $hasArtistSuggestions = false;
                }
                ?>

                var artistName = document.getElementById("myText").value;
                var name = artistName;
                var baseURL = "http://localhost:8000/api/artist/";
                var url = baseURL.concat(artistName);
                window.location.href = url;
                name = artistName;
                document.getElementById("myText").focus();


            };
            var artistID="";

            function putText(val, val2) {
                document.getElementById("myText").value = val;

                artistID = val2;

            }
            function goToCloud(){
                var baseURL = "http://localhost:8000/api/wordcloud/";
                var url = baseURL.concat(artistID);
                if (artistID!=""){
                window.location.href = url;}
                }


        </script>

<style>
#wrapper {
    width: 100%;
    height: 100%;
    margin: 0 auto;
    text-align: center;

}
#search {
    position: fixed;
    left: 35%;
    top: 50%;

}

#searchCloud {
    position: fixed;
    left: 35%;
    top: 70%;

}

#songList {
    position: fixed;
    left: 40%;

    text-align: left;
}

#lyrics {
    position: fixed;
    left: 20%;
    text-align: center;
    max-width: 700px;
}

body {
    background-color: #c5c8c4;

}

form{
    display:inline-block;

}

input[type = "button"], input[type = "submit"], button {
    background-color: #B345F1;
    height: auto;
    width: 120px;
    font-size: 12px;
    display: inline-block;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border: 1px solid rgba(0,0,0,0.3);
    border-bottom-width: 3px;
}
input[type = "text"] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
pageTitle {
    font-size: 20px;

}
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

/* Dropdown button on hover & focus */
.dropbtn:hover, .dropbtn:focus {
    background-color: #3e8e41;
}

/* The container <div> - needed to position the dropdown content */
#myDropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #f1f1f1}

/* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
.show {display:block;}
</style>

</head>
<body onload = "getTextString()">
    <div id = "search">
        <br>
        <input type="text" name="artist" oninput="countChar(this)" value = "<?php $textstring ?>" size ="50" id="myText">

        <div id="myDropdown" class="dropdown-content" >
            <?php

            if (count($artistSuggestions)>0){
                $artistArray = $artistSuggestions;
                $num = count($artistSuggestions);
                $hasArtistSuggestions = true;

                for ($i = 0; (($i  < $num) && ($i < 3))
                    && $hasArtistSuggestions; $i++){
                    echo "<a href='#' onclick='putText(".json_encode($artistArray[$i]['artistName'], JSON_HEX_TAG).", " .
                            json_encode($artistArray[$i]['artistId'], JSON_HEX_TAG).")'>".
                            $artistArray[$i]['artistName'] ."</a>";
                }
            }

            else {
                $hasArtistSuggestions = false;
            }

            ?>


        </div>
        <br><br>
        <button onclick="goToCloud()">Search</button>
        <br>


    </div>


</body>

