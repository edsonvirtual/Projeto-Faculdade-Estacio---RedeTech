<?php define('BASE_PATH', realpath(dirname(__FILE__)));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>RedeTech</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/slide.css">
    <script src="js/slide.js" defer></script>
    <script src="js/menu.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <script src="https://kit.fontawesome.com/324b71f187.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@300;400;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/footer.css">

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/styles.css">
    

</head>

<body>
    
    <div class="container-geral">

        <!--=============== HEADER ===============-->
        <header class="header">
            <nav class="nav container">
                <div class="nav__data">
                    <a href="#" class="nav__logo">
                        <img src="img/redetech copy.png" alt=""></a>
                    <div class="nav__toggle" id="nav-toggle">
                        <i class="ri-menu-line nav__burger"></i>
                        <i class="ri-close-line nav__close"></i>
                    </div>
                </div>
                <!--=============== NAV MENU ===============-->
                <div class="nav__menu" id="nav-menu">
                    <ul class="nav__list">
                        <li><a href="index.php" class="nav__link">Home</a></li>
                        <li><a href="./products.php" class="nav__link">Produtos</a></li>
                        <li><a href="empresa.html" class="nav__link">Empresa</a></li>
                        <li><a href="quemsomos.html" class="nav__link">Quem Somos</a></li>


                        <!--=============== DROPDOWN 1 ===============-->
                        <li class="dropdown__item">
                            <div class="nav__link">
                                Serviços <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                            </div>

                            <ul class="dropdown__menu">
                                <li>
                                    <a href="#" class="dropdown__link">
                                        <i class="ri-pie-chart-line"></i> Manutenção
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="dropdown__link">
                                        <i class="ri-arrow-up-down-line"></i> Produtos
                                    </a>
                                </li>

                                <!--=============== DROPDOWN SUBMENU ===============-->

                            </ul>
                        </li>
                        <li><a href="contato.html" class="nav__link">Contato</a></li>

                        <!--=============== DROPDOWN 2 ===============-->
                        <li class="dropdown__item">
                            <div class="nav__link">
                                Login <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                            </div>

                            <ul class="dropdown__menu">
                                <li>
                                    
                                    <a href="login.php" class="dropdown__link">
                                        <i class="ri-user-line"></i> Login
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="dropdown__link">
                                        <i class="ri-lock-line"></i> Accounts
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="dropdown__link">
                                        <i class="ri-message-3-line"></i> Messages
                                    </a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </div>
            </nav>
            <script src="assets/js/main.js"></script>
        </header>
        <section class="slider">
            <div class="slider-content">

                <input type="radio" name="btn-radio" id="radio1">
                <input type="radio" name="btn-radio" id="radio2">
                <input type="radio" name="btn-radio" id="radio3">

                <div class="slide-box primeiro">
                    <img class="img-desktop" src="img/banner1.jpg" alt="slide 1">
                    <img class="img-mobile" src="img/banner1.jpg" alt="slide 1">
                </div>

                <div class="slide-box">
                    <img class="img-desktop" src="img/banner4.jpg" alt="slide 3">
                    <img class="img-mobile" src="img/banner4" alt="slide 1">
                </div>

                <div class="slide-box">
                    <img class="img-desktop" src="img/banner5.jpg" alt="slide 3">
                    <img class="img-mobile" src="img/banner5.jpg" alt="slide 3">
                </div>

                <div class="nav-auto">
                    <div class="auto-btn1"></div>
                    <div class="auto-btn2"></div>
                    <div class="auto-btn3"></div>
                </div>

                <div class="nav-manual">
                    <label for="radio1" class="manual-btn"></label>
                    <label for="radio2" class="manual-btn"></label>
                    <label for="radio3" class="manual-btn"></label>
                </div>

            </div>
        </section>

        <div class="menorpreco">
            <p>MENORES PREÇOS</p>
        </div>

        <div class="container-card">
            <!--card 1-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/mouse.png" alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>MOUSE</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <!--card 2-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/2144780-design-banner-para-jogos-com-efeito-glitch-luz-neon-no-texto-ilustracao-de-jogos-gratis-vetor.jpg"
                        alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>Apple watch</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <!--card 3-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/banner.jpg" alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>Apple watch</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <!--card 4-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/banner1.jpg" alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>Apple watch</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <!--card 5-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/banner3.jpg" alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>Apple watch</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <!--card 6-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/banner4.jpg" alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>Apple watch</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <!--card 7-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/banner-do-youtube-para-jogos_584197-754.avif" alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>Apple watch</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <!--card 8-->
            <div class="card-produtos">
                <div class="relative">
                    <img src="img/banner5.jpg" alt="">
                    <div class="wishlist">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <div class="content">
                    <h3>Apple watch</h3>
                    <p>A realidade de duração do Smart watch</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="fas fa-star grey"></i>
                    </div>
                    <div class="price">R$ 129,00</div>
                    <div class="buttons">
                        <button class="add-to-card">Adicionar ao carrinho</button>
                        <button class="refresh"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <main>
            <div class="textocentral">
                <p class="textocentral">
                    Nossos clientes
                </p>
            </div>

            <div class="container-logo-clientes">
                <div class="imagens-clientes">
                    <img src="img/banner3.jpg" alt="">
                    <img src="img/banner4.jpg" alt="">
                    <img src="img/banner5.jpg" alt="">
                </div>
            </div>
        </main>
        <footer>
            <div class="footer-content">
                <div class="footer-contacts">
                    <img src="img/redetech copy.png" alt="">

                    <div class="footer-social-media">
                        <a href="#" class="footer-link" id="instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="footer-link" id="facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="footer-link" id="whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                <ul class="footer-list">
                    <li>
                        <h3>Blog</h3>
                    </li>
                    <li>
                        <a href="#" class="footer-link">Home</a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">Empresa</a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">Quem somos</a>
                    </li>
                </ul>

                <ul class="footer-list">
                    <li>
                        <h3>Produtos</h3>
                    </li>
                    <li>
                        <a href="#" class="footer-link">Home</a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">Empresa</a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">Quem somos</a>
                    </li>
                </ul>


                <div class="footer-subscribe">
                    <h3>Se inscreva-se</h3>
                    <p>
                        Entre com seu email para receber notificações e nova ideias
                    </p>
                    <div class="input-group">
                        <input type="email" id="email">
                        <button>
                            <i class="fa-regular fa-envelope"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <p>&copy; Todos os direitos reservado -- RedeTech soluções em informática --</p>
            </div>
        </footer>




</body>

</html>
