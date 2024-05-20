<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile border-bottom">
            <div class="nav-link flex-column">
                <div class="nav-profile-image">
                    <img src="{{app('App\Http\Controllers\SidebarController')->getImageURL()}}" alt="profile" />
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex ml-0 mb-3 flex-column">
                    <span class="font-weight-semibold mb-1 mt-2 text-center">{{ app('App\Http\Controllers\SidebarController')->getUser()->name }}</span>
                    <span class="text-secondary icon-sm text-center">{{app('App\Http\Controllers\SidebarController')->getUser()->role}}</span>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{route('profile.edit')}}">
                <i class="mdi mdi-contacts menu-icon"></i>
                <span class="menu-title">Profile</span>
            </a>
        </li>

        @php
            $tree = (new \App\Http\Controllers\UsersController())->tree();
            $hasChildren = $tree['children']->isEmpty();
        @endphp

        @if(!$hasChildren)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="mdi mdi-account-multiple menu-icon"></i>
                    <span class="menu-title">Users</span>
                </a>
            </li>
        @endif


{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">--}}
{{--                <i class="mdi mdi-crosshairs-gps menu-icon"></i>--}}
{{--                <span class="menu-title">Transaction</span>--}}
{{--                <i class="menu-arrow"></i>--}}
{{--            </a>--}}
{{--            <div class="collapse" id="ui-basic">--}}
{{--                <ul class="nav flex-column sub-menu">--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="{{route('purchases.index')}}">Purchase</a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="pages/ui-features/dropdowns.html">Sale Order</a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="pages/ui-features/typography.html">Inventory Counting</a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </li>--}}



        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a class="nav-link" href="{{route('logout')}}"
                   onclick="event.preventDefault();
                                               this.closest('form').submit();">
                    <i class="mdi mdi-logout-variant menu-icon"></i>
                    <span class="menu-title"> Logout</span>
                </a>

            </form>
        </li>

    </ul>
</nav>
