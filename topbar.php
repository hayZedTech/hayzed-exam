

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>
      nav ul{
        margin-left: 20px;
      }
    </style>
    
</head>
<body class="subject_body">
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="d-flex my-3 list-unstyled">
      <li >
      <a  class="btn btn-info text-white px-4 py-2" href="subjects.php"><b>Subject</b></a>
      </li>
        <li class="mx-3">
          <a class=" btn bg-white text-black px-4 py-2 result  float-left" href="result.php"><b>Result</b></a>
        </li>
        <li >
          <a class=" btn btn-danger px-4 py-2 logout  float-left" href="index.php"><b>Logout</b></a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<script src="bootstrap.bundle.min.js"></script>
<script src="jQuery.js"></script>