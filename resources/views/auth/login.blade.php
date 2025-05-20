@extends('layouts.app-layout')

@section('title', 'تسجيل الدخول')
@section('header', 'تسجيل الدخول')

@section('content')
    <div class="flex items-center justify-center min-h-screen">
        <form action="{{ route('login') }}" method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-md">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    تسجيل الدخول
                </button>
            </div>
        </form>
    </div>
@endsection

@section('search-action', '')
