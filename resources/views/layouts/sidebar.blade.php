<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <h5>Quiz App</h5>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-dismiss="offcanvas" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg> Dashboard</a>
        </li>

        
        <li class="nav-title">System:</li>
        @if (\App\Helpers\Helper::userAccessOr('view users', 'view roles'))
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                </svg> Users
            </a>
            <ul class="nav-group-items compact">                
                @can('view users')
                <li class="nav-item"><a class="nav-link" href="{{ route('users') }}"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Users</a></li>
                @endcan
                @can('view roles')
                <li class="nav-item"><a class="nav-link" href="{{ route('roles') }}"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> roles</a></li>
                @endcan
                @can('view permissions')
                <li class="nav-item"><a class="nav-link" href="{{ route('permissions') }}"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Permission</a></li>
                @endcan
            </ul>
        </li>
        @endif


       


        @if (\App\Helpers\Helper::userAccessOr('View Leader'))
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-book') }}"></use>
                </svg> Leaders
            </a>
            <ul class="nav-group-items compact">                
                @can('View Leader')
                <li class="nav-item"><a class="nav-link" href="{{ route('leader') }}"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Leaders</a></li>
                @endcan
            </ul>
        </li>
        @endif

        @if (\App\Helpers\Helper::userAccessOr('view investment'))
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-book') }}"></use>
                </svg> Investment
            </a>
            <ul class="nav-group-items compact">                
                @can('view investment')
                <li class="nav-item"><a class="nav-link" href="{{ route('investment') }}"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Investment</a></li>
                @endcan
            </ul>
        </li>
        @endif



        <!-- <li class="nav-item">
            <a class="nav-link" href="colors.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('vendors/@coreui/icons/svg/free.svg#cil-drop') }}"></use>
                </svg> Colors
            </a>
        </li> -->

    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>