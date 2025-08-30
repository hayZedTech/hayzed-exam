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
    <h3>Subject: <span class="text-primary">Biology</span></h3>
    
    

    
    <!-- <form action="answers.php" method="post">
    <button class="btn1" name="submit">Submit</button>
    </form> -->
        <hr>
    <h2>Questions</h2>
    
    
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
                    <button class="btn btn-success px-3"  name="bio">Submit</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end of modal -->

    
    <div id="disp">
    <ul class="quest">
        <!-- Q1 -->
        <li>
        The modification in structure, physiology and behaviour of plant and animal is called ______
            <ul class="ans">
                <li> <input type="radio" name="one" id="" value="1" required> adaptation</li>
                <li><input type="radio" name="one" id="" value="0" required> evolution</li>
                <li><input type="radio" name="one" id="" value="0" required> variation</li>
                <li><input type="radio" name="one" id="" value="0" required> succession</li>
            </ul>
          
        </li>
    
        
        <br>
        <!-- Q2 -->
        <li>
        A bacteria that is spherically shaped is called?
            <ul class="ans">
                <li> <input type="radio" name="two" id="" value="0" required> diplobacillus</li>
                <li><input type="radio" name="two" id="" value="1" required> coccus</li>
                <li><input type="radio" name="two" id="" value="0" required> bacillus</li>
                <li><input type="radio" name="two" id="" value="0" required> vibrio</li>
            </ul>
            
        </li>


        <br>
        <!-- Q3 -->
        <li>
        The flame cells are used for excretion in
            <ul class="ans">
                <li><input type="radio" name="three" id="" value="1" required> Fluke</li>
                <li><input type="radio" name="three" id="" value="0" required> Nematode</li>
                <li><input type="radio" name="three" id="" value="0" required> Bacteria</li>
                <li><input type="radio" name="three" id="" value="0" required> Volvox</li>
            </ul>
        
        </li>


        <br>
        <!-- Q4 -->
        <li>
        Which of the following is an example of a microorganism in action as a disease vector?
            <ul class="ans">
                <li><input type="radio" name="four" id="" value="0" required> Fungi decomposing dead plant material</li>
                <li><input type="radio" name="four" id="" value="1" required> Mosquito transmitting malaria</li>
                <li><input type="radio" name="four" id="" value="0" required> Bacteria causing food poisoning</li>
                <li><input type="radio" name="four" id="" value="0" required> Algae producing oxygen through photosynthesis</li>
            </ul>
           
        </li>


        <br>
        <!-- Q5 -->
        <li>
        Which of the following is a characteristic of cells related to irritability?
            <ul class="ans">
                <li><input type="radio" name="five" id="" value="1" required> Ability to respond to stimuli</li>
                <li><input type="radio" name="five" id="" value="0" required> Ability to synthesize proteins</li>
                <li><input type="radio" name="five" id="" value="0" required> Ability to generate energy</li>
                <li><input type="radio" name="five" id="" value="0" required> Ability to replicate DNA</li>
            </ul>
           
        </li>


        <br>
        <!-- Q6 -->
        <li>
        The membrane around the vacuole is known as
            <ul class="ans">
                <li><input type="radio" name="six" id="" value="0" required> Elaioplast</li>
                <li><input type="radio" name="six" id="" value="0" required> Amyloplast</li>
                <li><input type="radio" name="six" id="" value="1" required> Tonoplast</li>
                <li><input type="radio" name="six" id="" value="0" required> Cytoplast</li>
            </ul>
        
        </li>


        <br>
        <!-- Q7 -->
        <li>
        Which of the following is NOT a part of the alimentary canal?
            <ul class="ans">
                <li><input type="radio" name="seven" id="" value="0" required> Oesophagus</li>
                <li><input type="radio" name="seven" id="" value="0" required> Large intestine</li>
                <li><input type="radio" name="seven" id="" value="1" required> Liver</li>
                <li><input type="radio" name="seven" id="" value="0" required> Small intestine</li>
            </ul>
       
        </li>


        <br>
        <!-- Q8 -->
        <li>
        Which of the following is a characteristic feature of Kingdom Plantae?
            <ul class="ans">
                <li><input type="radio" name="eight" id="" value="1" required> Presence of chloroplasts</li>
                <li><input type="radio" name="eight" id="" value="0" required> Ability to perform photosynthesis</li>
                <li><input type="radio" name="eight" id="" value="0" required> Lack of cell walls</li>
                <li><input type="radio" name="eight" id="" value="0" required> Heterotrophic mode of nutrition</li>
            </ul>
        
        </li>


        <br>
        <!-- Q9 -->
        <li>
        A biome characterized by hot summer, warm winter and treeless vegetation is
            <ul class="ans">
                <li><input type="radio" name="nine" id="" value="1" required> Steppe grasslands</li>
                <li><input type="radio" name="nine" id="" value="0" required> Temperate deserts</li>
                <li><input type="radio" name="nine" id="" value="0" required> Savannah grassland</li>
                <li><input type="radio" name="nine" id="" value="0" required> Tropical deserts</li>
            </ul>
       
        </li>


        <br>
        <!-- Q10 -->
        <li>
        Which of the following is an example of physiological variation in organisms?
            <ul class="ans">
                <li><input type="radio" name="ten" id="" value="1" required> Variation in blood pressure among individuals</li>
                <li><input type="radio" name="ten" id="" value="0" required> Variation in beak shape among finches</li>
                <li><input type="radio" name="ten" id="" value="0" required> Differences in fur color in rabbits</li>
                <li><input type="radio" name="ten" id="" value="0" required> Variation in leaf shape in plants</li>
            </ul>
        
        </li>

    </ul>
    </div>
    </form>
    </div>
</body>
<?php include_once "footer.php" ?>

 
