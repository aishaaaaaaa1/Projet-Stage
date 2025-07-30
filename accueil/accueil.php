<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
 <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.4.1/dist/css/glide.core.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.4.1/dist/glide.min.js"></script>
    <link
      rel="stylesheet"
      href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"
    />

    <link rel="stylesheet" href="style.css?v=2" />
    <title>Document</title>
  </head>
  <body>
     <nav>
      <div class="nav__header">
        <div class="nav__logo">
          <a href="#">CampusOne</a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <i class="ri-menu-line"></i>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li><a href="../accueil/index.php">Accueil</a></li>
        <li><a href="../about.html">about us</a></li>
        <li><a href="../contact/contact.html">contact</a></li>
        
       
          <a class="btn btn-signup" href="../login/login.php">SIGN UP</a>
     
      </ul>
    </nav>
    
    <header>
     

      <section class="slider-container">
        <div class="glide">
          <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
              <li class="glide__slide">
                <a href="../pei/pei.php">
                  <img src="pexels-suzyhazelwood-8657777.jpg" alt="slide1" />
                  <h4>Portail Étudiant Intégré</h4>
                </a>
              </li>
              <li class="glide__slide">
                <a href="../biblio/book.html">
                  <img src="pexels-ivo-rainha-527110-1290141.jpg" alt="slide2" />
                  <h4>Bibliothèque</h4>
                </a>
              </li>
              <li class="glide__slide">
                <a href="../reservation/reservation.php">
                  <img src="pexels-alleksana-4234540.jpg" alt="slide3" />
                  <h4>Réservation de salles</h4>
                </a>
              </li>
              <li class="glide__slide">
                <a href="../restau.php">
                  <img src="pexels-viliamphotography-29240222.jpg" alt="slide4" />
                  <h4>Restaurant</h4>
                </a>
              </li>
             
              
              </li>
              
           
          </div>

          <div class="glide__arrows" data-glide-el="controls">
            <button class="glide__arrow glide__arrow--left" data-glide-dir="<">
              <i class="las la-arrow-left"></i>
            </button>
            <button class="glide__arrow glide__arrow--right" data-glide-dir=">">
              <i class="las la-arrow-right"></i>
            </button>
          </div>
        </div>

   
    </header>
  
        <section class="section__container discover__container">
      <h2 class="section__header">Découvrir le Campus</h2>
      <p class="section__description">
        Explorez les principaux services et espaces de vie de votre campus, pensés pour accompagner chaque étudiant au quotidien.
      </p>
      <div class="discover__grid">
        <div class="discover__card">
          <span><i class="ri-book-2-line"></i></span>
          <h4>Bibliothèque</h4>
          <p>
            Accédez à des milliers de livres, ressources numériques et espaces de travail pour réussir vos études dans un environnement calme et moderne.
          </p>
        </div>
        <div class="discover__card">
          <span><i class="ri-calendar-check-line"></i></span>
          <h4>Planning & Tâches</h4>
          <p>
            Organisez vos cours, examens et devoirs grâce à un agenda interactif et des rappels personnalisés.
          </p>
        </div>
        <div class="discover__card">
          <span><i class="ri-building-line"></i></span>
          <h4>Réservation de salles</h4>
          <p>
            Réservez facilement une salle de travail, un laboratoire ou un espace associatif selon vos besoins.
          </p>
        </div>
       
    </section>
      
     <section class="about">
  <div class="image">
    <img src="pexels-pixabay-159514.jpg" alt="Notre histoire">
  </div>
  <div class="content">
    <h1>Notre histoire</h1>
    <h2>Un campus historique, une plateforme innovante</h2>
    <p>
      Le campus existe depuis 1950 et a vu passer des générations d'étudiants, de professeurs et d'innovations pédagogiques. En 2024, pour répondre aux nouveaux besoins de la vie universitaire, la plateforme CampusOne a été créée. <br><br>
      CampusOne est une solution digitale moderne qui rassemble emploi du temps, bibliothèque, réservation de salles, jeux éducatifs et bien plus encore dans un espace unique et convivial. <br>
      Notre ambition : faciliter la vie étudiante, renforcer la communauté et accompagner chaque membre du campus dans sa réussite, aujourd'hui et pour demain.
    </p>
  </div>
