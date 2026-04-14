<?php
require_once 'login/db.php'; 

header('Content-Type: application/json');
$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category']; 
    
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');

    $htmlFilename = $slug . '.html';
    $cssFilename = $slug . '.css';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/'; 
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $dbImagePath = 'uploads/' . $imageName; 
            $pagesDir = 'pages/';
            
            if (!is_dir($pagesDir)) mkdir($pagesDir, 0777, true);
            if ($category === 'truck' || $category === 'truck') {
                // ГЕНЕРАЦІЯ HTML ДЛЯ ВАНТАЖІВОК 
                $htmlContent = <<<HTML
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>{$title}</title>
                    <link rel='stylesheet' href='{$cssFilename}'>
                </head>
                <header class="header">
                    <nav class="nav" id="nav">
                        <a href="../index.html"><img src="../logo-image/white-volvo-logo-1.png" alt="" class="volvo-logo"></a>
                        <ul class="nav-list">
                            <a href="../cars.html"><li><p>CARS</p></li></a>
                            <a href="../trucks.html"><li><p>TRUCKS</p></li></a>
                            <a href="buses.html"><li><p>BUSES</p></li></a>
                            <a href="history.html"><li><p>HISTORY</p></li></a>
                        </ul>
                    </nav>
                </header>
                <body>
                    <div class="content">
                        <div class="description">
                            <h2></h2>
                            <p>

                            </p>
                        </div>
                        <div class="video">
                            <video autoplay muted loop playsinline>
                                <source src="../images-for-car-page/" type="video/mp4">
                                Your web-browser does not support this video
                            </video>
                        </div>
                        <div class="info">
                            <div class="truck-info">
                                <h3></h3>
                                <p>Applications: </p>
                                <p>Power: </p>
                                <p>Availability: Contact the Volvo Center for more information</p>
                            </div>  
                            <div class="truck-photo">
                                <img src="../images-for-car-page/" alt="">
                            </div> 
                        </div>
                        <div class="description">
                            <h2></h2>
                            <p>

                            </p>
                        </div>
                        <div class="video">
                            <video autoplay muted loop playsinline>
                                <source src="../images-for-car-page/" type="video/mp4">
                                Your web-browser does not support this video
                            </video>
                        </div>
                        <div class="gallery">
                            <img src="../images-for-car-page/" alt="">
                            <img src="../images-for-car-page/" alt="">
                            <img src="../images-for-car-page/" alt="">
                            <img src="../images-for-car-page/" alt="">    
                        </div>
                        <div class="interior-info">
                            <div>
                                <h3></h3>
                                <p>

                                </p>
                            </div>
                            <div>
                                <h3></h3>
                                <p> 

                                </p>

                            </div>
                            <div>
                                <h3></h3>
                                <p>

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="truck-banner">
                            <div class="truck-banner-info">
                                <h2></h2>
                                <p> 

                                </p>
                            </div>
                            <div class="truck-banner-photo">
                                <img src="../images-for-car-page/" alt="">
                            </div>
                    </div>
                    <div class="content">
                        <div class="description">
                            <h2></h2>
                            <p>
                                
                            </p>
                        </div>
                        <img src="../images-for-car-page/" alt="">
                        <div class="grid-info">
                            <div>
                                <h2></h2>
                                <p>

                                </p>
                            </div>
                            <div>
                                <h2></h2>
                                <p>

                                </p>
                            </div>
                            <div>
                                <h2></h2>
                                <p>
                                
                                </p>
                            </div>
                            <div>
                                <h2></h2>
                                <p>
                                
                                </p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
                HTML;
// ГЕНЕРАЦІЯ CSS ДЛЯ ВАНТАЖІВОК 
$cssContent = <<<'CSS'
html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    padding: 0;
    font-family: "Vend Sans", sans-serif;
    font-optical-sizing: auto;
    font-style: normal;
    overflow-x: hidden;
}

.content {
    margin-left: 25vh;
    margin-right: 25vh;
}

h1 {
    font-size: 40px;
    font-weight: bold;
}

a {
    text-decoration: none;
    color: inherit;
    cursor: pointer;

}

a:hover {
    text-decoration: underline;
}

.nav {
    position: relative;
    top: 0;
    left: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background-color: #131313;
    height: 6rem;
}

.nav-list {
    color: #FEFEFE;
    font-family: "Vend Sans", sans-serif;
    font-optical-sizing: auto;
    font-size: 30px;
    font-weight: 400;
    font-style: normal;
    font-weight: 600;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
    margin: 0;
    padding: 0;
    list-style: none;
    flex-grow: 1;
    
}

