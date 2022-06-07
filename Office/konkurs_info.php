<?php
include('header.php');
?>
<style>
	.video-fluid {
		width: auto;
		height: auto;
	}
</style>
  <!-- Start contact section  -->
  <section id="contact">
     <div class="container">
       <div class="row">
         <div class="col-md-12">
           <div class="title-area">
              <h2 class="title">КОНКУРС!!!</h2>
              <span class="line"></span><br><br>
              <a href="konkurs.php" class="btn btn-primary btn-lg">Активировать билет</a>
            </div>
         </div>
         <div class="col-md-12">
           <div class="cotact-area">
             <div class="row">
               <div class="col-md-12 pull-center" style="text-align:center;">
				<video class="video-fluid z-depth-1" loop controls>
				  <source src="assets/video/konkurs_video.mp4" type="video/mp4">
				</video>				
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
  </section>
  <!-- End contact section  -->

<?php
include('footer.php');
?>