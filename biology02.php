<?php
include_once "header02.php";
?>

<body style="background: url(Images/BLUE_SKY.png); background-size:cover;">
    <div class="topbar">
        <marquee behavior="" direction="">HayZed Tech</marquee>
    </div>
    <div>
      <a href="index.php" style="float: right; background-color:green; margin:5px 20px; color:white; padding:10px 15px; border-radius:10px; text-decoration:none;">Home</a>
    </div>

    <div class="container">
        <div class="mainPage">

            <form id="quizForm" action="" method="post">
                <?php
                $questions = [
                    1 => ["question" => "The modification in structure, physiology and behaviour of plant and animal is called ______",
                          "options" => ["adaptation" => 1, "evolution" => 0, "variation" => 0, "succession" => 0]],
                    2 => ["question" => "A bacteria that is spherically shaped is called?",
                          "options" => ["diplobacillus" => 0, "coccus" => 1, "bacillus" => 0, "vibrio" => 0]],
                    3 => ["question" => "The flame cells are used for excretion in",
                          "options" => ["Fluke" => 1, "Nematode" => 0, "Bacteria" => 0, "Volvox" => 0]],
                    4 => ["question" => "Which of the following is an example of a microorganism in action as a disease vector?",
                          "options" => ["Fungi decomposing dead plant material" => 0, "Mosquito transmitting malaria" => 1, "Bacteria causing food poisoning" => 0, "Algae producing oxygen through photosynthesis" => 0]],
                    5 => ["question" => "Which of the following is a characteristic of cells related to irritability?",
                          "options" => ["Ability to respond to stimuli" => 1, "Ability to synthesize proteins" => 0, "Ability to generate energy" => 0, "Ability to replicate DNA" => 0]],
                    6 => ["question" => "The membrane around the vacuole is known as",
                          "options" => ["Elaioplast" => 0, "Amyloplast" => 0, "Tonoplast" => 1, "Cytoplast" => 0]],
                    7 => ["question" => "Which of the following is NOT a part of the alimentary canal?",
                          "options" => ["Oesophagus" => 0, "Large intestine" => 0, "Liver" => 1, "Small intestine" => 0]],
                    8 => ["question" => "Which of the following is a characteristic feature of Kingdom Plantae?",
                          "options" => ["Presence of chloroplasts" => 1, "Ability to perform photosynthesis" => 0, "Lack of cell walls" => 0, "Heterotrophic mode of nutrition" => 0]],
                    9 => ["question" => "A biome characterized by hot summer, warm winter and treeless vegetation is",
                          "options" => ["Steppe grasslands" => 1, "Temperate deserts" => 0, "Savannah grassland" => 0, "Tropical deserts" => 0]],
                    10 => ["question" => "Which of the following is an example of physiological variation in organisms?",
                          "options" => ["Variation in blood pressure among individuals" => 1, "Variation in beak shape among finches" => 0, "Differences in fur color in rabbits" => 0, "Variation in leaf shape in plants" => 0]],
                ];

                foreach($questions as $num => $q) {
                    echo '<div class="mySlides">';
                    echo '<div class="numbertext"><h1>' . $num . ' / 10</h1></div>';
                    echo '<div class="box"><ul><li>' . $q['question'] . '<ul class="ans">';
                    $optIndex = 1;
                    foreach($q['options'] as $text => $val) {
                        $inputId = "q{$num}_opt{$optIndex}";
                        echo '<li class="option" data-val="'.$val.'"><input type="radio" name="q' . $num . '" id="' . $inputId . '" value="' . $val . '">';
                        echo '<label for="' . $inputId . '"> ' . $text . '</label></li>';
                        $optIndex++;
                    }
                    echo '</ul></li></ul>';
                    echo '<div class="feedback" id="feedback'.$num.'"></div>'; // Feedback div
                    echo '</div></div>';
                }
                ?>
                <br>
                <button type="button" id="ch_res" class="ch_res1">Check Result</button>
                <div id="totalScore" style="margin-top:20px; font-weight:bold;"></div>
            </form>

        </div>

        <div class="footterr">
            <?php include_once "footer02.php"; ?>
        </div>
    </div>
  

</body>

<script src="script/jQuery.js"></script>
<script>
$(document).ready(function(){

    // Show feedback on selecting an answer
    $("input[type=radio]").change(function(){
        let qName = $(this).attr("name");
        let selectedVal = Number($(this).val());
        let feedbackDiv = $("#feedback"+qName.replace('q',''));
        
        // Reset option colors
        $("input[name='"+qName+"']").closest(".option").css("color","black");

        if(selectedVal === 1){
            $(this).closest(".option").css("color","green");
            feedbackDiv.text("✅ Correct!").css("color","green");
        } else {
            $(this).closest(".option").css("color","red");
            // Highlight correct answer
            $("input[name='"+qName+"'][value='1']").closest(".option").css("color","green");
            feedbackDiv.text("❌ Wrong!").css("color","red");
        }
    });

    $("#ch_res").click(function(){
        let total = 0;
        let allAnswered = true;

        for(let i=1; i<=10; i++){
            let selected = $("input[name='q"+i+"']:checked");
            if(selected.length === 0){
                allAnswered = false;
                break;
            }
            total += Number(selected.val());
        }

        if(!allAnswered){
            alert("Please complete all the questions!");
            return;
        }

        $("#totalScore").text("✅ Your Total score is: " + total + "/10");
    });

});
</script>
