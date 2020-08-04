<aside class="app-sidebar">
    <div class="app-sidebar__user">
{{--        <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">--}}
        <div>
            <p class="app-sidebar__user-name">{{ Auth::user()->name }}</p>
            <p class="app-sidebar__user-designation">{{ implode(', ', Auth::user()->roles()->pluck('name')->toArray() ) }}</p>
        </div>
    </div>
    <ul class="app-menu">

{{--Dashboard--}}
    <li><a class="app-menu__item" href="{{route('admin.home')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>

{{--    Admins--}}
    @if(Auth::user()->hasPermission('admins-read'))
        <li><a class="app-menu__item" href="{{ route('admins.index') }}"><i class="app-menu__icon fa fa-users"></i> <span class="app-menu__label">Admins</span></a></li>
        @else
        <li><a class="app-menu__item btn disabled" href="#"><i class="app-menu__icon fa fa-users"></i> <span class="app-menu__label">Admins</span></a></li>
    @endif

{{--Roles--}}
    @if(Auth::user()->hasPermission('roles-read'))
        <li><a class="app-menu__item" href="{{ route('roles.index') }}"><i class="app-menu__icon fa fa-anchor"></i> <span class="app-menu__label">Roles</span></a></li>
        @else
        <li><a class="app-menu__item btn disabled" href="#"><i class="app-menu__icon fa fa-anchor"></i> <span class="app-menu__label">Roles</span></a></li>
    @endif

    </ul>
</aside>