.volvo-logo {
    width: 10rem;
    height: 2rem;
    padding-left: 2rem;
}

.description {
    margin-top: 15vh;
    margin-bottom: 5vh;
    display: flex;
    justify-content: space-around;
    align-items: center;
    font-weight: 500;
    width: 100%;
    height: auto;
}

.description h2 {
    font-size: 30px;
}

.description p {
    font-size: 15px;
}

.video {
    width: 100%;
    height: 85vh; 
    overflow: hidden;
    background: #000;
    position: relative;
    border-radius: 10px;
}

.video video {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important; 
}

.info  {
    display: flex;            
    align-items: center;      
    background-color: #EDEDED;
    width: 75%;               
    margin: 10vh auto;        
    padding: 8vh 5vw;         
    border-radius: 10px;      
    position: relative;
}

.truck-info {
    flex: 1;                  
    padding-right: 20px;
    color: #131313;
}

.truck-info h3 {
    font-size: 36px;
    margin-bottom: 20px;
}

.truck-info p {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.truck-photo {
    flex: 1;                  
    position: relative;
    z-index: 2;               
}

.truck-photo img {
    width: 140%;              
    max-width: none;          
    transform: translate(20%, 0); 
    display: block;
}

.gallery {
    margin-top: 10vh;
    display: grid;
    grid-template-columns: repeat(2, 1fr); 
    gap: 20px;
    align-items: center;
}

.gallery img {
    width: 100%; 
    height: auto;
    border-radius: 15px;
    object-fit: cover;
}

.interior-info {
    margin-top: 15vh;
    display: flex;
    align-items: baseline;
    justify-content: space-between;
}

.interior-info div {
    width: 20%;
}

.truck-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #EDEDED;
    padding-left: 15vh;
    padding-right: 5vh;
}

.truck-banner img {
    width: 960px;
    height: 600px;
}

.grid-info {
    margin: 10vh 15px 15px 0;

    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    align-items: center;

}
CSS;
            //ГЕНЕРАЦІЯ ШАБЛОНУ HTML ДЛЯ АВТОБУСІВ
            } elseif($category === 'bus' || $category === 'bus') {
                $htmlContent = <<<HTML
                <!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$title}</title>
    <link rel='stylesheet' href={$cssFilename}>
</head>
<header class="header">
    <nav class="nav" id="nav">
        <a href="../index.html"><img src="../logo-image/white-volvo-logo-1.png" alt="" class="volvo-logo"></a>
        <ul class="nav-list">
            <a href="../cars.html"><li><p>CARS</p></li></a>
            <a href="../trucks.html"><li><p>TRUCKS</p></li></a>
            <a href="../buses.html"><li><p>BUSES</p></li></a>
            <a href="history.html"><li><p>HISTORY</p></li></a>
        </ul>
    </nav>
