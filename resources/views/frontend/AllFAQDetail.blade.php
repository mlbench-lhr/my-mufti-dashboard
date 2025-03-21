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
               <!-- Left Section: FAQ Details with Back Arrow -->
               <div class="d-flex align-items-center">
                 <span>
                  <a href="{{ route('AllFAQs') }}" class="me-2 text-success">
                  <img src="{{ asset('../../public/frontend/media/Arrow Left.svg') }}" alt="Back" class="custom-icon"><!--add public-->
                  </a>
                  </span>
                  <span class="fw-bold text-dark" style="font-size: 34px;">FAQ's Detail</span>
                </div>
                     <!-- Right Section: Edit & Delete Buttons -->
               <div class="d-flex align-items-center gap-2">
                <button style="background-color: #38B89A; color:#FFFFFF; padding: 6px 20px; border-radius: 8px; font-size: 20px; display: flex; align-items: center;" type="button" class="btn"
                data-bs-toggle="modal" data-bs-target="#kt_editFaqModal"
                onclick="populateEditForm({{ $faq->id }})">
                <img src="{{ asset('../../public/frontend/media/Pen 2.svg') }}" alt="Edit" style="width: 26px; height: 26px;">
                Edit
                 </button>
                <a href="{{ URL::to('DeleteFAQ/' . $faq->id) }}"
                    id="delete-btn">
                <button type="button" class="btn" style="background-color:#EA4335; color:white; padding: 6px 13px; border-radius: 8px; font-size: 20px; display: flex; align-items: center;"
                  onclick="confirmDelete(event)">
                  <img src="{{ asset('../../public/frontend/media/Trash Bin 2.svg') }}" alt="Delete"style="width: 26px; height: 26px;">
                  Delete
                </button>
                </a>
            </div>
        </div>
    </div> 
    <!--end::Container-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card body-->
                <!-- FAQ Question -->
                <div class="card p-4 mb-3" style="background-color: #F0F1F3; border-radius: 0;">
                    <h6 class="fw-bold" style="color: #38B89A; font-size: 32px;">Question</h6>
                    <p class="fw-bold" style="font-size: 28px;">
                        {!! nl2br(e($faq['question'])) !!}
                    </p>
                    <small class="text-muted"style="color: #78827FCC; font-size: 20px;">Published On: {{ $faq->created_at->format('M d, Y') }}</small>
                </div>

                <!-- FAQ Answer -->
                <div class="card p-4" style="background-color: #F0F1F3; border-radius: 0;">
                    <h6 class="fw-bold" style="color: #38B89A; font-size: 32px;">Answer</h6>
                    <p class="text-muted" style="color: #78827F; font-size: 22px;">
                        {!! nl2br(e($faq['answer'])) !!}
                    </p>
                </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>
