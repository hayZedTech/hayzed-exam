<script src="bootstrap.bundle.min.js"></script>
<script src="jQuery.js"></script>
        <script>
             $(document).ready(function(){
                setTimeout(function() {
                $('#staticBackdrop_submit').modal('show');
                $("#time01").hide();
                $("head").hide();
                 }, 60000 * 20);
                //  1202000  
            })


                    
            function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.text(minutes + ":" + seconds);

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
            }

            jQuery(function ($) {
                    display = $('#time');
                startTimer(20 * 60, display);
            });




    document.addEventListener("keydown", function (event){
    if (event.ctrlKey){
       event.preventDefault();
    }
    if(event.keyCode == 123){
       event.preventDefault();
    }
});


document.addEventListener('contextmenu', 
     event => event.preventDefault()
);
        </script>


       
</html>