</header>
<body>
    <img src="../images-for-car-page/" alt="" 
    style="width: 100%; height: 60vh; object-fit: cover; object-position: 50% 70%; margin-top: 5vh;">
    <h1 style="display: flex; align-items: center; justify-content: center; letter-spacing: 5px;">
    </h1>
    <div class="intro-text">
        <p>
            
        </p>
    </div>
    <div class="photo1">
        <img src="../images-for-car-page/" alt="">
    </div>
    <div class="specs-banner">
        <div class="specs-banner-photo">
            <img src="../images-for-car-page/" alt="">
        </div>
        <div class="specs-banner-info">
            
            <h2></h2>
            <p>
                
            </p>
        </div>
    </div>
    <div class="specs-banner">
        
        <div class="specs-banner-info">
            <h2></h2>
            <p>
                
            </p>
        </div>
        <div class="specs-banner-photo">
            <img src="../images-for-car-page/" alt="">
        </div>
    </div>
    <img src="../images-for-car-page/" alt="" 
    style="width: 100%; height: 60vh; object-fit: cover; object-position: 50% 70%; margin-top: 5vh;">
    <h1 style="display: flex; align-items: center; justify-content: center; letter-spacing: 3px;">
    </h1>
    <div class="intro-text">
        <p>
           
        </p>
    </div>
    <div class="specs-banner">
        
        <div class="specs-banner-info">
            <h2></h2>
            <p>
                
            </p>
        </div>
        <div class="specs-banner-photo">
            <img src="../images-for-car-page/" alt="">
        </div>
    </div>
    <div class="specs-banner">
        <div class="specs-banner-photo">
            <img src="../images-for-car-page/" alt="">
        </div>
        <div class="specs-banner-info">
            <h2></h2>
            <p>
                
            </p>
        </div>
    </div>
    <img src="../images-for-car-page/" alt="" 
    style="width: 100%; height: 60vh; object-fit: cover; object-position: 50% 70%; margin-top: 5vh;">
    <h1 style="display: flex; align-items: center; justify-content: center; letter-spacing: 3px;">
        
    </h1>
    <div class="intro-text">
        <p>
           
        </p>
    </div>
    <div class="interior-info">
            <div>
                <h3></h3>
                <p>
                    
                </p>
            </div>
            <div>
                <h3></h3>
                <p> 
                    
                </p>
            </div>
            <div>
                <h3></h3>
                <p>
                    
                </p>
            </div>
        </div>
        <img src="../images-for-car-page/" alt="" 
    style="width: 100%; height: 60vh; object-fit: cover; object-position: 50% 70%; margin-top: 5vh;">
    <h1 style="display: flex; align-items: center; justify-content: center; letter-spacing: 3px;">
        
    </h1>
    <div class="intro-text">
        <p>
           
        </p>
    </div>
    <div class="interior-info">
            <div>
                <h3></h3>
                <p>
                    
                </p>
            </div>
            <div>
                <h3></h3>
                <p> 
                    
                </p>
            </div>
            <div>
                <h3></h3>
                <p>
                    
                </p>
            </div>
    </div>
    <img src="../images-for-car-page/" alt="" 
    style="width: 100%; height: 60vh; object-fit: cover; object-position: 50% 70%; margin-top: 5vh;">
    <div class="specs-banner">
        <div class="specs-banner-photo">
            <img src="../images-for-car-page/" alt="">
        </div>
        <div class="specs-banner-info">
            <h2></h2>
            <p>
               
            </p>
        </div>
    </div>
    <div class="specs-banner">
        
        <div class="specs-banner-info">
            <h2></h2>
            <p>
                
            </p>
        </div>
        <div class="specs-banner-photo">
            <img src="../images-for-car-page/" alt="">
        </div>
    </div>
</body>
</html>
HTML;
// ГЕНЕРАЦІЯ ШАБЛОНУ CSS ДЛЯ АВТОБУСІВ 
$cssContent = <<<'CSS'
html {
    scroll-behavior: smooth;
}

body {
    font-family: 'Oswald', sans-serif;
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    display: flex;
    flex-direction: column;
    margin: 0;
    min-height: 100vh;   
    overflow-x: hidden;
}

h1 {
    font-size: 60px;
    font-weight: bold;
    font-family: "Vend Sans", sans-serif;
}

a {
    text-decoration: none;
    color: inherit;
    cursor: pointer;

}

a:hover {
    text-decoration: underline;
}

.nav {
    position: relative;
    top: 0;
    left: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background-color: #131313;
    height: 6rem;
}

.nav-list {
    color: #FEFEFE;
    font-family: "Vend Sans", sans-serif;
    font-optical-sizing: auto;
    font-size: 30px;
    font-weight: 400;
    font-style: normal;
    font-weight: 600;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
    margin: 0;
    padding: 0;
    list-style: none;
    flex-grow: 1;
    
}

.volvo-logo {
    width: 10rem;
    height: 2rem;
    padding-left: 2rem;
}

.intro-text {
    max-width: 800px; 
    margin: 50px auto; 
    text-align: center;
    font-family: "Vend Sans", sans-serif;
}

.intro-text p {
    font-size: 18px; 
    line-height: 1.6; 
    color: #333; 
}

.photo1 {
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo1 img {
    max-width: 1200px; 
    width: 100%; 
    height: auto; 
    box-shadow: 0 20px 40px rgba(0,0,0,0.1); 
    border-radius: 10px;
}

.specs-banner {
    margin-top: 10vh;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-left: 5vh;
    padding-right: 5vh;
    gap: 5vw;
}

.specs-banner img {
    border-radius: 10px;
}

.specs-banner p, .interior-info p {
    font-size: 18px; 
    line-height: 1.6; 
    color: #333; 
}

.interior-info {
    max-width: 1400px;
    width: 90%;
    margin: 10vh auto; 
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 40px; 
    align-items: stretch; 
}

.interior-info div {
    background-color: #F8FAFC; 
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    transition: transform 0.3s ease;
    border-top: 4px solid #4A90E2; 
}

.interior-info div:hover {
    transform: translateY(-8px); 
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}
CSS;


            } else {



// ГЕНЕРАЦІЯ HTML ШАБЛОНУ ЛЕГКОВИХ АВТО
$htmlContent = <<<HTML
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$title}</title>
    <link rel='stylesheet' href='{$cssFilename}'>
