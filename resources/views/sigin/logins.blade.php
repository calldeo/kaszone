<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <!-- BEGIN: Head -->
    <meta charset="utf-8">
    <link href="{{ asset('dashboards/dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>Login - PityCash</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('dashboards/dist/css/app.css') }}" />
    <!-- END: CSS Assets -->
    <!-- END: Head -->
</head>
<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <div class="my-auto">
                    <img alt="Brand Title" class="-intro-x w-1/2 -mt-16" src="{{ asset('dashboards/dist/images/piticash.png') }}" width="150" height="50">
                    <div class="-intro-x text-white font-medium text-1xl leading-tight mt-10">
                        Nothing is impossible. Anything can happen as long as we believe.
                        <br>
                        Sign in to your account.
                    </div>
                </div>
            </div>
            <!-- END: Login Info -->
            
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                        Sign In
                    </h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">
                        A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place.
                    </div>

                    <form class="user" method="post" action="/postlogin">
                        {{ csrf_field() }}
                        <div class="intro-x mt-8">
                            <input type="email" name="email" value="{{ Session::get('email') }}" class="intro-x login__input form-control py-3 px-4 block" placeholder="Email" required>
                            <input type="password" name="password" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Password" required>
                        </div>

                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" id="btn" style="background-color: #EB8153; border: none; color: white;">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>

    <!-- BEGIN: JS Assets-->
    <script src="{{ asset('dashboards/dist/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert -->
    <script>
        // Check for login error message in session
        @if (session('login_error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('login_error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirm: 'swal2-confirm custom-confirm' // Add custom class for the confirm button
                },
                buttonsStyling: false // Disable default button styling
            });

            // Style the confirm button after alert is shown
            setTimeout(() => {
                const confirmButton = document.querySelector('.swal2-confirm');
                if (confirmButton) {
                    confirmButton.style.backgroundColor = '#EB8153'; // Set button background color
                    confirmButton.style.color = 'white'; // Set button text color
                    confirmButton.style.border = 'none'; // Remove border
                    confirmButton.style.borderRadius = '5px'; // Add rounded corners
                    confirmButton.style.padding = '10px 20px'; // Add padding for better appearance
                    confirmButton.style.fontWeight = 'bold'; // Make text bold
                    confirmButton.style.fontSize = '16px'; // Adjust font size
                    confirmButton.style.cursor = 'pointer'; // Change cursor on hover
                    confirmButton.style.boxShadow = 'none'; // Remove any shadow if present
                }
            }, 1); // Small timeout to ensure the button is available in the DOM
        @endif
    </script>
    <!-- END: JS Assets-->
</body>
</html>
