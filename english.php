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
    <h3>Subject: <span class="text-primary">English Language</span></h3>
   
    

    
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
                    <button class="btn btn-success px-3"  name="eng">Submit</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end of modal -->

    
    <div id="disp">
    <ul class="quest">
        <!-- Q1 -->
        <li>
        Choose the word that has the same rhyme scheme as the following word: <br>battle
            <ul class="ans">
                <li> <input type="radio" name="one" id="" value="0" required> raffle</li>
                <li><input type="radio" name="one" id="" value="0" required> latter</li>
                <li><input type="radio" name="one" id="" value="0" required> bitter</li>
                <li><input type="radio" name="one" id="" value="1" required> kettle</li>
            </ul>
            
        </li>
    
        
        <br>
        <!-- Q2 -->
        <li>
        Dr. Samuel Johnson is also known as_____
            <ul class="ans">
                <li> <input type="radio" name="two" id="" value="0" required> SamJson</li>
                <li><input type="radio" name="two" id="" value="1" required> Samjohn</li>
                <li><input type="radio" name="two" id="" value="0" required> all of the above</li>
                <li><input type="radio" name="two" id="" value="0" required> Johnsam</li>
            </ul>
            
        </li>


        <br>
        <!-- Q3 -->
        <li>
        Choose the word that has the same CONSONANT SOUND as the one represented by the letter(s) underlined.
        <br>
        <span>wa<b class="text-decoration-underline">sh</b></span>
            <ul class="ans">
                <li> <input type="radio" name="three" id="" value="0" required> treasure</li>
                <li><input type="radio" name="three" id="" value="0" required> occasion</li>
                <li><input type="radio" name="three" id="" value="1" required> nation</li>
                <li><input type="radio" name="three" id="" value="0" required> equation</li>
            </ul>
        
        </li>


        <br>
        <!-- Q4 -->
        <li>
        
    In this question, select the option that best explains the information conveyed in the sentence.
    <br>
    <b>If he were here, it could be more fun.</b>
            <ul class="ans">
                <li> <input type="radio" name="four" id="" value="1" required> He did not show up and so the occasion lacked much fun</li>
                <li><input type="radio" name="four" id="" value="0" required> He was expected but did not show up to liven up the occasion</li>
                <li><input type="radio" name="four" id="" value="0" required> There was no fun because he was not present</li>
                <li><input type="radio" name="four" id="" value="0" required> He was being expected to supply more fun</li>
            </ul>
            
        </li>


        <br>
        <!-- Q5 -->
        <li>
        Fill in the gap in the sentence below with the appropriate option: <br>
        Peter and Jane love each other ...... the difficulties they face.
            <ul class="ans">
                <li> <input type="radio" name="five" id="" value="0" required> therefore</li>
                <li><input type="radio" name="five" id="" value="1" required> despite</li>
                <li><input type="radio" name="five" id="" value="0" required> however</li>
                <li><input type="radio" name="five" id="" value="0" required> besides</li>
            </ul>
            
        </li>


        <br>
        <!-- Q6 -->
        <li>
        Choose the word that has the same rhyme scheme as the word below:  <br>Day
            <ul class="ans">
                <li> <input type="radio" name="six" id="" value="0" required> Dare</li>
                <li><input type="radio" name="six" id="" value="1" required> Anime</li>
                <li><input type="radio" name="six" id="" value="0" required> Flight</li>
                <li><input type="radio" name="six" id="" value="0" required> Ball</li>
            </ul>
        
        </li>


        <br>
        <!-- Q7 -->
        <li>
        In this question, fill each gap with the most appropriate option from the list provided.
            <br>
        Without our relentless campaigns, we might ...... sure of victory in the race for the National Assembly.
            <ul class="ans">
                <li> <input type="radio" name="seven" id="" value="0" required> be never</li>
                <li><input type="radio" name="seven" id="" value="1" required> never have been</li>
                <li><input type="radio" name="seven" id="" value="0" required> never had been</li>
                <li><input type="radio" name="seven" id="" value="0" required> have never be</li>
            </ul>
        
        </li>


        <br>
        <!-- Q8 -->
        <li>
        Pick out the option with a different rhyme
            <ul class="ans">
                <li> <input type="radio" name="eight" id="" value="0" required> Roar</li>
                <li><input type="radio" name="eight" id="" value="0" required> Four</li>
                <li><input type="radio" name="eight" id="" value="1" required> Growl</li>
                <li><input type="radio" name="eight" id="" value="0" required> Bore</li>
            </ul>
        
        </li>


        <br>
        <!-- Q9 -->
        <li>
        The primary stress is indicated by writing the syllable on which it occurs in capital letters. From the words lettered A to D choose the one that has the correct stress. <br>
        category
            <ul class="ans">
                <li> <input type="radio" name="nine" id="" value="0" required> cat-e-GO-ry</li>
                <li><input type="radio" name="nine" id="" value="1" required> CAT-e-go-ry</li>
                <li><input type="radio" name="nine" id="" value="0" required> cat-e-go-RY</li>
                <li><input type="radio" name="nine" id="" value="0" required> cat-E-go-ry</li>
            </ul>
        
        </li>


        <br>
        <!-- Q10 -->
        <li>
        Choose the word that does not have the same vowel sound as the others
            <ul class="ans">
                <li> <input type="radio" name="ten" id="" value="0" required> shun</li>
                <li><input type="radio" name="ten" id="" value="0" required> son</li>
                <li><input type="radio" name="ten" id="" value="0" required> sun</li>
                <li><input type="radio" name="ten" id="" value="1" required> short</li>
            </ul>
        
        </li>

    </ul>
    </div>
    </form>
</body>
<?php include_once "footer.php" ?>
