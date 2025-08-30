<?php include_once "db.php"; ?>



<!DOCTYPE html>
<html lang="en">
<head id="dHead">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>

        body{
            font-family: 'Times New Roman', Times, serif !important;
            font-size: 20px;
        }

      
        .page_contents{
            padding: 5px 13px;
        }

        .btn1{
            padding: 5px 15px;
            background-color: rgb(0, 150, 0);
            color: white;
            font-size: 18px;
            border: transparent;
            border-radius: 10px;
            /* margin-left: 30%; */
            cursor: pointer;
            font-weight: bold;
        }

        /* .btn2{
            position: fixed;
            top: 120px;
            padding: 5px 25px;
            right: 120px;
            cursor: pointer;
            margin-left: 70%;
        } */
        /* .quest{margin-bottom: 15px;} */
        .quest li{
            list-style-type: decimal;
            margin-top: 2px;
        }

        /* .ans{margin-top: 5px;} */
        .ans li{
            list-style-type: upper-latin;
            text-transform:inherit;
        }


        /* Subject */
        .subject_body{
            background: url(Images/wavy_background.jpg) no-repeat fixed;
            background-size: cover;
        }

        .subjects input{
            width: 20px;
        }

     

    </style>
</head>