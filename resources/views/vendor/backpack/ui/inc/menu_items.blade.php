{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-separator title="MAIN" />

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Vendors" icon="la la-user-tie" :link="backpack_url('vendor')" />
<!-- <x-backpack::menu-item title="Vendor comments" icon="la la-comment" :link="backpack_url('vendor-comment')" /> -->

<x-backpack::menu-separator title="ADMINISTRATION" />
<x-backpack::menu-item title="Vendor Types" icon="la la-briefcase" :link="backpack_url('vendor-type')" />
<x-backpack::menu-item title="Countries" icon="la la-flag" :link="backpack_url('country')" />
<x-backpack::menu-item title="Users" icon="la la-users" :link="backpack_url('user')" />
<x-backpack::menu-item title="Roles" icon="la la-user-shield" :link="backpack_url('role')" />
