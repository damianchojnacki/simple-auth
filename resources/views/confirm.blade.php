<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ mix("css/style.css") }}"/>
    <link
        rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous"
    />
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirm</title>
</head>
<body>
<div class="wrapper d-flex justify-content-center align-items-center p-3">
    @if($errors->any())
        @error('link')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    @elseif(\Illuminate\Support\Facades\Auth::check())
        <div class="loginBox">
            <div class="text-center pt-3">
                <h3>Dziękujemy za podtwierdzenie konta</h3>
            </div>
            <form class="form" method="post" action="{{ route('auth.delete') }}">
                @csrf
                @method('delete')
                <div id="submit" class="d-flex flex-column justify-content-between align-items-center mt-5">
                    <button type="submit" class="delete">Usuń konto</button>
                </div>
            </form>
        </div>
    @endif
</div>
<script src="{{ mix('js/main.js') }}"></script>
<script>
    document.querySelector("form").addEventListener("submit", (e) => {
        e.preventDefault();

        axios.delete('/api/user', {
            data: {
                id: {{\Illuminate\Support\Facades\Auth::user()->id}},
                hash: "{{\Illuminate\Support\Facades\Auth::user()->hash}}"
            }
        })
            .then(() => {
                document.querySelector(".loginBox").innerHTML = "<p style='padding-top: 1rem'><h2>Account deleted.</h2></p>";
            })
            .catch((error) => {
                const p = document.createElement("p");
                p.style.paddingBottom = "1rem";
                p.style.color = "red";
                p.innerText = error.response.data.message;

                document.querySelector("#submit").prepend(p);

                setTimeout(() => p.style.display = "none", 2000);
            });
    });
</script>
</body>
</html>
