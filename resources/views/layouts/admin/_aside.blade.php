<aside class="app-sidebar">
    <div class="app-sidebar__user">
{{--        <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">--}}
        <div>
            <p class="app-sidebar__user-name">{{ Auth::user()->name }}</p>
            <p class="app-sidebar__user-designation">{{ implode(', ', Auth::user()->roles()->pluck('display_name')->toArray() ) }}</p>
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

{{--Settings--}}
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Settings</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{ route('admin.settings.index') }}"><i class="icon fa fa-circle-o"></i> General</a></li>
                <li><a class="treeview-item" href="{{ route('admin.social.login') }}"><i class="icon fa fa-circle-o"></i> Socials Login</a></li>
                <li><a class="treeview-item" href="{{ route('admin.social.links') }}"><i class="icon fa fa-circle-o"></i> Socials Links</a></li>
            </ul>
        </li>

{{--        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Tables</span><i class="treeview-indicator fa fa-angle-right"></i></a>--}}
{{--            <ul class="treeview-menu">--}}
{{--                <li><a class="treeview-item" href="table-basic.html"><i class="icon fa fa-circle-o"></i> Basic Tables</a></li>--}}
{{--                <li><a class="treeview-item" href="table-data-table.html"><i class="icon fa fa-circle-o"></i> Data Tables</a></li>--}}
{{--            </ul>--}}
{{--        </li>--}}



{{--    @if(Auth::user()->hasPermission('settings-read'))--}}
{{--        <li><a class="app-menu__item" href="{{ route('settings.index') }}"><i class="app-menu__icon fa fa-anchor"></i> <span class="app-menu__label">Roles</span></a></li>--}}
{{--    @else--}}
{{--        <li><a class="app-menu__item btn disabled" href="#"><i class="app-menu__icon fa fa-anchor"></i> <span class="app-menu__label">Roles</span></a></li>--}}
{{--    @endif--}}

    </ul>
</aside>
