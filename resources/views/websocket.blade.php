<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{mix('css/helper.css')}}">
    <link rel="stylesheet" href="{{mix('css/ws.css')}}">
    <title>Document</title>
</head>
<body>

<main class="d-flex align-center h-100vh">

    <section id="section-login" class="d-flex flex-col justify-center align-center mx-auto card">
        <form id="form-login" class="d-flex flex-col ">

            <h2>Login</h2>

            <input id="input-email" type="text" placeholder="Email">

            <input id="input-password" type="password" placeholder="password">

            <button type="submit">Login</button>
        </form>
    </section>

    <section id="section-chat" class="d-flex flex-col justify-between align-center card mx-auto h-80 hide">

        <nav id="nav-online" class="w-100 d-flex">

            <h3 class="white pl-1">Chat</h3>

            <div id="avatars">

{{--                avatar --}}
{{--                <span class="avatar">AL</span>--}}
{{--                <span class="avatar">AB</span>--}}
{{--                <span class="avatar">AC</span>--}}
            </div>


        </nav>


        <ul id="list-messages" class="px-1 d-flex flex-col">
        </ul>

        <form id="form" class="w-100 d-flex flex-col">

            <span class="pl-1" id="span-typing"></span>
{{--            <label for="input-message">Message:</label>--}}
            <input
                    id="input-message"
                    class="py-2 pl-1"
                    placeholder="Type a message"
                   type="text">
        </form>

    </section>

</main>

<script src="{{ mix('js/app.js')  }}"></script>

</body>
</html>