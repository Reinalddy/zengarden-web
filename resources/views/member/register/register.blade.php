<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>ZenGarden - Regoster</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9" style="margin-top: 20%;">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Register To Your UCP</h1>
                                    </div>
                                    <form class="user" id="form-register">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="ucp" aria-describedby="ucp"
                                                placeholder="Enter ucp name">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="pin" aria-describedby="pin"
                                                placeholder="Enter pin">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" placeholder="Password">
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block">Register</button>
                                        <hr>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ url('/') }}">Alredy Haven An account ?, Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>


    <script>
        
        $(document).ready(function () {
            // csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // register
            $('#form-register').on('submit', function (e) {
                e.preventDefault();
                var ucp = $('#ucp').val();
                var pin = $('#pin').val();
                var password = $('#password').val();

                $.ajax({
                    type: "POST",
                    url: "{{ url('/register') }}",
                    data: {
                        ucp: ucp,
                        pin: pin,
                        password: password
                    },
                    success: function (response) {
                        if (response.code == 200) {
                            window.location.href = "{{ url('/') }}";
                        } else if (response.code == 422) {
                            $.each(response.data, function (key, value) {
                                // find element
                                $('#' + key).addClass('is-invalid');
                                $('#' + key).next('.invalid-feedback').html(value);
                            })
                        }
                    }
                });


            
            })
        })
    </script>

</body>

</html>