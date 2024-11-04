@include('frontend.layout.header')
<style>
    .back-img {
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100vh;
        z-index: -1;
    }

    .welcome {
        color: #000;
        font-size: 38.03px;
        font-style: normal;
        font-weight: 600;
        /* line-height: 24.05px; */
        /* 66.667% */
    }

    .message {
        color: rgba(143, 143, 143, 1);

        font-size: 19.95px;
        font-style: normal;
        font-weight: 400;
        line-height: 29.06px;
        /* 125% */
        letter-spacing: 2%;
    }

    .login-right {
        height: 100vh;
    }

    @media only screen and (max-width: 768px) {
        .login-right {
            display: none;
            height: 0vh;
        }

        .login-right img {
            display: none;
        }
    }

    @media (min-width: 992px) {

        .container,
        .container-fluid,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl,
        .container-xxl {
            padding: 0 10px !important;
        }
    }

    .btn:not(.btn-outline):not(.btn-dashed):not(.border-hover):not(.border-active):not(.btn-flush):not(.btn-icon) {
        background-color: transparent;
        border: 1px solid;
    }

    .form-control:focus {
        border-color: #38B89A;
        color: #38B89A;
    }

    .pass {
        color: #38B89A;
        font-size: 23.27px;
        font-style: normal;
        font-weight: 500;
        line-height: 29.08px;
        letter-spacing: 0.2%;
        text-align: end;
    }
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes changeColor {
        0% {
            color: red;
        }

        25% {
            color: yellow;
        }

        50% {
            color: green;
        }

        75% {
            color: blue;
        }

        100% {
            color: red;
        }
    }

    .dynamic-color-spinner {
        animation: spin 1s linear infinite, changeColor 2s infinite;
    }
</style>

<body>
    <div class="container-fluid mx-0 px-4">
        <div class="row g-xl-10 ">
            {{-- <div class="col-5 d-flex justify-content-center align-items-center  login-right" style=" background: #ffff;">
                <div class="px-1" style="width:100%; margin-left:0% ">
                    <div style="position: relative; width:100%; padding-bottom:100%">
                        <img src="public/frontend/media/loginform.svg" alt=""
                            class="position-absolute top-0 right-0 bottom-0 left-0  h-100 img-fluid">
                    </div>
                </div>
            </div> --}}
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 d-flex justify-content-center align-items-start  login-right">
                <img src="public/frontend/media/loginform.svg" alt="image" class="img-fluid">
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 d-flex justify-content-center">
                <form class="" style="width: 75% ">
                    <div class="row mt-5">
                        <div style="pb-5">
                            <div class="logo pb-10">
                                <div class=" pt-20 pb-20">
                                    <img src="public/frontend/media/loginLogo.svg" alt="image">
                                </div>
                                <div class="welcome fw-bolder text-dark pt-10 pb-2 ">Forgot Password?</div>
                                <div class="message pt-5">Enter your email address so we can send you OTP to reset your password</div>
                            </div>
                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-success">{{ Session::get('success') }}</div>
                        @endif
                        @if (Session::has('fail'))
                            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                        @endif
                        <!--begin::Input group-->
                        <div class="mb-5">
                            <label class="fs-6 mt-5 form-label fw-bolder text-dark">Email Address</label>
                            <input type="email" class="form-control p-5" name="email" id="email"
                                placeholder="Enter your email address" autofocus>
                            <span class="text-danger" id="emailError">
                            </span>
    
                        </div>
                        <div class="d-flex justify-content-end">
                            <div id="loader" style="display:none;" class="mr-5 me-3">
                                <div class="spinner-border spinner-border dynamic-color-spinner" role="status"></div>
                            </div>
                            <p id="btnOTP" class="pass w-15" style="cursor: pointer;">Send OTP</p>
                        </div>

                        <div class="mb-5 col-12 d-flex flex-row align-items-center justify-content-center" id="otp">

                            <input class="m-4 fs-2x text-center form-control rounded custom-input" type="text"
                                id="num1" maxlength="1" autocomplete="off" />
                            <input class="m-4 fs-2x text-center form-control rounded custom-input" type="text"
                                id="num2" maxlength="1" autocomplete="off" />
                            <input class="m-4 fs-2x text-center form-control rounded custom-input" type="text"
                                id="num3" maxlength="1" autocomplete="off" />
                            <input class="m-4 fs-2x text-center form-control rounded custom-input" type="text"
                                id="num4" maxlength="1" autocomplete="off" />
                        </div>
                        <!--begin::Action-->
                        <div class="d-flex align-items-center mt-10">
                            <button type="submit" class="btn btn-secondary text-light w-100 p-5" id="btnLogin"
                                style="background-color:#38B89A;font-size:20px;">Next</button>
                        </div>
                        <!--end::Action-->
                    </div>
                </form>
            </div>

        </div>


    </div>

    <script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>

    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="module">
        $(document).ready(function() {
            $('#btnLogin').on('click', function(e) {
                e.preventDefault();

                var email = $('#email').val();
                var num1 = $('#num1').val();
                var num2 = $('#num2').val();
                var num3 = $('#num3').val();
                var num4 = $('#num4').val();
                var otp = num1 + num2 + num3 + num4;
                console.log(otp);

                if (email == "") {
                    document.getElementById("emailError").innerText =
                        "Email is required";
                } else {
                    if (email != "") {
                        document.getElementById("emailError").innerText =
                            "";
                    }


                    $.ajax({
                        url: 'verifyOTP',
                        type: 'GET',
                        data: {
                            email: email,
                            otp: otp,
                        },
                        success: function(response) {

                            console.log(response);
                            var data = $.parseJSON(response);

                            if (data.response == "success") {

                                localStorage.setItem('myData', data.email);
                                window.location.href = 'resetPassword';
                            } else if (data.response == "error") {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Invalid OTP',
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            }
                        }

                    });
                }
            })

            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('keydown', function(event) {
                        if (event.key === "Backspace") {
                            inputs[i].value = '';
                            if (i !== 0) inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if (event.keyCode > 47 && event.keyCode < 58) {
                                inputs[i].value = event.key;
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            } else if (event.keyCode > 64 && event.keyCode < 91) {
                                inputs[i].value = String.fromCharCode(event.keyCode);
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            }
                        }
                    });
                }
            }
            OTPInput();

            $('#btnOTP').on('click', function(e) {
                e.preventDefault();

                var email = $('#email').val();

                if (email == "") {
                    document.getElementById("emailError").innerText =
                        "Email is required";
                } else {
                    if (email != "") {
                        document.getElementById("emailError").innerText =
                            "";
                    }
                    $("#loader").show();

                    $.ajax({
                        url: 'fogetPasswordOTP',
                        type: 'GET',
                        data: {
                            email: email,
                        },
                        success: function(response) {
                            console.log(response);
                            var data = $.parseJSON(response);
                            console.log(data);
                            if (data.response == "success") {
                                $("#loader").hide();

                                Swal.fire({
                                    title: 'Success',
                                    text: 'OTP Sent Successfully!',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })

                            } else if (data.response == "error") {
                                $("#loader").hide();

                                Swal.fire({
                                    title: 'Error',
                                    text: 'Email not founded in Database',
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                            }
                        }

                    });
                }

            })
        });
    </script>
</body>

</html>
