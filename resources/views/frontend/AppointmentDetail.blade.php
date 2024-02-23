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

    .box {
        border-radius: 10px;
        background: rgba(255, 255, 255, 1);
        padding: 8px 12px;
        align-items: flex-start;
        gap: 10px;
        flex-wrap: wrap;

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
                <h1 class="d-flex flex-column text-dark fw-bolder my-0 fs-1">Appointment Detail
                </h1>

                <!--end::Heading-->
            </div>
            <!--end::Page title=-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-fluid" id="">
            <div class="row pb-4">
                <div class="col-6">
                    <div class=" title fs-2 fw-bold pb-9">
                        Username
                    </div>
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <!--begin: Pic-->
                        <div class="me-3 mb-4">
                            @if ($detail->user_detail->image == '')
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                        style="height: 80px; width:80px;" />
                                </div>
                            @else
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ asset('public/storage/' . $detail->user_detail->image) }}" alt="image"
                                        style="height: 80px; width:80px; object-fit: cover;" />
                                </div>
                            @endif
                        </div>
                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1 text-success fs-7 fw-normal">
                                        {{ $detail->user_detail->user_type }}
                                    </div>
                                    <div class="d-flex align-items-center mb-1 text-gray-900 fs-2 fw-bolder">
                                        {{ $detail->user_detail->name }}
                                    </div>
                                    <div class="d-flex align-items-center mb-1 text-muted fs-5 fw-normal">
                                        {{ $detail->user_detail->email }}
                                    </div>
                                </div>
                                <!--end::User-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <div class="col-6">
                    <div class=" title fs-2 fw-bold pb-9">
                        Scholars
                    </div>
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <!--begin: Pic-->
                        <div class="me-3 mb-4">
                            @if ($detail->mufti_detail->image == '')
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ url('public/frontend/media/blank.svg') }}" alt="image"
                                        style="height: 80px; width:80px;" />
                                </div>
                            @else
                                <div class="symbol   symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="{{ asset('public/storage/' . $detail->mufti_detail->image) }}" alt="image"
                                        style="height: 80px; width:80px; object-fit: cover;" />
                                </div>
                            @endif
                        </div>
                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1 text-success fs-7 fw-normal">
                                        {{ $detail->mufti_detail->fiqa }}
                                    </div>
                                    <div class="d-flex align-items-center mb-1 text-gray-900 fs-2 fw-bolder">
                                        {{ $detail->mufti_detail->name }}
                                    </div>
                                    <div class="text-muted fs-6 pt-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px;">
                                        @foreach ($detail->mufti_detail['interests'] as $data)
                                        {{ $data['interest'] }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                                <!--end::User-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <!-- Left side content -->
                    <div class="col-lg-6">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-7 fw-bolder fs-2 text-gray-800">Category</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-5">
                                <span class="fw-bold fs-3 badge badge-light fw-normal fs-4  mb-3"> {{$detail->category}} </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-7 fw-bolder fs-2 text-gray-800">Charges</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-5">
                                <span class="fw-bold fs-3 text-muted">
                                   {{$detail->consultation_fee}}$
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-7 fw-bolder fs-2 text-gray-800">Date and time</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-5">
                                <span class="fw-bold fs-3 text-muted">
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('M d, Y, h:iA') }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-7 fw-bolder fs-2 text-gray-800">Duration</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-5">
                                <span class="fw-bold fs-3 text-muted">
                                    {{$detail->duration}} Mins
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-7 fw-bolder fs-2 text-gray-800">Payment With:</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-5">
                                <span class="fw-bold fs-3 text-success">
                                    {{$detail->payment_method}}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>

                <div class="row mt-5 pb-10">
                    <div class="col-lg-12">
                        <div class="fw-bolder fs-1 text-gray-800">Description</div>
                        <div class="fw-normal text-dark fs-2 text-dark mt-5">
                           {{$detail->description}}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