</head>
<header class="header">
    <nav class="nav" id="nav">
        <a href="../index.html"><img src="../logo-image/white-volvo-logo-1.png" alt="" class="volvo-logo"></a>
        <ul class="nav-list">
            <a href="../cars.html"><li><p>CARS</p></li></a>
            <a href="trucks.html"><li><p>TRUCKS</p></li></a>
            <a href="buses.html"><li><p>BUSES</p></li></a>
            <a href="history.html"><li><p>HISTORY</p></li></a>
        </ul>
    </nav>
</header>
<body>
    <div class="car-card">
        <div class="car-preview">
            <p>{$title}</p>
            <img src="../{$dbImagePath}" alt="{$title}">
        </div>
        <div class="car-specs">
            <h2>
                
                <br> 
                
            </h2>
            <div class="specs">
                <div>
                    <p>Electric range</p>
                    <p></p>
                </div>
                <div>
                    <p>Battery charging duration 10-80%</p>
                    <p></p>
                </div>
                <div>
                    <p>Max power (hp)</p>
                    <p></p>
                </div>
                <div>
                    <p>Acceleration from 0 to 100 km/h</p>
                    <p></p>
                </div>
            </div>
        </div>
            <div class="carousel-wrapper">
                <button class="carousel-btn prev-btn" id="prevBtn">&#10094;</button>

                <div class="carousel-track" id="track">

                    <div class="carousel-card">
                        <video autoplay muted loop playsinline>
                            <source src="" type="video/mp4">
                            Your web-browser does not support this video
                        </video>
                    </div>

                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 1">
                    </div>

                

                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 2">
                    </div>

                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 3">
                    </div>
                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 4">
                    </div>
                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 5">
                    </div>
                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 6">
                    </div>
                    <div class="carousel-card">
                        <img src="">
                    </div>

                </div>

                <button class="carousel-btn next-btn" id="nextBtn">&#10095;</button>
            </div>
            <br>

            <div class="exterior">
                <div class="title">
                    <h2></h2>
                    <p> <br> </p>
                </div>
                <div class="exterior-photo">
                    <div class="box1"><img src="" alt=""></div>
                    <div class="box2"><img src="" alt=""></div>
                    <div class="box3"><img src="" alt=""></div>
                    <div class="box4"><img src="" alt=""></div>
                    <div class="box5"><img src="" alt=""></div>
                    <div class="box6"><img src="" alt=""></div>
                </div>
            </div>
            <div class="interior">
                <div class="title">
                    <h2></h2>
                    <p>
                        
                        <br> 
                        
                    </p>
                </div>
                <div class="carousel-wrapper">
                <button class="carousel-btn prev-btn" id="prevBtn">&#10094;</button>

                <div class="carousel-track" id="track">

                    <div class="carousel-card">
                        <video autoplay muted loop playsinline>
                            <source src="" type="video/mp4">
                            Your web-browser does not support this video
                        </video>
                    </div>

                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 1">
                    </div>

                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 2">
                    </div>

                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 3">
                    </div>
                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 3">
                    </div>
                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 3">
                    </div>
                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 3">
                    </div>
                    <div class="carousel-card">
                        <img src="" alt="Volvo Photo 3">
                    </div>

                </div>

                <button class="carousel-btn next-btn" id="nextBtn">&#10095;</button>
            </div>
            </div>
        </div>
        <span><a href="#nav"><p>Back to top</p></a></span>
    </div>
    <script>
           document.addEventListener('DOMContentLoaded', () => {
            const carousels = document.querySelectorAll('.carousel-wrapper');

            carousels.forEach(carousel => {
                const track = carousel.querySelector('.carousel-track');
                const prevBtn = carousel.querySelector('.prev-btn');
                const nextBtn = carousel.querySelector('.next-btn');

                if (!track || !prevBtn || !nextBtn) return;

                nextBtn.addEventListener('click', () => {
                    const cardWidth = track.querySelector('.carousel-card').offsetWidth;
                    track.scrollBy({ left: cardWidth + 20, behavior: 'smooth' });
                });

                prevBtn.addEventListener('click', () => {
                    const cardWidth = track.querySelector('.carousel-card').offsetWidth;
                    track.scrollBy({ left: -(cardWidth + 20), behavior: 'smooth' });
                });
            });
        });
    </script>
