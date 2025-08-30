
<div class="prev_next">
      <h3 class="prev" onclick="plusSlides(-1)">Previous</h3>
      <h3 class="next" onclick="plusSlides(1)">Next</h3>
      </div>

      <!-- <div class="caption-container">
        <p id="caption"></p>
      </div> -->

      <div class="row myPagesNum">
        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(1)"><p>1</p></div>
        </div>

        <div class="column">
          <div class="demo cursor"  onclick="currentSlide(2)"><p>2</p></div>
        </div>

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(3)"><p>3</p></div>
        </div>

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(4)"><p>4</p></div>
        </div>

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(5)"><p>5</p></div>
        </div> 

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(6)"><p>6</p></div>
        </div>

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(7)"><p>7</p></div>
        </div>

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(8)"><p>8</p></div>
        </div>

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(9)"><p>9</p></div>
        </div>

        <div class="column">
        <div class="demo cursor"  onclick="currentSlide(10)"><p>10</p></div>
        </div>
      </div>
    </div>

    
     
 <script src="script/jQuery.js"></script>
<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
      showSlides(slideIndex += n);
    }

    function currentSlide(n) {
      showSlides(slideIndex = n);
    }

    function showSlides(n) {
      var i;
      var slides = document.getElementsByClassName("mySlides");
      var dots = document.getElementsByClassName("demo");
      // var captionText = document.getElementById("caption");
      if (n > slides.length) {slideIndex = 1}
      if (n < 1) {slideIndex = slides.length}
      for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
          dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex-1].style.display = "block";
      dots[slideIndex-1].className += " active";
      // captionText.innerHTML = dots[slideIndex-1].alt;
    };

    </script>