</section>
         <section class="section__container banner__container">
      <div class="banner__card">
        <h4>50+</h4>
        <p>Years Experience</p>
      </div>
      <div class="banner__card">
        <h4>37K</h4>
        <p>Happy Clients</p>
      </div>
      <div class="banner__card">
        <h4>4.8</h4>
        <p>Overall Ratings</p>
      </div>
    </section>
    <section class="section__container destination__container" id="about">
      <h2 class="section__header">Les espaces fun du campus</h2>
      <p class="section__description">
        Découvrez les lieux pour vous détendre, vous amuser et partager des moments inoubliables sur le campus : jeux, sport, détente et plus encore !
      </p>
      <div class="destination__grid">
        <div class="destination__card">
          <img src="pexels-pixabay-221537.jpg" alt="Espace détente" />
          <div class="destination__card__details">
            <div>
              <h4>Espace détente</h4>
              <p>Un espace pour se relaxer, discuter ou participer à des activités associatives et ludiques.</p>
            </div>
            <div class="destination__rating">
              <span><i class="ri-cup-line"></i></span>
            </div>
          </div>
        </div>
        <div class="destination__card">
          <img src="pexels-thelazyartist-1598347.jpg" alt="Installations sportives" />
          <div class="destination__card__details">
            <div>
              <h4>Installations sportives</h4>
              <p>Des terrains et équipements pour pratiquer vos sports favoris, organiser des tournois et garder la forme.</p>
            </div>
            <div class="destination__rating">
              <span><i class="ri-football-line"></i></span>
            </div>
          </div>
        </div>
        <div class="destination__card">
          <img src="pexels-denniz-futalan-339724-2306897.jpg" alt="Espace jeux" />
          <div class="destination__card__details">
            <div>
              <h4>Espace jeux</h4>
              <p>Des jeux de société, consoles, baby-foot et mini-jeux éducatifs pour s'amuser entre amis.</p>
            </div>
            <div class="destination__rating">
              <span><i class="ri-gamepad-line"></i></span>
            </div>
          </div>
        </div>
      </div>
    </section>
       <section class="section__container showcase__container" id="package">
      <div class="showcase__image">
        <img src="pexels-ivo-rainha-527110-1290141.jpg" alt="showcase" />
      </div>
      <div class="showcase__content">
        <h4>RÉSERVEZ UNE SALLE SUR LE CAMPUS</h4>
        <p>
          Sur notre campus, la diversité et la qualité des salles mises à disposition sont un véritable atout pour la réussite de tous. Nous proposons des salles de cours lumineuses et modernes, des salles de réunion équipées de matériel audiovisuel, des laboratoires spécialisés pour les travaux pratiques, ainsi que des espaces associatifs chaleureux pour les clubs et projets étudiants. Chaque salle est pensée pour offrir un environnement propice à la concentration, à la créativité et à la collaboration, avec des équipements adaptés à chaque besoin : wifi haut débit, tableaux interactifs, vidéoprojecteurs, mobilier modulable, accès PMR, etc.
          <br><br>
          Grâce à notre plateforme de réservation en ligne, vous pouvez consulter en temps réel la disponibilité de chaque salle, comparer les capacités et les équipements, puis réserver en quelques clics le créneau qui vous convient. Ce service vous évite les démarches administratives fastidieuses et vous permet de gérer vos réservations à tout moment, directement depuis votre espace personnel. Vous recevez une confirmation immédiate et pouvez modifier ou annuler votre réservation en toute simplicité.
           <br><br>
          Profitez d'espaces lumineux, connectés et adaptés à toutes les envies, et vivez pleinement votre expérience universitaire. La réservation en ligne, c'est la liberté d'organiser vos activités sans contrainte, la garantie de trouver l'espace idéal et la possibilité de partager des moments uniques avec vos camarades. Rejoignez la communauté CampusOne et découvrez une nouvelle façon d'apprendre, de créer et de partager sur le campus !
        </p>
        
        <a href="../reservation/reservation.php" class="btn" style="color: #a68868; margin-top: 20px; display: inline-block; text-align:center;">
          Réserver une salle maintenant
          <span><i class="ri-arrow-right-line"></i></span>
        </a>
      </div>
    </section>
    

    <!-- Slides Avis Campus -->
<h2 class="section__header" style="text-align:center; margin: 40px 0 24px 0; color:#071739;">Avis des étudiants et membres du campus</h2>
<div class="swiper-slide">
  <div class="client__card">
    <div class="client__content">
      <div class="client__rating">
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
      </div>
      <p>
        CampusOne a révolutionné mon organisation : je réserve mes salles en quelques clics, je retrouve tous mes documents et je communique facilement avec mes professeurs. Un vrai plus pour la vie étudiante !
      </p>
    </div>
    <div class="client__details">
      <img src="D4.jpg" alt="client" />
      <div>
        <h4>Julie Martin</h4>
       
      </div>
    </div>
  </div>
</div>
<div class="swiper-slide">
  <div class="client__card">
    <div class="client__content">
      <div class="client__rating">
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
        <span><i class="ri-star-fill"></i></span>
      </div>
      <p>
        Grâce à la plateforme, la gestion des emplois du temps et la réservation des salles sont devenues simples et rapides. L'accès à la bibliothèque numérique est aussi très pratique pour mes recherches.
      </p>
    </div>
    <div class="client__details">
      <img src="D4.jpg" alt="client" />
      <div>
        <h4>Lucas Dupont</h4>
        
      </div>
    </div>
  </div>
</div>

          
       
     <footer style="background-color: #ffffff;">
      <div class="section__container footer__container">
        <div class="footer__col">
          <div class="footer__logo">
            <a href="#">CampusOne</a>
          </div>
          
          <ul class="footer__socials" ">
            <li>
              <a href="#"><i class="ri-facebook-fill"></i></a>
            </li>
            <li>
              <a href="#"><i class="ri-instagram-line"></i></a>
            </li>
            <li>
              <a href="#"><i class="ri-twitter-fill"></i></a>
            </li>
            <li>
              <a href="#"><i class="ri-linkedin-fill"></i></a>
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Liens utiles</h4>
          <ul class="footer__links">
            <li><a href="../accueil/index.php">Accueil</a></li>
            <li><a href="../about.html">Notre histoire</a></li>
            <li><a href="../contact/contact.html">Contact</a></li>
            <li><a href="../login/login.php">Login</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Quick Links</h4>
          <ul class="footer__links">
           
             <li><a href="../biblio/book.html">Bibliothèque</a></li>
            
          </ul>
        </div>
        
        <div class="footer__col">
          <h4>Bibliothèque & Services</h4>
          <ul class="footer__links">
           
            <li><a href="../reservation/reservation.php">Réservation de salles</a></li>
            <li><a href="../restau.php">Restaurant</a></li>
            
           
            
          </ul>
        </div>
      </div>
      <div class="footer__bar">
        Copyright © 2025 Web Design Mastery. All rights reserved.
      </div>
    </footer>
 
<script>
      new Glide('.glide', {
        type: 'carousel',
        perView: 5,
        focusAt: 'center',
        autoplay: 3000,
        arrows: {
          prev: '.glide__arrow--left',
          next: '.glide__arrow--right',
        },
      }).mount();
    </script>
    
  </body>
</html>