<!--end::Card body-->
    <!-- Edit FAQ Modal -->
    <div class="modal fade" id="kt_editFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mw-500px">
            <div class="modal-content">
                <div class="modal-header pb-0 border-0 d-flex justify-content-between items-center">
                    <p></p>
                    <p class="fs-1 fw-bold">Edit FAQ</p>
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
                    <form id="editFaqForm" action="/editfaq" method="post" class="form">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" id="faq_id">
                        <div class="mb-5">
                            <label for="faq_question" class="fw-bold fs-3 pb-2 mb-1">Question</label>
                            <textarea id="faq_question" class="form-control" style="color:#78827F" name="question" placeholder=""
                                rows="1" required oninput="this.value = this.value.replace(/^\s+/g, '')"></textarea>
                        </div>
                        <div class="mb-5">
                            <label for="faq_answer" class="fw-bold fs-3 pb-2 mb-1">Answer</label>
                            <textarea id="faq_answer" class="form-control" style="color:#78827F" name="answer" placeholder=""
                                rows="4" required oninput="this.value = this.value.replace(/^\s+/g, '')"></textarea>
                        </div>
                        <div class="d-flex justify-content-center pt-2">
                            <button type="submit" class="btn btn-lg col-12"
                                style="background-color: #38B89A; color: #FFFFFF">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->
    <script>
    //let faqCategories = [];
    function populateEditForm(faqId) {
    console.log("Fetching FAQ ID:", faqId);
    
    fetch(`/getfaq/${faqId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch FAQ data.');
            }
            return response.json();
        })
        .then(data => {
            // Ensure elements exist before assigning values
            document.getElementById('faq_id').value = data.id;
            document.getElementById('faq_question').value = data.question || "";
            document.getElementById('faq_answer').value = data.answer || "";
            
            //document.getElementById('editFaqForm').action = `/editfaq/${faqId}`;
        })
        .catch(error => {
            console.error("Error populating form:", error);
            alert("Failed to load FAQ data. Please try again.");
        });
}

    document.getElementById('editFaqForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const faqId = document.getElementById('faq_id').value;

        fetch(this.action, {
            method: this.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            Swal.fire({
                    title: "Success!",
                    text: "Your FAQ has been Updated successfully.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = `/AllFAQDetail/${faqId}`;
                });
        })
        .catch(error => {
            Swal.fire({
                title: "Error!",
                text: "Failed to update FAQ.",
                icon: "error",
                showConfirmButton: false,
                timer: 1500,
            });
        });
    });
</script>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        var faq_id = '{{ $faq->id }}';
        var currentPage = 1;

        if (!faq_id) {
            console.error("FAQ ID is undefined. Cannot load data.");
            return;
        }

        function loadVerificationData(page, search = '', sortingOption = '') {
            $('#loader').removeClass('d-none');
            $.ajax({
                url: `/AllFAQDetail/${faq_id}?page=${page}&search=${encodeURIComponent(search)}&sorting=${sortingOption}`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var faqs = response.faqs;
                    var tableBody = $('#faq-table-body');
                    tableBody.empty();

                    if (!faqs.data || faqs.data.length === 0) {
                        tableBody.append(`
                            <tr>
                                <td colspan="6" class="text-center pt-10 fw-bolder fs-2">
                                    No Questions found
                                </td>
                            </tr>`);
                    } else {
                        $.each(faqs.data, function(index, row) {
                            var newRow = `
                                <tr class="text-start">        
                                    <td>${truncateText(row.question)}</td>
                                    <td>${row.created_at}</td>
                                    <td>${row.answer}</td>
                                </tr>`;
                            tableBody.append(newRow);
                        });
                    }
                    $('#loader').addClass('d-none');
                },
                error: function(error) {
                    console.error("Error loading data:", error);
                }
            });
        }

        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            currentPage = $(this).data('page');
            loadVerificationData(currentPage, $('#global-search').val());
        });

        $(document).on('click', '#apply-filter-button', function(e) {
            e.preventDefault();
            loadVerificationData(currentPage, $('#global-search').val(), $('#request-date-filter').val());
        });

        $(document).on('click', '#reset-filter-button', function(e) {
            e.preventDefault();
            $('#request-date-filter').val('').trigger('change');
            loadVerificationData(currentPage);
        });

        $('#global-search').on('input', function() {
            loadVerificationData(1, $(this).val());
        });

        loadVerificationData(currentPage);
    });
    function truncateText(text, length = 50) {
        return text.length > length ? text.substring(0, length) + '...' : text;
    }
        function confirmDelete(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Delete FAQ',
            text: 'Are you sure you want to delete this FAQ?',
            width: '450px',
            icon: 'warning',
            iconColor:'#38B89A',
            showCancelButton: true,
            confirmButtonColor: '#38B89A',
            cancelButtonColor: '#F0F1F3',
            confirmButtonText: 'Delete',
            cancelButtonText: '<span style="color: #7B849A;">Cancel</span>',
            willOpen: () => {
                const cancelButton = Swal.getCancelButton();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'The FAQ has been deleted.',
                    'success'
                ).then(() => {
                    window.location.href = event.target.closest('a').href;
                });
            }
        });
    }

        function validateInput(input) {
            input.value = input.value.replace(/^\s+/, '').replace(/[^0-9a-zA-Z\s]/g, '');
        }
    </script>
