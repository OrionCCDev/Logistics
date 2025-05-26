{{--  <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @role('orionadmin')
                        <div class="mb-4">
                            <h3 class="text-lg font-bold">Admin Section</h3>
                            <p>This content is only visible to administrators.</p>
                        </div>
                    @endrole

                    @permission('manage-users')
                        <div class="mb-4">
                            <h3 class="text-lg font-bold">User Management</h3>
                            <p>This content is only visible to users with manage-users permission.</p>
                        </div>
                    @endpermission

                    @hasanyrole(['manager', 'orionadmin'])
                        <div class="mb-4">
                            <h3 class="text-lg font-bold">Management Section</h3>
                            <p>This content is visible to both managers and administrators.</p>
                        </div>
                    @endhasanyrole

                    <div class="mb-4">
                        <h3 class="text-lg font-bold">Your Roles</h3>
                        <ul>
                            @foreach(auth()->user()->roles as $role)
                                <li>{{ $role->display_name }} ({{ $role->name }})</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-bold">Your Permissions</h3>
                        <ul>
                            @foreach(auth()->user()->permissions as $permission)
                                <li>{{ $permission->display_name }} ({{ $permission->name }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>  --}}

@extends('layouts.app')

@section('content')
    <h1>Dashboard</h1>
@endsection
