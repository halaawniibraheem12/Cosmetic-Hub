@extends('layouts.app')

@section('title','Profile')

@section('content')
<div class="space-y-8">

    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Profile</h1>
            <p class="text-gray-500 mt-1">Manage your personal information and security settings</p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 rounded-lg bg-purple-100 text-purple-700 hover:bg-purple-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- Profile Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: User Card -->
        <div class="bg-white rounded-2xl shadow border p-6 h-fit">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900 truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <div class="mt-6 space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 font-medium">Active</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Role</span>
                    <span class="px-2 py-1 rounded-full bg-purple-100 text-purple-700 font-medium">Administrator</span>
                </div>
            </div>

            <div class="mt-6 bg-purple-50 border border-purple-100 rounded-xl p-4">
                <p class="font-semibold text-gray-900 mb-1">
                    Tip
                </p>
                <p class="text-sm text-gray-600">
                    Keep your profile updated and use a strong password for better security.
                </p>
            </div>
        </div>

        <!-- Right: Forms -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Update Profile Information -->
            <div class="bg-white rounded-2xl shadow border overflow-hidden">
                <div class="p-5 border-b bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user-pen text-purple-600 mr-2"></i>
                        Profile Information
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Update your name and email address</p>
                </div>

                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-2xl shadow border overflow-hidden">
                <div class="p-5 border-b bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-key text-purple-600 mr-2"></i>
                        Update Password
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Change your password to keep your account secure</p>
                </div>

                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-2xl shadow border overflow-hidden">
                <div class="p-5 border-b bg-gray-50">
                    <h2 class="text-lg font-bold text-red-600 flex items-center">
                        <i class="fas fa-triangle-exclamation mr-2"></i>
                        Delete Account
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Permanently remove your account</p>
                </div>

                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

</div>
@endsection