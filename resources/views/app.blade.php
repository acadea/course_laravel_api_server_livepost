<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<script>

    function getCookie(name){
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) {
            return parts.pop().split(';').shift();
        }
    }

    function request(url, options){
        // get cookie
        const csrfToken = getCookie('XSRF-TOKEN');
        return fetch(url, {
            headers: {
                'content-type': 'application/json',
                'accept': 'application/json',
                'X-XSRF-TOKEN': decodeURIComponent(csrfToken),
            },
            credentials: 'include',
            ...options,
        })
    }

    function logout(){
        return request('/logout', {
            method: 'POST'
        });
    }

    function login(){
        return request('/login', {
            method: "POST",
            body: JSON.stringify({
                email: 'luz72@example.net',
                'password': 'password'
            })
        })
    }

    fetch('/sanctum/csrf-cookie', {
        headers: {
            'content-type': 'application/json',
            'accept': 'application/json'
        },
        credentials: 'include'
    }).then(() => logout())
    .then(() => {
        return login();
    })
    .then(() => request('/api/v1/users'))

</script>

</body>
</html>