<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Actionable Immunology Database led by Dr. Tao Wang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Your page description here" />
    <meta name="author" content="" />

    @include('css')

    <style>
        /*
        ---------------------------------------------
        navbar
        ---------------------------------------------
        */
        .nav-cover {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            box-shadow: 1px 0 5px rgb(200 200 200 / 80%);
            z-index: 999;
            padding: 0 150px;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            align-content: center;
            transition: 0.6s;
            z-index: 999;
            background-color: #ffffff;
            font-family: "Poppins", sans-serif;
            max-height: 66px;
        }

        .logo {
            position: relative;
            display: flex;
            text-decoration: none;
            font-size: 25px;
        }

        .nav-menu {
            display: flex;
            padding: 0;
            margin: 0;
            justify-content: space-around;
        }

        .nav-menu .active {
            border-bottom: 2px solid #0a58ca;
            color: #0a58ca;
        }

        .nav-menu li {
            list-style: none;
            text-decoration: none;
        }

        .nav-menu li {
            white-space: nowrap;
            margin: 1rem 0;
        }

        /*.nav-menu li:nth-child(-n+2) {*/
        /*    color: #333333;*/
        /*    text-decoration: none;*/
        /*    display: block;*/
        /*    padding: 1rem 1rem;*/
        /*    letter-spacing: 1px;*/
        /*}*/

        .nav-menu li:not(:last-child) a {
            text-decoration: none;
            padding: 1rem 1rem 1.6rem;
            letter-spacing: 1px;
        }

        .nav-menu li:not(:last-child) a:hover {
            border-bottom: 2px solid #0a58ca;
            color: #0a58ca;
        }

        .nav-menu li:last-child {
            margin-top: 10px;
            margin-left: 1rem;
        }

        .nav-content .burger {
            display: none;
            font-size: 25px;
        }

        .nav-content .burger:hover {
            cursor: pointer;
            color: darkgrey;
        }

        .dropdown-item:focus,
        .dropdown-item:hover {
            background-color: transparent;
            color: #0a58ca;
        }

        @media only screen and (max-width: 1000px) {
            .logo {
                margin: 1rem 0 .5rem 0;
            }

            .nav-menu {
                max-height: 0;
                width: 100%;
                position: absolute;
                left: 0;
                top: 66px;
                background-color: #ffffff;
                display: none;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                overflow: hidden;
                border-bottom: 1px solid rgb(200 200 200 / 40%);
            }

            .nav-menu li:last-child {
                margin: 10px 0 15px;
                padding: 0;
            }

            .nav-menu ul {
                border-bottom: 1px solid black;
            }

            .showing {
                max-height: 1000px;
                transition: 1s;
            }

            .nav-content .burger {
                display: block;
            }
        }

        /* The popup chat - hidden by default */
        .chat-box {
            display: none;
            position: fixed;
            bottom: 15px;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 9;
            height: 60%;
            width: 30%;
        }

        .chat-icon {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9;
            color: limegreen;
            font-size: 50px;
        }

        .chat-icon:hover {
            cursor: pointer;
            color: rgba(0, 0, 0, 0.7);
        }

        .resize-handle {
            width: 15px;
            height: 15px;
            background-color: #000;
            position: absolute;
            cursor: se-resize;
        }

        /* Close Button */
        .close-btn {
            position: absolute;
            top: -15px;
            right: -15px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            font-size: 20px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .close-btn:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .close-btn:focus {
            outline: none;
        }
    </style>

    <script src="{{ asset("js/jquery-3.5.1.js") }}"></script>

</head>

<body>

    <div class="nav-cover">
        <div class="nav-content">
            <a href="{{ url('/') }}" class="logo"><img src="{{ asset('img/logo.png') }}" width="125"></a>
            <ul class="nav-menu">
                <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : ''  }}">Home</a></li>
                <li><a href="{{ url('/database') }}" class="{{ request()->is('database*') ? 'active' : ''  }}">Database</a>
                </li>
                <li><a href="{{ url('/tools') }}" class="{{ request()->is('tools') ? 'active' : ''  }}">Tools</a></li>
                @guest
                <li><a href="{{ url('/login') }}" class="btn btn-primary">Login</a></li>
                @else
                <li>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            @if( Auth::check() && Auth::user()->role == "admin" )
                            <i class="fa-solid fa-user-secret me-1"></i>
                            @else
                            <i class="fa-solid fa-user me-1"></i>
                            @endif
                            {{ Auth::user()->username }}
                        </button>
                        <div class="dropdown-menu">
                            @if( Auth::check() && Auth::user()->role == "admin" )
                            <a class="dropdown-item" href="{{ url('/user-management') }}"><i
                                    class="fa-solid fa-users-gear me-2"></i>Management</a>
                            @endif
                            <a class="dropdown-item" href="{{ url('/user-profile') }}"><i
                                    class="fa-solid fa-address-card me-2"></i>Profile</a>
                            <hr class="m-1">
                            <a class="dropdown-item" href="{{ url('/logout') }}"><i
                                    class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a>
                        </div>
                    </div>
                </li>
                @endguest
            </ul>
            <div class="burger">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </div>

    <div style="margin-top: 90px; margin-bottom: 50px">
        @yield('content')
    </div>

    <script>
        // burger menu toggles
        $('.burger').click(function() {
            let nav_menu = $('.nav-menu');
            nav_menu.css('display', 'flex')
            nav_menu.hasClass('showing') ? nav_menu.removeClass('showing') : nav_menu.addClass('showing');
        })

        $('.dismiss').click(function() {
            $('.alert').toggle();
        })
    </script>

</body>

</html>