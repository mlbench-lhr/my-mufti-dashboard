@extends('frontend.layout.master')
<style>
    .loader {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 4px solid #38B89A;
        width: 40px;
        height: 40px;
        animation: spin 0.5s linear infinite;
        position: fixed;
        top: 57%;
        left: 59%;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }


    }

    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
        /* Adjust the max-width based on your requirements */
    }

    /* Change the checked color of the radio button */
    .form-check-input:checked {
        background-color: #38B89A !important;
        color: #38B89A !important;
        border-color: #38B89A !important;
        box-shadow: 0 0 5px rgba(56, 184, 154, 0.6) !important;
    }
</style>
@section('content')
    <!--begin::Header-->
    <div id="kt_header" class="header">
        <!--begin::Container-->
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <!--begin::Wrapper-->
            <div class="d-flex d-lg-none align-items-center ms-n2 me-2">
                <!--begin::Aside mobile toggle-->
                <div class="btn btn-icon btn-active-icon-primary" id="kt_aside_toggle">
                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                    <span class="svg-icon svg-icon-1 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                fill="black" />
                            <path opacity="0.3"
                                d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Aside mobile toggle-->
                <!--begin::Logo-->
                <a href="" class="d-flex align-items-center">
                    <img alt="Logo" src="{{ '../../public/frontend/media/sidebarLogo.svg' }}" class="h-20px" />
                </a>
                <!--end::Logo-->
            </div>

            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-2 pb-lg-0"
                data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="d-flex flex-column text-dark fw-bold my-0 fs-1">All Public Questions
                </h1>
                <h3 class="mt-4" style=" font-weight:400; ">Total posted questions: <span class="fs-5" id="user-count"
                        style="font-weight:500 "> </span> </h3>
                <!--end::Heading-->
            </div>
            <div class="d-flex gap-2">
                <div class="d-flex align-items-center position-relative ">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" id="global-search" class="form-control form-control-solid w-250px ps-14"
                        placeholder="Search" />
                </div>

                <div class="dropdown">
                    <button class="btn" style="background-color: #38B89A; color: #FFFFFF" type="button" id=""
                        data-bs-toggle="modal" data-bs-target="#kt_modal_add_interests" aria-expanded="false">
                        Add Question
                    </button>
                </div>
                <!--end::Page title=-->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-fluid" id="">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body pt-0">

                    <div class="d-flex overflow-auto h-55px">
                        <ul
                            class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-3 fw-bolder flex-nowrap">
                            <!--begin::Nav item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('PublicQuestions') ? 'active' : null }}"
                                    href="{{ URL::to('PublicQuestions') }}">Added by You</a>
                            </li>
                            <!--end::Nav item-->
                            <!--begin::Nav item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-success me-6 {{ Request::is('PublicQuestions/Scholar') ? 'active' : null }}"
                                    href="{{ URL::to('PublicQuestions/Scholar') }}">Added by Scholars/Life Coach</a>
                            </li>
                            <!--end::Nav item-->
                        </ul>
                    </div>

                    @if (count($questions))
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-dark fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-275px">Question</th>
                                        <th class="min-w-175px">Question Category</th>
                                        <th class="min-w-150px">Voting Option</th>
                                        <th class="min-w-150px">Added On</th>
                                        <th class="min-w-125px">Action</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="text-gray-600 fw-bold" id="verification-table-body">
                                    {{-- ajax data is appending here  --}}
                                    <div id="loader" class="loader"></div>

                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                        <div class="pagination d-flex justify-content-end" id="pagination-links"></div>
                    @else
                        <div class="text-center my-19">
                            <img alt="Logo" style="align-items: center; margin-top:50px"
                                src="{{ url('public/frontend/media/NoPrivateQus.svg') }}" class="img-fluid ">
                        </div>
                    @endif
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        <!-- Modal- Add-->
        <div class="modal fade" id="kt_modal_add_interests" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog mw-500px">
                <div class="modal-content">
                    <div class="modal-header pb-0 border-0 d-flex justify-content-between items-center">
                        <p>
                        </p>
                        <p class="fs-1 fw-bold">Add Public Question</p>
                        <button type="button" class="btn btn-lg btn-icon btn-active-color-dark" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1 w-50">
                                <svg width="55" height="55" viewBox="0 0 33 33" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M33.3307 20.0013C33.3307 27.3651 27.3612 33.3346 19.9974 33.3346C12.6336 33.3346 6.66406 27.3651 6.66406 20.0013C6.66406 12.6375 12.6336 6.66797 19.9974 6.66797C27.3612 6.66797 33.3307 12.6375 33.3307 20.0013ZM15.9569 15.9608C16.3474 15.5703 16.9806 15.5703 17.3711 15.9608L19.9974 18.5871L22.6236 15.9609C23.0141 15.5703 23.6473 15.5703 24.0378 15.9609C24.4283 16.3514 24.4283 16.9846 24.0378 17.3751L21.4116 20.0013L24.0378 22.6275C24.4283 23.018 24.4283 23.6512 24.0378 24.0417C23.6472 24.4322 23.0141 24.4322 22.6235 24.0417L19.9974 21.4155L17.3711 24.0417C16.9806 24.4322 16.3475 24.4322 15.9569 24.0417C15.5664 23.6512 15.5664 23.018 15.9569 22.6275L18.5831 20.0013L15.9569 17.375C15.5664 16.9845 15.5664 16.3514 15.9569 15.9608Z"
                                        fill="#303030" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    <div class="modal-body pt-4">
                        <form id="postForm" action="/submitPublicQuestion" method="post" class="form">
                            @method('POST')
                            @csrf
                            <div class="mb-5">
                                <label for="question_category" class="fw-bold fs-6 pb-2 mb-1">Question Category</label>
                                <select onchange="changeFunc2();" id="categoryBox" class="form-select cursor-pointer"
                                    name="question_category" required>
                                    <option value="" disabled selected>-- Select Category--</option>
                                    <option value="Family Law">Family Law</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Home Finance">Home Finance</option>
                                    <option value="Food">Food</option>
                                    <option value="Marriage">Marriage</option>
                                    <option value="Relationship">Relationship</option>
                                    <option value="Dhikr">Dhikr</option>
                                    <option value="Duas">Duas</option>
                                    <option value="Raising Kids">Raising Kids</option>
                                    <option value="Parents">Parents</option>
                                    <option value="Salah">Salah</option>
                                    <option value="Dawah">Dawah</option>
                                    <option value="Competitive Religion">Competitive Religion</option>
                                    <option value="Quran">Quran</option>
                                    <option value="Hadith">Hadith</option>
                                    <option value="Others">Others</option>
                                </select>

                                <br>
                                <div id="categoryContainer" class="d-flex flex-wrap"
                                    style="height: auto; overflow-y: hidden; overflow-x: hidden;">
                                </div>

                            </div>
                            <div class="mb-5">
                                <label for="question" class="fw-bold fs-6 pb-2 mb-1">Question</label>
                                <textarea id="question" class="form-control" name="question" placeholder="Write what you want to ask"
                                    rows="4" oninput="validateInput(this)" required></textarea>
                            </div>
                            <div class="mb-5">
                                <label for="voting_option" class="fw-bold fs-6 pb-2 mb-2">Select Voting Option</label>
                                <div class="flex-row items-center gap-5">
                                    <span class="me-10">
                                        <input class="form-check-input cursor-pointer" type="radio"
                                            name="voting_option" id="yes_no" value="1" required>
                                        <label class="" for="yes_no">Yes, No</label>
                                    </span>
                                    <span class="">
                                        <input class="form-check-input cursor-pointer" type="radio"
                                            name="voting_option" id="true_false" value="2" required>
                                        <label class="" for="true_false">True, False</label>
                                    </span>
                                </div>

                            </div>

                            <input type="hidden" name="question_categories" id="category_box">

                            <div class="d-flex justify-content-center pt-2">
                                <button type="submit" class="btn btn-lg col-12"
                                    style="background-color: #38B89A; color: #FFFFFF">
                                    Add Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end::Content-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let mealTimeArray = [];
        let selectedObjectId2 = null;

        function changeFunc2() {
            var selectBoxes1 = document.getElementById("categoryBox");
            var selectedValue2 = selectBoxes1.options[selectBoxes1.selectedIndex].value;
            var existingObject2 = mealTimeArray.find(obj => obj.id === selectedValue2);
            if (existingObject2) {
                selectedObjectId2 = existingObject2.id;
            } else {
                selectedObjectId2 = null;
                var selectedText2 = $('#categoryBox option:selected').text();
                let myObject2 = {
                    name: selectedText2,
                    id: selectedValue2,
                };
                mealTimeArray.push(myObject2);
            }

            displayObjects2();
        }

        function removeItem2(id) {
            mealTimeArray = mealTimeArray.filter(obj1 => obj1.id !== id);
            $(document).ready(function() {
                $('#categoryBox').prop('selectedIndex', "");
            });
            displayObjects2();
        }

        function displayObjects2() {
            var container2 = document.getElementById('categoryContainer');
            container2.innerHTML = '';

            mealTimeArray.forEach(function(obj1) {
                var div2 = document.createElement('span');
                div2.innerHTML = `
                    <span class="d-flex align-items-center justify-content-start px-3 py-2 bg-white rounded-1 shadow-sm me-2 mb-2" style="width: fit-content;">
                        <span class="fs-5 text-gray-900 me-2">${obj1.name}</span>
                        <span style="cursor:pointer;" onclick="removeItem2('${obj1.id}')" class="text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 27 27" fill="none">
                                <path d="M17.1531 9.90137L9.43948 17.6149" stroke="#38B89A" stroke-width="2" stroke-linecap="round" />
                                <path d="M17.1531 17.6152L9.43948 9.90166" stroke="#38B89A" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                    </span>`;
                container2.appendChild(div2);
            });
        }

        function updateFormInput2() {
            document.getElementById("category_box").value = JSON.stringify(mealTimeArray);
        }

        // document.getElementById("postForm").addEventListener("submit", function(event) {
        //     event.preventDefault();
        //     updateFormInput2();
        //     this.submit();
        // });

        document.getElementById("postForm").addEventListener("submit", function(event) {
            event.preventDefault();
            updateFormInput2();

            const formData = new FormData(this);

            fetch(this.action, {
                    method: this.method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        title: "Success!",
                        text: "Your question has been submitted successfully.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '/PublicQuestions';
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "Error!",
                        text: "There was an error submitting your question. Please try again.",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
        });

        function validateInput(input) {
            input.value = input.value.replace(/^\s*/, '');
        }
    </script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var currentPage = 1;

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    function loadVerificationData(page, search = '', sortingOption = '') {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: '{{ route('getPublicQuestions') }}?page=' + page + '&search=' + encodeURIComponent(search) +
                '&sorting=' +
                sortingOption,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var users = response.users;

                var tableBody = $('#verification-table-body');
                var userCount = response.userCount;
                $('#user-count').text(userCount);

                tableBody.empty();

                if (users.data.length === 0) {
                    var noUserRow = `
            <tr>
                <td colspan="6" class="text-center pt-10 fw-bolder fs-2">No Questions found</td>
            </tr>
        `;
                    tableBody.append(noUserRow);
                } else {
                    var count = 0;
                    console.log(users.data);
                    $.each(users.data, function(index, row) {

                        var categoryName = row.question_category.map(function(categoryname) {
                            return categoryname;
                        });

                        var category = categoryName.slice(0, 3).join(
                            ', ');
                        if (categoryName.length > 3) {
                            category += ' ...';
                        }

                        var newRow = `
                    <tr class="text-start">
                        <td>${truncateText(row.question, 14)}</td>
                        <td>${category}</td>
                        <td>
                                ${row.voting_option == 1 ?
                                    `Yes, No` :
                                    `True, False`}
                        </td>
                        <td>${row.registration_date}</td>
                        <td>
                            <div class="fs-4 fw-bolder text-dark">
                                <a href="{{ URL::to('PublicQuestionDetail') }}/${row.id}?flag=1" class="link-success fw-bold">
                                    View Detail
                                </a>
                            </div>
                        </td>

                    </tr>
                `;
                        tableBody.append(newRow);
                        count++;
                    });
                }

                var paginationLinks = $('#pagination-links');
                paginationLinks.empty();

                var totalPages = users.last_page;
                var currentPage = users.current_page;


                var previousLink = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
                </li>`;
                paginationLinks.append(previousLink);

                for (var i = 1; i <= totalPages; i++) {
                    if (totalPages > 7 && (i < currentPage - 2 || i > currentPage + 2)) {
                        if (i === 1 || i === totalPages) {
                            var pageLink =
                                `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                            paginationLinks.append(pageLink);
                        }
                        continue;
                    }

                    var pageLink = `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}" style="background-color: #38B89A;">${i}</a>
                      </li>`;
                    paginationLinks.append(pageLink);
                }

                var nextLink = `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
                </li>`;
                paginationLinks.append(nextLink);


                $('#loader').addClass('d-none');

            },
        });
    }


    function truncateText(text, wordLimit) {
        const words = text.split(" ");
        return words.length > wordLimit ? words.slice(0, wordLimit).join(" ") + " ..." : text;
    }
    // Handle page clicks
    // $(document).on('click', '.page-link', function(e) {
    //     e.preventDefault();
    //     currentPage = $(this).data('page');
    //     var searchTerm = $('#global-search').val();
    //     loadVerificationData(currentPage, searchTerm);
    // });
    // $(document).on('click', '#apply-filter-button', function(e) {
    //     e.preventDefault();
    //     var searchTerm = $('#global-search').val();
    //     var sortingOption = $('#request-date-filter').val();
    //     loadVerificationData(currentPage, searchTerm, sortingOption);
    // });
    // $(document).on('click', '#reset-filter-button', function(e) {
    //     e.preventDefault();
    //     $('#request-date-filter').val('').trigger('change');
    //     loadVerificationData(currentPage);
    // });
    // $(document).ready(function() {
    //     // Handle global search input
    //     $('#global-search').on('input', function() {
    //         var searchTerm = $(this).val();
    //         loadVerificationData(1, searchTerm);
    //     });
    // });
    // $(document).ready(function() {
    //     loadVerificationData(currentPage);
    // });

    function updateUrlParameter(key, value) {
        var url = new URL(window.location.href);
        url.searchParams.set(key, value);
        window.history.pushState({
            path: url.href
        }, '', url.href);
    }
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        if (page && page !== currentPage) {
            currentPage = page;
            var searchTerm = $('#global-search').val();
            loadVerificationData(currentPage, searchTerm);
            updateUrlParameter('page', currentPage);
        }
    });
    $(document).on('click', '#apply-filter-button', function(e) {
        e.preventDefault();
        var searchTerm = $('#global-search').val();
        var sortingOption = $('#request-date-filter').val();
        loadVerificationData(currentPage, searchTerm, sortingOption);
    });
    $(document).on('click', '#reset-filter-button', function(e) {
        e.preventDefault();
        $('#request-date-filter').val('').trigger('change');
        loadVerificationData(currentPage);
    });
    $(document).ready(function() {
        $('#global-search').on('input', function() {
            var searchTerm = $(this).val();
            currentPage = 1;
            loadVerificationData(currentPage, searchTerm);
            updateUrlParameter('page', currentPage);
        });

        currentPage = getUrlParameter('page') || 1;
        loadVerificationData(currentPage);
    });
</script>
