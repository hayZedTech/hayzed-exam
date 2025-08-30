<!DOCTYPE html>
    <html>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <head>
    <style>
    body {
      font-family: Arial;
      margin: 0;
    }

    * {
      box-sizing: border-box;
    }

    /* img {
      vertical-align: middle;
    } */

    /* Position the image container (needed to position the left and right arrows) */
    .container {
      position: relative;
      /* height: 400px; */
    }

    /* Hide the images by default */
    .mySlides {
      display: none;
    }

    /* Add a pointer when hovering over the thumbnail images */
    .cursor {
      cursor: pointer;
    }

    /* Next & previous buttons */
    .prev,
    .next {
      cursor: pointer;
      position: absolute;
      top: 44%;
      width: auto;
      padding: 16px;
      margin-top: 50px;
      color: black;
      font-weight: bold;
      font-size: 20px;
      border-radius: 0 3px 3px 0;
      user-select: none;
      -webkit-user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
      right: 0;
      border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
      background-color: rgba(0, 0, 0, 0.8);
      color: white;
      /* padding: 5px 40px; */
    }

    /* Number text (1/3 etc) */
    .numbertext {
      color: #000;
      font-size: 12px;
      padding: 8px 12px;
      position: absolute;
      top: -100px;
    }

    .box{
      margin-top: 130px;
    }

    .mybtn1 button{
      margin: 40px 0 30px 400px;
      padding: 5px 20px;
      border:ridge 4px grey;
      border-radius: 10px;
      cursor: pointer;
      background-color: #555;
      color: white;
    }

    .mybtn1 button:hover{
      border:groove 4px grey;
      background-color: #ccc;
      color: #555;
    }

    .ans li{
      margin-left: -30px;
    }

    /* Container for image text */
    /* .caption-container {
      text-align: center;
      background-color: #222;
      padding: 2px 16px;
      color: white;
    } */

    .myPagesNum{
      /* background-color: #000; */
      color: white;
      margin-top: 50px;
    }


    .myPagesNum:after {
      content: "";
      display: table;
      clear: both;
    }

    .mainPage{
      width: 100%;
      height: 250px;
    }

    .mainPage li{
     list-style-type: none;
    }
    /* Six columns side by side */
    .column {
      float: left;
      width: 16.66%;
    }

    /* Add a transparency effect for thumnbail images */
    .demo {
      opacity: 0.5;
    }

    .demo p{
      background-color: navy;
      text-align: center;
      padding: 10px;
      margin: 4px;
    }

    .active,
    .demo:hover {
      opacity: 1;
      color: white;
    }

    #disp1, #disp2, #disp3, #disp4, #disp5, #disp6, #disp7, #disp8, #disp9, #disp10{
      margin-left: 30px;
      font-weight: bold;
      background-color: #eee;
      border-radius: 10px;
    }

    .ch_res1{
      margin-top: -10px;
      margin-left: 85% ;
      padding: 12px 15px;
      background-color: blue;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }

    .topbar{
        width: 100%;
        height: 50px;
        background-color: navy;
        color: white;
        display: flex;
        align-items: center;
        padding-left: 30px;
        padding-right: 30px;
        font-size: 20px;
    }

    .exit{
        position: absolute;
        margin:20px 0 0 100px !important;
        text-decoration: none;
        border: none;
        background-color: red;
        padding: 11px 22px;
        border-radius: 10px;
        color: white;
      }

    @media screen and (max-width:480px) {
        .mybtn1{
          position: fixed;
          top: 300px;
            margin-top: 0px;
            margin-left: 0% !important;
        }
        .mybtn1 button{
      margin: 40px 0 20px 20px;
      padding: 5px 20px;
      border:ridge 4px grey;
      border-radius: 10px;
      cursor: pointer;
      background-color: #555;
      color: white;
    }

    .prev_next{
        top: 70px !important;
    }

    .ch_res1{
      margin-top: 25px !important;
      margin-left: 65% !important;
    }

    .exit{
        margin:25px 0 0 20px !important;
      }


    }

    
    </style>
    </head>