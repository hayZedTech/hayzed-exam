<?php include_once "styles.php" ?>


<nav class="navbar navbar-expand-sm bg-primary navbar-dark sticky-top mb-3">
  <div class="container-fluid">
    <a class="navbar-brand time" href="#"
    id="time01"> <span style="color: white;"><b>Time left: </b></span> <span id="time"></span>    
    </a>

    <input type="submit" value="Submit" name="accept" id="submitBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop_submit" class="button btn1">
  </div>
</nav>






<body id="body1">
    <div class="page_contents">
    <h2>Name: <span class="text-primary"><?php echo $_SESSION['name'] ?></span></h2>
    <h3>Exam Number: <span class="text-primary"><?php echo $_SESSION['stid'] ?></span></h3>
    <h3>Subject: <span class="text-primary">Geography</span></h3>
    <!-- <form action="answers.php" method="post">
    <button class="btn1" name="submit">Submit</button>
    </form> -->
        <hr>
    <h2>Questions</h2>
    <!-- <input type="submit" value="Submit" name="accept" id="submitBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop_submit" class="button btn1"> -->
    
    <form action="answers.php" method="post">
    <!-- <button type="button" class="btn1 px-4 mx-2" id="submitBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop_submit">
          Submit
        </button> -->
        

        <!-- <a href="submit.php" class="btn btn-primary text-white btn2"><b>Check result</b></a> -->
   

     <!--Submit Modal -->
     <div class="modal fade" id="staticBackdrop_submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="text-success ">Submit your exam</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <button class="btn btn-success px-3"  name="geo">Submit</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end of modal -->

    
    <div id="disp">
    <ul class="quest">
        <!-- Q1 -->
        <li>
        Which of the following criteria is used to classify settlements into rural or urban?
            <ul class="ans">
                <li> <input type="radio" name="one" id="" value="0" required> age</li>
                <li><input type="radio" name="one" id="" value="1" required> function</li>
                <li><input type="radio" name="one" id="" value="0" required> site</li>
                <li><input type="radio" name="one" id="" value="0" required> location</li>
            </ul>
           
        </li>
    
        
        <br>
        <!-- Q2 -->
        <li>
        The broad-leafed evergreen and dense equatorial forest of the Amazon basin is called
            <ul class="ans">
                <li> <input type="radio" name="two" id="" value="0" required> blanos</li>
                <li><input type="radio" name="two" id="" value="0" required> campos</li>
                <li><input type="radio" name="two" id="" value="1" required> selvas</li>
                <li><input type="radio" name="two" id="" value="0" required> pampas</li>
            </ul>
            
        </li>


        <br>
        <!-- Q3 -->
        <li>
        River regime is best described as the
            <ul class="ans">
                <li><input type="radio" name="three" id="" value="0" required> seasonal variation in the volume of water in a river</li>
                <li><input type="radio" name="three" id="" value="0" required> period a river overflows its bank</li>
                <li><input type="radio" name="three" id="" value="0" required> amount of load a river carries</li>
                <li><input type="radio" name="three" id="" value="1" required> rate at which a river flows
                </li>
            </ul>
        <!-- Correct Answer: Option 3A -->
        </li>


        <br>
        <!-- Q4 -->
        <li>
        Nigeria major export commodity is
            <ul class="ans">
                <li><input type="radio" name="four" id="" value="0" required> cocoa</li>
                <li><input type="radio" name="four" id="" value="1" required> petroleum</li>
                <li><input type="radio" name="four" id="" value="0" required> coal</li>
                <li><input type="radio" name="four" id="" value="0" required> cotton</li>
            </ul>
           
        </li>


        <br>
        <!-- Q5 -->
        <li>
        Which of the following has the highest salinity?
            <ul class="ans">
                <li><input type="radio" name="five" id="" value="1" required> Dead sea</li>
                <li><input type="radio" name="five" id="" value="0" required> Mediterranean sea</li>
                <li><input type="radio" name="five" id="" value="0" required> Red sea</li>
                <li><input type="radio" name="five" id="" value="0" required> South Atlantic ocean
                </li>
            </ul>
        
        </li>


        <br>
        <!-- Q6 -->
        <li>
        A settlement where two rivers meet is called a
            <ul class="ans">
                <li><input type="radio" name="six" id="" value="0" required> nodal town</li>
                <li><input type="radio" name="six" id="" value="0" required> linear settlement</li>
                <li><input type="radio" name="six" id="" value="0" required> nucleated settlement</li>
                <li><input type="radio" name="six" id="" value="1" required> confluence town</li>
            </ul>
       
        </li>


        <br>
        <!-- Q7 -->
        <li>
        Plateaux formed together with fold mountains are best described as
            <ul class="ans">
                <li><input type="radio" name="seven" id="" value="0" required> dissected</li>
                <li><input type="radio" name="seven" id="" value="0" required> tactonic</li>
                <li><input type="radio" name="seven" id="" value="1" required> intermoutau</li>
                <li><input type="radio" name="seven" id="" value="0" required> piedmout</li>
            </ul>
       
        </li>


        <br>
        <!-- Q8 -->
        <li>
        Which of the following rocks is organically formed?
            <ul class="ans">
                <li><input type="radio" name="eight" id="" value="0" required> coal</li>
                <li><input type="radio" name="eight" id="" value="0" required> grit</li>
                <li><input type="radio" name="eight" id="" value="1" required> shale</li>
                <li><input type="radio" name="eight" id="" value="0" required> gypsum</li>
            </ul>
        
        </li>


        <br>
        <!-- Q9 -->
        <li>
        Kossou Dam is located on river
            <ul class="ans">
                <li><input type="radio" name="nine" id="" value="0" required> nile</li>
                <li><input type="radio" name="nine" id="" value="0" required> volta</li>
                <li><input type="radio" name="nine" id="" value="1" required> badama</li>
                <li><input type="radio" name="nine" id="" value="0" required> cunene</li>
            </ul>
        
        </li>


        <br>
        <!-- Q10 -->
        <li>
        A major function performed by rural settlement is
            <ul class="ans">
                <li><input type="radio" name="ten" id="" value="0" required> commercial</li>
                <li><input type="radio" name="ten" id="" value="0" required> industrial</li>
                <li><input type="radio" name="ten" id="" value="0" required> administrative</li>
                <li><input type="radio" name="ten" id="" value="1" required> agriculture</li>
            </ul>
        
        </li>

    </ul>
    </div>
    </form>
</body>
<?php include_once "footer.php" ?>
