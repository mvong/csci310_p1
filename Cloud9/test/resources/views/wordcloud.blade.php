<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cloud9!</title>
        <script type = "text/javascript">
	  function loadCloud(){
            var wordCloudString =  "<?php echo $wordCloudString?>";
	    document.getElementById("wordcloud").innerHTML= wordCloudString;
          }
        </script>

        <script type="text/javascript" src = "../js/html2canvas.js"></script>

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

#squishit {
    position: fixed;
    left: 35%;
    top: 10%;
    max-width: 600px;
}

#lyrics {
    position: fixed;
    left: 20%;
    text-align: center;

}

a{
  text-decoration: none;
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
    width: 300px;
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
</style>
</head>
<body onload = "loadCloud()">

<div id = "squishit">
    <p id="wordcloud"> </p>
</div>

    <div id = "searchCloud">
      <form action="/word_cloud.php">
          <br>
          <input type="text" name="artist" size ="50">
          <br>
          <br>
	  <input type="submit" value="Share" style="button">

	  <input type="submit" value="Search" style="button">
      </form>

</div>
</body>
