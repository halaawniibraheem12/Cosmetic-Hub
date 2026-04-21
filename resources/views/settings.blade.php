@extends('layouts.app')

@section('title','Settings')

@section('content')
<div class="space-y-8">

    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-500 mt-1">Manage your account settings</p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 rounded-lg bg-purple-100 text-purple-700 hover:bg-purple-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- User Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Name -->
        <div class="bg-white rounded-xl shadow p-5 border flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Name</p>
                <p class="font-semibold text-gray-900">
                    {{ auth()->user()->name }}
                </p>
            </div>
        </div>

        <!-- Email -->
        <div class="bg-white rounded-xl shadow p-5 border flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-semibold text-gray-900">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl shadow p-5 border flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Account Status</p>
                <p class="font-semibold text-green-600">
                    Active
                </p>
            </div>
        </div>

    </div>

    <!-- Main Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Security -->
        <div class="bg-white rounded-2xl shadow border">

            <div class="p-5 border-b bg-gray-50 rounded-t-2xl">
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-lock text-purple-600 mr-2"></i>
                    Security
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Password and account security
                </p>
            </div>

            <div class="p-6 space-y-6">

                <!-- Change Password -->
                <div class="flex items-center justify-between bg-purple-50 border border-purple-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-900">Change Password</p>
                        <p class="text-sm text-gray-600">
                            Update your account password
                        </p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                        Edit
                    </a>
                </div>

                <!-- Logout -->
                <div class="flex items-center justify-between bg-red-50 border border-red-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-900">Logout</p>
                        <p class="text-sm text-gray-600">
                            End your current session
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                            Logout
                        </button>
                    </form>
                </div>

            </div>

        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-2xl shadow border">

            <div class="p-5 border-b bg-gray-50 rounded-t-2xl">
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-id-card text-purple-600 mr-2"></i>
                    Account Information
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Basic user details
                </p>
            </div>

            <div class="p-6 space-y-4">

                <div class="flex justify-between">
                    <span class="text-gray-500">Name</span>
                    <span class="font-medium text-gray-900">
                        {{ auth()->user()->name }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-900">
                        {{ auth()->user()->email }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Role</span>
                    <span class="font-medium text-gray-900">
                        Administrator
                    </span>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection