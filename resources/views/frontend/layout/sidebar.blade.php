  <!--begin::Aside-->
  <div id="kt_aside" class="aside py-9" data-kt-drawer="true" data-kt-drawer-name="aside"
      data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
      data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
      data-kt-drawer-toggle="#kt_aside_toggle">
      <!--begin::Brand-->
      <div class="aside-logo flex-column-auto px-9 mb-9" id="kt_aside_logo">
          <!--begin::Logo-->
          <a href="{{ URL::to('Dashboard') }}">
              <img alt="Logo" src="{{ '../../public/frontend/media/sidebarLogo.svg' }}" />
          </a>
          <!--end::Logo-->
      </div>
      <!--end::Brand-->
      <!--begin::Aside menu-->
      <div class="aside-menu flex-column-fluid ps-5 pe-3 mb-9" id="kt_aside_menu">
          <!--begin::Aside Menu-->
          <div class="w-100 hover-scroll-overlay-y pe-2" id="kt_aside_menu_wrapper" data-kt-scroll="true"
              data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
              data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
              data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu, #kt_aside_menu_wrapper" data-kt-scroll-offset="100">
              <!--begin::Menu-->
              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('Dashboard') ? 'active' : '' }}"
                          href="{{ URL::to('Dashboard') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">Dashboard</span>
                      </a>
                  </div>

              </div>

              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('AllEvents') || request()->is('RequestedEvents') ? 'active' : '' }}"
                          href="{{ URL::to('AllEvents') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">All Events</span>
                      </a>
                  </div>

              </div>

              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('AllScholars') ? 'active' : '' }}"
                          href="{{ URL::to('AllScholars') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">All Scholars</span>
                      </a>
                  </div>

              </div>

              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('ScholarsRequests') ? 'active' : '' }}"
                          href="{{ URL::to('ScholarsRequests') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">Scholars Requests</span>
                      </a>
                  </div>

              </div>

              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('AllUsers') ? 'active' : '' }}"
                          href="{{ URL::to('AllUsers') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">All Users</span>
                      </a>
                  </div>

              </div>

              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('PublicQuestions') ? 'active' : '' }}"
                          href="{{ URL::to('PublicQuestions') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">All Public Questions</span>
                      </a>
                  </div>

              </div>

              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('PrivateQuestions') ? 'active' : '' }}"
                          href="{{ URL::to('PrivateQuestions') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">All Private Questions</span>
                      </a>
                  </div>

              </div>

              <div class="menu menu-column menu-rounded fw-bold my-auto" id="#kt_aside_menu" data-kt-menu="true">
                  <div class="menu-item">
                      <a class="menu-link  {{ request()->is('AllAppointments') ? 'active' : '' }}"
                          href="{{ URL::to('AllAppointments') }}">
                          <span class="menu-icon">
                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                              <span class="menu-bullet">
                                  <span class="bullet bullet-dot"></span>
                              </span>
                              <!--end::Svg Icon-->
                          </span>
                          <span class="menu-title">All Appointments</span>
                      </a>
                  </div>

              </div>

              <!--end::Menu-->
          </div>

          <!--end::Aside Menu-->
      </div>
      <div class="aside-logo flex-column-auto px-9 mb-9" id="kt_aside_logo">
          <!--begin::Logo-->
          <div class="d-flex align-items-center">
              <!--begin::Avatar-->
              <div class="symbol symbol-circle symbol-70px">
                  <img src="{{ '../../public/frontend/media/avatars/150-26.jpg' }}" alt="photo" />
              </div>
              <!--end::Avatar-->
              <!--begin::User info-->
              <div class="ms-2">
                  <!--begin::Name-->
                  <a href="#"
                      class="text-gray-800 text-hover-primary fs-6 fw-bolder lh-1">{{ session('name') }}</a>
                  <!--end::Name-->
                  <!--begin::Major-->
                  <span class="text-muted fw-bolder d-block fs-4  pt-1lh-1">Admin</span>
                  <!--end::Major-->
              </div>
              <div class="btn btn-lg btn-icon btn-active-color-white position-relative me-n2 ms-4"
                  data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="top-end">
                  <!--begin::Svg Icon | path: icons/duotune/coding/cod001.svg-->
                  <a href="{{ URL::to('logout') }}">
                      <span class="">
                          <svg width="50" height="50" viewBox="0 0 30 30" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M6.5 12.5676V17.4324C6.5 19.7258 6.5 20.8724 7.22162 21.5849C7.87718 22.2321 8.89572 22.2913 10.8183 22.2968C10.813 22.262 10.808 22.2271 10.8032 22.192C10.6884 21.3484 10.6884 20.2759 10.6884 18.9453L10.6884 18.8919C10.6884 18.4889 11.0193 18.1622 11.4275 18.1622C11.8357 18.1622 12.1667 18.4889 12.1667 18.8919C12.1667 20.2885 12.1682 21.2626 12.2683 21.9975C12.3655 22.7114 12.5434 23.0895 12.8161 23.3588C13.0889 23.6281 13.4718 23.8037 14.195 23.8996C14.9394 23.9985 15.926 24 17.3406 24H18.3261C19.7407 24 20.7273 23.9985 21.4717 23.8996C22.1948 23.8037 22.5778 23.6281 22.8505 23.3588C23.1233 23.0895 23.3011 22.7114 23.3983 21.9975C23.4984 21.2626 23.5 20.2885 23.5 18.8919V11.1081C23.5 9.71149 23.4984 8.73743 23.3983 8.0025C23.3011 7.28855 23.1233 6.91048 22.8505 6.6412C22.5778 6.37192 22.1948 6.19635 21.4717 6.10036C20.7273 6.00155 19.7407 6 18.3261 6H17.3406C15.926 6 14.9394 6.00155 14.195 6.10036C13.4718 6.19635 13.0889 6.37192 12.8161 6.6412C12.5434 6.91048 12.3655 7.28855 12.2683 8.0025C12.1682 8.73743 12.1667 9.71149 12.1667 11.1081C12.1667 11.5111 11.8357 11.8378 11.4275 11.8378C11.0193 11.8378 10.6884 11.5111 10.6884 11.1081L10.6884 11.0547C10.6884 9.72409 10.6884 8.65156 10.8032 7.80803C10.808 7.77288 10.813 7.73795 10.8183 7.70325C8.89572 7.70867 7.87718 7.76792 7.22162 8.41515C6.5 9.12759 6.5 10.2742 6.5 12.5676ZM16.385 17.9484L18.8487 15.516C19.1374 15.231 19.1374 14.769 18.8487 14.484L16.385 12.0516C16.0963 11.7666 15.6283 11.7666 15.3397 12.0516C15.051 12.3365 15.051 12.7986 15.3397 13.0836L16.5417 14.2703H9.45652C9.04831 14.2703 8.71739 14.597 8.71739 15C8.71739 15.403 9.04831 15.7297 9.45652 15.7297H16.5417L15.3397 16.9164C15.051 17.2014 15.051 17.6635 15.3397 17.9484C15.6283 18.2334 16.0963 18.2334 16.385 17.9484Z"
                                  fill="#38B89A" />
                          </svg>
                      </span>
                  </a>
                  <!--end::Svg Icon-->
              </div>
              <!--end::User info-->
          </div>



          <!--end::Logo-->
      </div>




      <!--end::Aside menu-->
  </div>

  <!--end::Aside-->
