<?php
include 'include/head.php';
?>
<!DOCTYPE html>
<html lang="en">

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">

            <a class="navbar-brand" href="#page-top">
                <img src="./assets\img\logo\logo em grande\logo_corrigido.png" alt="Whisper Logo" style="max-height: 40px;">
            </a>

            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#about">Everything about my project</a></li>
                    <li class="nav-item"><a class="nav-link" href="#avatar-section">Developers </a></li>
                    <li class="nav-item"><a class="nav-link" href="#pap-tools">Project Tools</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/login.php">login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <img src="./assets/img/logo/logo em grande/logo_corrigido.png"
                        alt="Whisper Logo"
                        class="img-fluid logo">
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5"></p>
                </div>
            </div>
        </div>
    </header>

    <!-- About-->
    <section class="page-section bg-primary" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-white mt-0">What is my project about?</h2>
                    <hr class="divider divider-light" />
                    <p class="text-white-75 mb-4">This website is part of a project called Whisper. The project revolves around a tourism company offering tours in Lisbon and its surroundings. It will include a MySQL database, an application developed in Android Studio, and a functional website where users can contact the company to book their tours around Lisbon, with various types of routes and guides for any destination they wish to explore.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Avatar Section-->
    <section class="page-section bg-dark text-white" id="avatar-section">
        <div class="container-fluid p-0">
            <h2 class="text-center mt-0">Developers</h2>
            <hr class="divider" />
            <div class="row g-0 justify-content-center">
                <div class="col-lg-4 col-sm-6 text-center">
                    <img class="img-fluid rounded-circle" src="./assets/img/avatar-image/Avatar (1).jpg" alt="Rodrigo Assunção" style="max-width: 200px;" />
                    <h3 class="mt-3">Rodrigo Assunção</h3>
                </div>
                <div class="col-lg-4 col-sm-6 text-center">
                    <img class="img-fluid rounded-circle" src="./assets/img/avatar-image/AvatarAlex.png" alt="Rodrigo Alexandre" style="max-width: 200px;" />
                    <h3 class="mt-3">Rodrigo Alexandre</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- pap-tools -->
    <section class="page-section" id="pap-tools">
        <div class="container px-4 px-lg-5">
            <h2 class="text-center mt-0">Tools I used in my project</h2>
            <hr class="divider" />

            <div class="row gx-4 gx-lg-5">
                <!-- image 1 -->
                <div class="col-lg-4 col-md-6 text-center d-flex flex-column align-items-center">
                    <div class="mt-5">
                        <div class="mb-2">
                            <img src="./assets/img/pap-tools/vscode.png" alt="Visual Studio Code" class="img-fluid" style="max-width: 50px;">
                        </div>
                        <h3 class="h4 mb-2">Visual Studio Code</h3>
                        <p class="text-muted mb-0">Visual Studio Code was the tool that allowed me to program in multiple programming languages like MySQL, HTML, CSS, and JavaScript. Its features, such as the integrated terminal and support for extensions, made the development process much smoother and more efficient</p>
                    </div>
                </div>

                <!-- image 2 -->
                <div class="col-lg-4 col-md-6 text-center d-flex flex-column align-items-center ">
                    <div class="mt-5">
                        <div class="mb-2">
                            <img src="./assets/img/pap-tools/androidStudio.jpg" alt="Android Studio" class="img-fluid" style="max-width: 50px;">
                        </div>
                        <h3 class="h4 mb-2">Android Studio</h3>
                        <p class="text-muted mb-0">Android Studio allowed me to use Java programming, which enabled me to create the splash screen for my project.</p>
                    </div>
                </div>

                <!-- image 3 -->
                <div class="col-lg-4 col-md-6 text-center d-flex flex-column align-items-center ">
                    <div class="mt-5">
                        <div class="mb-2">
                            <img src="./assets/img/pap-tools/IMG-Xamp.png" alt="Xampp" class="img-fluid" style="max-width: 50px;">
                        </div>
                        <h3 class="h4 mb-2">Xampp</h3>
                        <p class="text-muted mb-0">XAMPP made it easy to set up a local server for my project, allowing me to run MySQL and test PHP scripts locally.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer-->
    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2025 - Whisper</div>
        </div>
    </footer>
</body>

</html>