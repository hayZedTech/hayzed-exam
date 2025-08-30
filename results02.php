<script src="script/jQuery.js"></script>
<script>
$(document).ready(function(){

   // let all_inp = $("input:checked").val();
   // while(all_inp){
   //    alert ("Ok");
   // }

   // $("#res").click(function(){
   //    let  one = $("#inp1[name=one]:checked").val();
   //    let  two = $("#inp2[name=two]:checked").val();
   //    let  three = $("#inp3[name=three]:checked").val();
   //    let  four = $("#inp4[name=four]:checked").val();
   //    let  five = $("#inp5[name=five]:checked").val();
   //    let  six = $("#inp6[name=six]:checked").val();
   //    let  seven = $("#inp7[name=seven]:checked").val();
   //    let  eight = $("#inp8[name=eight]:checked").val();
   //    let  nine = $("#inp9[name=nine]:checked").val();
   //    let  ten = $("#inp10[name=ten]:checked").val();
   //    $.post("bio_answers.php", {bio_one:one, bio_two:two, bio_three:three, bio_four:four, bio_five:five, bio_six:six, bio_seven:seven, bio_eight:eight, bio_nine:nine, bio_ten:ten}, function(data, status){
   //    });
   // })


      $("#btn1").click(function(){
         let  one = $("#inp1[name=one]:checked").val();
     if(one){  
      if(one == 1){
        $("#disp1").html("Correct!!!");
        $("#disp1").css({'color':'green', 'padding': '8px 10px'});
        $("#inp1[name=one]").attr("disabled", "disabled");
     }else if(one == 0){
        $("#disp1").html("Wrong!!! &nbsp;&nbsp; Answer is A");
        $("#disp1").css({'color':'red', 'padding': '8px 10px'});
        $("#inp1[name=one]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 

   
   
   $("#btn2").click(function(){
         let  two = $("#inp2[name=two]:checked").val();
     if(two){  
      if(two == 1){
        $("#disp2").html("Correct!!!");
        $("#disp2").css({'color':'green', 'padding': '8px 20px'});
        $("#inp2[name=two]").attr("disabled", "disabled");
     }else if(two == 0){
        $("#disp2").html("Wrong!!! &nbsp;&nbsp; Answer is B");
        $("#disp2").css({'color':'red', 'padding': '8px 10px'});
        $("#inp2[name=two]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   $("#btn3").click(function(){
         let  three = $("#inp3[name=three]:checked").val();
     if(three){  
      if(three == 1){
        $("#disp3").html("Correct!!!");
        $("#disp3").css({'color':'green', 'padding': '8px 30px'});
        $("#inp3[name=three]").attr("disabled", "disabled");
     }else if(three == 0){
        $("#disp3").html("Wrong!!! &nbsp;&nbsp; Answer is A");
        $("#disp3").css({'color':'red', 'padding': '8px 10px'});
        $("#inp3[name=three]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   $("#btn4").click(function(){
         let  four = $("#inp4[name=four]:checked").val();
     if(four){  
      if(four == 1){
        $("#disp4").html("Correct!!!");
        $("#disp4").css({'color':'green', 'padding': '8px 40px'});
        $("#inp4[name=four]").attr("disabled", "disabled");
     }else if(four == 0){
        $("#disp4").html("Wrong!!! &nbsp;&nbsp; Answer is B");
        $("#disp4").css({'color':'red', 'padding': '8px 10px'});
        $("#inp4[name=four]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   $("#btn5").click(function(){
         let  five = $("#inp5[name=five]:checked").val();
     if(five){  
      if(five == 1){
        $("#disp5").html("Correct!!!");
        $("#disp5").css({'color':'green', 'padding': '8px 50px'});
        $("#inp5[name=five]").attr("disabled", "disabled");
     }else if(five == 0){
        $("#disp5").html("Wrong!!! &nbsp;&nbsp; Answer is A");
        $("#disp5").css({'color':'red', 'padding': '8px 10px'});
        $("#inp5[name=five]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   $("#btn6").click(function(){
         let  six = $("#inp6[name=six]:checked").val();
     if(six){  
      if(six == 1){
        $("#disp6").html("Correct!!!");
        $("#disp6").css({'color':'green', 'padding': '8px 60px'});
        $("#inp6[name=six]").attr("disabled", "disabled");
     }else if(six == 0){
        $("#disp6").html("Wrong!!! &nbsp;&nbsp; Answer is C");
        $("#disp6").css({'color':'red', 'padding': '8px 10px'});
        $("#inp6[name=six]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   $("#btn7").click(function(){
         let  seven = $("#inp7[name=seven]:checked").val();
     if(seven){  
      if(seven == 1){
        $("#disp7").html("Correct!!!");
        $("#disp7").css({'color':'green', 'padding': '8px 70px'});
        $("#inp7[name=seven]").attr("disabled", "disabled");
     }else if(seven == 0){
        $("#disp7").html("Wrong!!! &nbsp;&nbsp; Answer is C");
        $("#disp7").css({'color':'red', 'padding': '8px 10px'});
        $("#inp7[name=seven]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   
   $("#btn8").click(function(){
         let  eight = $("#inp8[name=eight]:checked").val();
     if(eight){  
      if(eight == 1){
        $("#disp8").html("Correct!!!");
        $("#disp8").css({'color':'green', 'padding': '8px 80px'});
        $("#inp8[name=eight]").attr("disabled", "disabled");
     }else if(eight == 0){
        $("#disp8").html("Wrong!!! &nbsp;&nbsp; Answer is A");
        $("#disp8").css({'color':'red', 'padding': '8px 10px'});
        $("#inp8[name=eight]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   $("#btn9").click(function(){
         let  nine = $("#inp9[name=nine]:checked").val();
     if(nine){  
      if(nine == 1){
        $("#disp9").html("Correct!!!");
        $("#disp9").css({'color':'green', 'padding': '8px 90px'});
        $("#inp9[name=nine]").attr("disabled", "disabled");
     }else if(nine == 0){
        $("#disp9").html("Wrong!!! &nbsp;&nbsp; Answer is A");
        $("#disp9").css({'color':'red', 'padding': '8px 10px'});
        $("#inp9[name=nine]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 


   $("#btn10").click(function(){
         let  ten = $("#inp10[name=ten]:checked").val();
     if(ten){  
      if(ten == 1){
        $("#disp10").html("Correct!!!");
        $("#disp10").css({'color':'green', 'padding': '8px 100px'});
        $("#inp10[name=ten]").attr("disabled", "disabled");
     }else if(ten == 0){
        $("#disp10").html("Wrong!!! &nbsp;&nbsp; Answer is A");
        $("#disp10").css({'color':'red', 'padding': '8px 10px'});
        $("#inp10[name=ten]").attr("disabled", "disabled");
     }
     }else{
         alert("Choose one answer")
      }
   }); 
     
    })
    </script>