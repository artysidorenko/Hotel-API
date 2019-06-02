<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Hotel.local</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Cutive+Mono&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Merriweather&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fffbf0;
                color: black;
                font-family: 'Merriweather', sans-serif;
                font-weight: 200;
                min-height: 100vh;
                margin: 0;
                text-align: center;
            }

            .full-height {
                min-height: 95vh;
                padding: 15px;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
                padding: 25px 10%;
                border: 1px solid black;
                border-radius: 10px;
            }

            .title {
                font-size: 3em;
            }

            .links {
                max-width: 600px;
                margin: auto;
            }

            .links > a {
                display: inline-block;
                color: #636b6f;
                padding: 10px 25px;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .api_list {
                text-align: left;
                margin: auto;
                display: inline-block;
            }

            #app {
                display: inline-block;
                border: 1px solid grey;
                border-radius: 10px;
            }
            .center {
                text-align: center;
                display: block;
            }

            .panel {
                display: inline-block;
                margin: auto;
            }

            footer {
               text-align: left;
               font-size: small;
                
            }
        </style>
    </head>
    <body>
        <div class="full-height panel">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <h1 class="title m-b-md">
                    Hotel Booking API
        </h1>

                <div class="instructions m-b-md">
                    <p>This app works as a hotel booking system, through which you can register new guests and book rooms.</p>
                    <p>Rooms will automatically display whether they are currently occupied (on today's date).</p>
                    <ul class="api_list">
                        <p class="m-b-md"><strong class="center">The back-end API can be accessed directly via these endpoints:</strong></p>
                        <li><i>/api/rooms|guests|bookings GET</i> to list all rooms|guests|bookings</li>
                        <li><i>/api/room|guest|booking/{id} GET</i> for information on a single room|guest|booking</li>
                        <li><i>/api/room|guest|booking/{id} POST</i> to update room|guest|booking information</li>
                        <li><i>/api/new/room|guest|booking POST</i> to add a new room|guest|booking</li>
                    </ul>
                    <p class="m-b-md"><strong>Alternatively users can interact with the API via the below interface:</strong></p>
                </div>

                <div id="app">
                    <div class="links m-b-md">
                        <router-link to="/rooms">List Rooms</router-link>
                        <router-link to="/guests">List Guests</router-link>
                        <router-link to="/bookings">List Bookings</router-link>
                        <router-link to="/new/guest">Enter Guest Information</router-link>
                        <router-link to="/new/booking">Process New Booking</router-link>
                    </div>

                    <router-view></router-view>
                </div>
                <script async src="{{mix('js/app.js')}}"></script>

            </div>
            <footer>
                <p>Site built by Arty Sidorenko/pjsmooth</p>
                <p>Technologies used: Vue.js/Vuex (front-end), PHP/Laravel (back-end), MySQL (db)</p>
            </footer>
        </div>
    </body>
</html>
