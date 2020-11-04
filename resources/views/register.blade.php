<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="{{ mix("css/style.css") }}"/>
    <link
        rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
</head>
<body>
<div class="wrapper d-flex justify-content-center align-items-center p-3">
    <div class="loginBox">
        <div class="text-center pt-3">
            <h3>Zarejestruj się</h3>
        </div>
        <form class="form" method="post" action="{{ route('auth.register') }}">
            @csrf
            <label>Email</label>
            <input
                id="email"
                type="text"
                name="email"
                text="Wpisz adres e-mail"
                additional="required"
            />
            <label>Password</label>
            <input
                id="password"
                type="text"
                name="password"
                text="Wpisz hasło"
                additional="required"
            />

            <div id="submit" class="d-flex flex-column justify-content-between align-items-center mt-5">
                <button type="submit" class="submit">Dalej</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ mix('js/main.js') }}"></script>
<script>
    let emailValue;
    let passwordValue;

    document.getElementById("email").addEventListener("change", (e) => {
        emailValue = e.target.value;
    });
    document.getElementById("password").addEventListener("change", (e) => {
        passwordValue = e.target.value;
    });
    document.querySelector("form").addEventListener("submit", (e) => {
        e.preventDefault();

        axios.post('/api/register', {
            email: emailValue,
            password: passwordValue
        })
        .then(() => {
            document.querySelector("form").innerHTML = "<p style='padding-top: 1rem'>Please confirm your email. If can't find any message, please check spam folder.</p>";
        })
        .catch((error) => {
            const p = document.createElement("p");
            p.style.paddingBottom = "1rem";
            p.style.color = "red";
            p.innerText = error.response.data.message.email;

            document.querySelector("#submit").prepend(p);

            setTimeout(() => p.style.display = "none", 2000);
        })
    });
</script>
</body>
</html>