</body>
</html>
HTML;

// ГЕНЕРАЦІЯ CSS ШАБЛОНУ ЛЕГКОВИХ АВТО
$cssContent = <<<CSS
html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    padding: 0;
    font-family: "Vend Sans", sans-serif;
    font-optical-sizing: auto;
    font-style: normal;
}

h1 {
    font-size: 40px;
    font-weight: bold;
}

span {
    display: flex;
    align-items: center;
    justify-content: center;
}

a {
    text-decoration: none;
    color: inherit;
    cursor: pointer;

}

a:hover {
    text-decoration: underline;
}

.nav {
    position: relative;
    top: 0;
    left: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background-color: #131313;
    height: 6rem;
}

.nav-list {
    color: #FEFEFE;
    font-family: "Vend Sans", sans-serif;
    font-optical-sizing: auto;
    font-size: 30px;
    font-weight: 400;
    font-style: normal;
    font-weight: 600;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
    margin: 0;
    padding: 0;
    list-style: none;
    flex-grow: 1;
    
}

.volvo-logo {
    width: 10rem;
    height: 2rem;
    padding-left: 2rem;
}

.car-preview {
    display: flex;
    justify-content: space-around;
    align-items: center;
    font-size: 65px;
    font-weight: 500;
    width: 100%;
    height: auto;
    background-color: #F5F3EF;
}

.car-specs h2 {
    display: flex;
    margin-left: 15%;
    font-weight: 600;
}

.specs {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
    gap: 20px;
    margin: 50px 10%;
}

.specs div {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    background-color: #F8FAFC; 
    border-radius: 15px;
    border: 1px solid #E2E8F0; 
    margin: 0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.specs div:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.05);
}

.specs div p:first-child {
    font-size: 14px;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    text-align: center;
}

.specs div p:last-child {
    font-size: 38px;
    font-weight: bold;
    color: #131313;
    margin: 0;
}

.carousel-wrapper {
    position: relative;
    max-width: 100%;
    height: 85vh;
    margin-top: 10vh;
    overflow: hidden; 
}

.carousel-track {
    display: flex;
    gap: 20px; 
    overflow-x: auto; 
    scroll-behavior: smooth; 
    scroll-snap-type: x mandatory; 
    
    scrollbar-width: none; 
}
.carousel-track::-webkit-scrollbar {
    display: none; 
}


.carousel-card {
    flex: 0 0 100%; 
    scroll-snap-align: center; 
    overflow: hidden;
    background: #000;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 85vh; 
}

.carousel-card img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
}

.carousel-card video {
    width: 100%;
    height: 100%;
    object-fit: cover; 
    background: #000;
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.9); 
    color: #131313;
    border: none;
    font-size: 24px;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    border-radius: 50%;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.carousel-btn:hover {
    background-color: #131313;
    color: white;
    transform: translateY(-50%) scale(1.1); 
}

.prev-btn { left: 30px; }
.next-btn { right: 30px; }

.title {
    display: flex;
    align-items: center;
    justify-content: space-around;
}

.title h2 {
    font-size: 30px;
    font-weight: bold;
}

.title p {
    font-size: 20px;
    font-weight: 100;
}

.exterior-photo {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    max-width: 1200px;
    align-items: center;
    justify-content: center;
    margin: 40px auto;
}

.exterior-photo img {
    border-radius: 10px;
    width: 100%;
    display: block;
}

.box1 {
    grid-column: span 2;
}

.box4 {
    grid-column: span 2;
}

span {
    color: #131313;
    font-size: 18px;
    font-weight: bold;
}
CSS; }

            file_put_contents($pagesDir . $htmlFilename, $htmlContent);
            file_put_contents($pagesDir . $cssFilename, $cssContent);

            $stmt = $conn->prepare("INSERT INTO cars (title, description, image_path, page_filename, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $description, $dbImagePath, $htmlFilename, $category);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['newCar'] = array(
                    'id' => $stmt->insert_id,
                    'title' => $title, 
                    'image' => $dbImagePath,
                    'filename' => $htmlFilename 
                );
            } else {
                $response['message'] = 'Помилка бази даних: ' . $conn->error;
            }
        } else {
            $response['message'] = 'Помилка завантаження фото';
        }
    } else {
        $response['message'] = 'Файл не обрано';
    }
}
echo json_encode($response);
?>