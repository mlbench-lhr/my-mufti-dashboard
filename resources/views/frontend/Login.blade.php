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
        line-height: 24.05px;
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

    @media only screen and (max-width: 1400px) {
        .login-right {
            height: 130vh;
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
</style>

<body>
    <div class="container-fluid mx-0 px-4">
        <div class="row ">
            <div class="col-5 d-flex justify-content-center align-items-center  login-right" style=" background: #ffff;">
                <div class="px-1" style="width:100%; margin-left:0% ">
                    <div style="position: relative; width:100%; padding-bottom:100%">
                        <img src="public/frontend/media/loginform.svg" alt=""
                            class="position-absolute top-0 right-0 bottom-0 left-0  h-100 img-fluid">
                    </div>
                </div>
            </div>
            <div class="col-7 d-flex">
                <form class="ms-10" style="width: 75% ">
                    <div class="row mt-5  p-4">
                        <div style="pb-5">
                            <div class="logo pb-10">
                                <div class=" pt-20 pb-20">
                                    <img src="public/frontend/media/loginLogo.svg" alt="">
                                </div>
                                <div class="welcome fw-bolder text-dark pt-10 pb-2 ">Sign In to your account</div>
                                <div class="message pt-5">Sign in With your email and password and continue to
                                    CompaignBox</div>
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
                        <div class="col-12 mb-5 d-flex flex-row" style="position:relative;">
                            <span class="col-12">
                                <label class="fs-6 form-label fw-bolder text-dark">Password</label>
                                <input type="password" class="form-control form-control p-5" name="password"
                                    id="newPassword" placeholder="Enter password" value="{{ old('password') }}">
                                <span class="text-danger" id="passwordError">

                                </span>

                            </span>
                            <span style="position: sticky; margin-top: 41px;margin-left: -45px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    viewBox="0 0 30 30" fill="none" id="togglePassword" style="cursor: pointer;">
                                    <path
                                        d="M12.75 15C12.75 13.7574 13.7574 12.75 15 12.75C16.2426 12.75 17.25 13.7574 17.25 15C17.25 16.2426 16.2426 17.25 15 17.25C13.7574 17.25 12.75 16.2426 12.75 15Z"
                                        fill="#787F89" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5 15C5 16.6394 5.42496 17.1915 6.27489 18.2957C7.97196 20.5004 10.8181 23 15 23C19.1819 23 22.028 20.5004 23.7251 18.2957C24.575 17.1915 25 16.6394 25 15C25 13.3606 24.575 12.8085 23.7251 11.7043C22.028 9.49956 19.1819 7 15 7C10.8181 7 7.97196 9.49956 6.27489 11.7043C5.42496 12.8085 5 13.3606 5 15ZM15 11.25C12.9289 11.25 11.25 12.9289 11.25 15C11.25 17.0711 12.9289 18.75 15 18.75C17.0711 18.75 18.75 17.0711 18.75 15C18.75 12.9289 17.0711 11.25 15 11.25Z"
                                        fill="#787F89" />
                                </svg>


                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="31"
                                    viewBox="0 0 30 31" fill="none" id="togglePassword2" style="cursor: pointer;">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.6061 9.36871C5.11373 9.15115 5.7016 9.3863 5.91916 9.89393L5.00001 10.2879C5.91916 9.89393 5.9193 9.89426 5.91916 9.89393L5.91864 9.89273C5.91836 9.89209 5.91857 9.89256 5.91864 9.89273L5.92256 9.9016C5.92657 9.91061 5.93345 9.92593 5.94325 9.94714C5.96284 9.98956 5.99403 10.0554 6.03702 10.1414C6.12308 10.3135 6.256 10.5653 6.43743 10.8701C6.80143 11.4816 7.35445 12.2961 8.10781 13.1074C8.28538 13.2986 8.4733 13.4889 8.67179 13.6759C8.68009 13.6834 8.68829 13.6911 8.6964 13.699C10.1811 15.0893 12.2523 16.2879 15 16.2879C16.209 16.2879 17.2784 16.057 18.2209 15.686C19.447 15.2035 20.4746 14.477 21.3156 13.6876C22.2653 12.7962 22.9628 11.8379 23.4233 11.0988C23.6526 10.7308 23.8207 10.421 23.9299 10.2069C23.9845 10.1 24.0241 10.0173 24.0492 9.96346C24.0617 9.93656 24.0706 9.91692 24.0759 9.90513L24.0809 9.89393C24.2986 9.38658 24.8864 9.15121 25.3939 9.36871C25.9016 9.58626 26.1367 10.1741 25.9192 10.6818L25 10.2879C25.9192 10.6818 25.9193 10.6815 25.9192 10.6818L25.917 10.6868L25.9134 10.695L25.902 10.7209C25.8925 10.7422 25.8791 10.7716 25.8619 10.8086C25.8274 10.8824 25.7775 10.9864 25.7116 11.1156C25.5799 11.3737 25.384 11.7339 25.1207 12.1565C24.7181 12.8027 24.1521 13.6041 23.4097 14.4121L24.2071 15.2096C24.5976 15.6001 24.5976 16.2333 24.2071 16.6238C23.8166 17.0143 23.1834 17.0143 22.7929 16.6238L21.9527 15.7836C21.3885 16.2394 20.757 16.6692 20.0558 17.04L20.8382 18.2425C21.1394 18.7054 21.0083 19.3248 20.5454 19.626C20.0825 19.9272 19.463 19.7962 19.1618 19.3332L18.1764 17.8187C17.4974 18.0269 16.772 18.1744 16 18.2435V19.7879C16 20.3401 15.5523 20.7879 15 20.7879C14.4477 20.7879 14 20.3401 14 19.7879V18.2437C13.2254 18.1745 12.5002 18.0267 11.8234 17.8191L10.8382 19.3332C10.537 19.7962 9.91755 19.9272 9.45463 19.626C8.99171 19.3248 8.86062 18.7054 9.16183 18.2425L9.94423 17.04C9.24411 16.6695 8.61252 16.2396 8.04752 15.7834L7.20712 16.6238C6.8166 17.0143 6.18343 17.0143 5.79291 16.6238C5.40238 16.2333 5.40238 15.6001 5.79291 15.2096L6.59035 14.4121C5.74536 13.4924 5.12778 12.5801 4.71885 11.8931C4.50966 11.5416 4.35351 11.2465 4.24817 11.0358C4.19546 10.9304 4.15535 10.8459 4.12758 10.7858C4.11369 10.7557 4.10288 10.7317 4.09511 10.7143L4.08572 10.693L4.08273 10.6861L4.08166 10.6836L4.08123 10.6826C4.08104 10.6822 4.08087 10.6818 5.00001 10.2879L4.08123 10.6826C3.86367 10.175 4.09847 9.58626 4.6061 9.36871Z"
                                        fill="#7B849A" />
                                </svg>
                            </span>

                        </div>
                        <div>
                            <a href="{{ URL::to('/fogetPassword') }}">
                                <p class="pass">Forget Password?</p>
                            </a>
                        </div>
                        <!--begin::Action-->
                        <div class="d-flex align-items-center mt-10">
                            <button type="submit" class="btn btn-secondary text-light w-100 p-5" id="btnLogin"
                                style="background-color:#38B89A;font-size:20px;">Sign in</button>
                        </div>

                        <!--end::Action-->
                    </div>
                </form>
            </div>

        </div>


    </div>
    </div>
    <script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>

    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $('#togglePassword').hide();


        const togglePassword2 = document
            .querySelector('#togglePassword2');
        const password2 = document.querySelector('#newPassword');

        togglePassword2.addEventListener('click', () => {

            const type2 = password2
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            password2.setAttribute('type', type2);
            $('#togglePassword2').hide();
            $('#togglePassword').show();
        });

        const togglePassword = document
            .querySelector('#togglePassword');
        const password = document.querySelector('#newPassword');

        togglePassword.addEventListener('click', () => {

            const type = password
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            password.setAttribute('type', type);
            $('#togglePassword2').show();
            $('#togglePassword').hide();
        });
    </script>

    <script type="module">
        $(document).ready(function() {
            $('#btnLogin').on('click', function(e) {
                e.preventDefault();

                var email = $('#email').val();
                var password = $('#newPassword').val();
                $('#btnLogin').attr('disabled', 'disabled')

                if (email == "" || password == "") {


                    if (email == "") {
                        document.getElementById("emailError").innerText =
                            "Email is required";
                    } else {
                        document.getElementById("emailError").innerText =
                            "";
                    }
                    if (password == "") {
                        document.getElementById("passwordError").innerText =
                            "Password is required";
                    }
                    if (password != "") {
                        document.getElementById("passwordError").innerText =
                            "";
                    }

                    $("#btnLogin").removeAttr('disabled');

                } else {
                    if (password != "") {
                        document.getElementById("passwordError").innerText =
                            "";
                    }
                    if (email != "") {
                        document.getElementById("emailError").innerText =
                            "";
                    }

                    $.ajax({
                        url: 'loginn',
                        type: 'POST',
                        data: {
                            email: email,
                            password: password,
                        },
                        success: function(response) {
                            var data = $.parseJSON(response);

                            if (data.response == "success") {
                                window.location.href = 'Dashboard'
                            } else if (data.response == "success1") {
                                window.location.href = 'FitmeRecipe'
                            } else if (data.response == "error") {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Invalid Email & Password.',
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false,
                                })
                                $("#btnLogin").removeAttr('disabled');
                            }
                        }

                    });
                }
            })
        });
    </script>

</body>

</html>
