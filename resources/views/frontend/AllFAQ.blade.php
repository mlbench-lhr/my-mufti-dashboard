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
    .add-faq-btn {
    background-color: #38B89A !important;
    color: #FFFFFF !important;
    width: 139px;
    height: 40px;
    border-radius: 8.45px;
    font-size: 15px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 38px;
    border: none;
}

</style>
@section('content')
    <!--begin::Header-->
    <div id="kt_header" class="header" >
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
                <h1 class="d-flex flex-column text-dark fw-bold my-0 fs-1">All FAQ's
                </h1>
                <h3 class="mt-4" style=" font-weight:400; color:#78827F">Total FAQ's: <span class="fs-5" id="faq-count"
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
                        placeholder="Search" style="border: 1px solid #7B849A;height: 38px; border-radius: 8px;padding: 4px 20px" />
                </div>

                <div class="dropdown">
                    <button class="btn add-faq-btn" type="button"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_add_interests">
                        Add FAQ
                    </button>
                </div>
                <!--end::Page title=-->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" >
    <!--begin::Container-->
    <div class="container-fluid" id="">
        <!--begin::Card-->
        <div class="card" >
            <!--begin::Card body-->
            <div class="card-body pt-0">
                @if (count($faqs))
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="faq_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-dark fw-bold fs-5  gs-0">
                                    <th class="min-w-75px text-center" style="font-size: 20px;">Sr No</th>
                                    <th class="min-w-250px" style="font-size: 20px;">Question</th>
                                    <th class="min-w-175px text-center" style="font-size: 20px;">Published On</th>
                                    <th class="min-w-150px text-center" style="font-size: 20px;">Action</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-bold" id="faq-table-body">
                                {{-- FAQs will be loaded via AJAX here --}}
                                <div id="loader" class="loader"></div>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <!--end::Table-->
                    <div class="pagination d-flex justify-content-end" id="pagination-links"></div>
                @else
                    <div class="text-center my-19">
                        <img alt="No FAQs" style="align-items: center; margin-top:50px"
                            src="{{ url('../../public/frontend/media/NoFAQ.svg') }}" class="img-fluid"> <!--add::public-->
                    </div>
                @endif
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->

    <!-- Modal - Add FAQ -->
    <div class="modal fade" id="kt_modal_add_interests" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mw-500px">
            <div class="modal-content">
                <div class="modal-header pb-0 border-0 d-flex justify-content-between items-center">
                    <p></p>
                    <p class="fs-1 fw-bold">Add FAQ</p>
                    <button type="button" class="btn btn-lg btn-icon btn-active-color-dark" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1 w-50">
                            <svg width="55" height="55" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M33.3307 20.0013C33.3307 27.3651 27.3612 33.3346 19.9974 33.3346C12.6336 33.3346 6.66406 27.3651 6.66406 20.0013C6.66406 12.6375 12.6336 6.66797 19.9974 6.66797C27.3612 6.66797 33.3307 12.6375 33.3307 20.0013ZM15.9569 15.9608C16.3474 15.5703 16.9806 15.5703 17.3711 15.9608L19.9974 18.5871L22.6236 15.9609C23.0141 15.5703 23.6473 15.5703 24.0378 15.9609C24.4283 16.3514 24.4283 16.9846 24.0378 17.3751L21.4116 20.0013L24.0378 22.6275C24.4283 23.018 24.4283 23.6512 24.0378 24.0417C23.6472 24.4322 23.0141 24.4322 22.6235 24.0417L19.9974 21.4155L17.3711 24.0417C16.9806 24.4322 16.3475 24.4322 15.9569 24.0417C15.5664 23.6512 15.5664 23.018 15.9569 22.6275L18.5831 20.0013L15.9569 17.375C15.5664 16.9845 15.5664 16.3514 15.9569 15.9608Z"
                                    fill="#303030" />
                            </svg>
                        </span>
                    </button>
                </div>
                <div class="modal-body pt-4">
                    <form id="postForm" action="/submitFAQ" method="post" class="form">
                        @method('POST')
                        @csrf
                        <div class="mb-5">
                            <label for="faq_question" class="fw-bold fs-3 pb-2 mb-1">Question</label>
                            <textarea id="faq_question" class="form-control" name="question" placeholder="Add Question"
                                rows="1" required oninput="this.value = this.value.replace(/^\s+/g, '')"></textarea>
                        </div>
                        <div class="mb-5">
                            <label for="faq_answer" class="fw-bold fs-3 pb-2 mb-1">Answer</label>
                            <textarea id="faq_answer" class="form-control" name="answer" placeholder="Add Answer"
                                rows="4" required oninput="this.value = this.value.replace(/^\s+/g, '')"></textarea>
                        </div>
                        <div class="d-flex justify-content-center pt-2">
                            <button id="submit-button" type="submit" class="btn btn-lg col-12"
                                style="background-color: #38B89A; color: #FFFFFF">
                                Add
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
    document.getElementById("postForm").addEventListener("submit", function(event) {
        event.preventDefault();
        let submitButton = document.querySelector("#submit-button"); 
        submitButton.disabled = true; 
        submitButton.textContent = "Processing...";

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
                    text: "Your FAQ has been submitted successfully.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '/AllFAQs'; 
                });
            })
            .catch(error => {
                Swal.fire({
                    title: "Error!",
                    text: "There was an error submitting your FAQ. Please try again.",
                    icon: "error",
                    showConfirmButton: false,
                    timer: 1500
                });
                submitButton.disabled = false; 
                submitButton.textContent = "Submit FAQ";
            });
    });

    function validateFaqInput(input) {
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

    function loadFaqData(page, search = '', sortingOption = '') {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: '{{ route('getAllFAQs') }}?page=' + page + '&search=' + encodeURIComponent(search) + '&sorting=' + sortingOption,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var faqs = response.faqs;
                var tableBody = $('#faq-table-body');
                var faqCount = response.faqCount;
                $('#faq-count').text(faqCount);

                tableBody.empty();

                if (faqs.data.length === 0) {
                    var noFaqRow = `
                        <tr>
                            <td colspan="6" class="text-center pt-10 fw-bolder fs-2">No FAQs found</td>
                        </tr>`;
                    tableBody.append(noFaqRow);
                } else {
                    var count = (faqs.data.length > 0) ? (faqs.current_page - 1) * faqs.per_page : 0;
                    $.each(faqs.data, function(index, row) {
                        var modifiedSerialNumber = pad(count + 1, 2, '0');
                        var newRow = `
                            <tr class="text-start" style="border-color: #DBDFE9;">
                                <td class="text-center" style="font-size: 17px;">${modifiedSerialNumber}</td>
                                <td class="fw-bold text-dark" style="font-size: 19px;">${truncateText(row.question)}</td>
                                <td class="text-center" style="font-size: 17px;">${row.created_at}</td>
                                <td class="text-center">
                                    <div class="fs-4 fw-bolder text-dark">
                                        <a href="{{ URL::to('AllFAQDetail') }}/${row.id}" class="link-success fw-bold"  style="font-size: 15px;">
                                            View Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
                        tableBody.append(newRow);
                        count++;
                    });
                    // Function to pad numbers with zeros
                    function pad(number, length, character) {
                        var str = '' + number;
                        while (str.length < length) {
                            str = character + str;
                        }
                        return str;
                    }
                }

                var paginationLinks = $('#pagination-links');
                paginationLinks.empty();

                var totalPages = faqs.last_page;
                var currentPage = faqs.current_page;

                var previousLink = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
                </li>`;
                paginationLinks.append(previousLink);

                for (var i = 1; i <= totalPages; i++) {
                    if (totalPages > 7 && (i < currentPage - 2 || i > currentPage + 2)) {
                        if (i === 1 || i === totalPages) {
                            var pageLink = `<li class="page-item disabled"><span class="page-link">...</span></li>`;
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

    function updateUrlParameter(key, value) {
        var url = new URL(window.location.href);
        url.searchParams.set(key, value);
        window.history.pushState({ path: url.href }, '', url.href);
    }

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        if (page && page !== currentPage) {
            currentPage = page;
            var searchTerm = $('#global-search').val();
            loadFaqData(currentPage, searchTerm);
            updateUrlParameter('page', currentPage);
        }
    });

    $(document).on('click', '#apply-filter-faq', function(e) {
        e.preventDefault();
        var searchTerm = $('#global-search').val();
        var sortingOption = $('#faq-date-filter').val();
        loadFaqData(currentPage, searchTerm, sortingOption);
    });

    $(document).on('click', '#reset-filter-faq', function(e) {
        e.preventDefault();
        $('#faq-date-filter').val('').trigger('change');
        loadFaqData(currentPage);
    });

    $(document).ready(function() {
        $('#global-search').on('input', function() {
            var searchTerm = $(this).val();
            currentPage = 1;
            loadFaqData(currentPage, searchTerm);
            updateUrlParameter('page', currentPage);
        });

        currentPage = getUrlParameter('page') || 1;
        loadFaqData(currentPage);
    });
</script>
