@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">Link Not Found</h2>
@endsection

@section('content')
  <div class="bg-white p-6 rounded shadow text-center">
    <h3 class="text-xl font-semibold mb-2">This link is not available</h3>
    <p class="text-gray-600 mb-4">The link you tried to open does not exist, has been removed, or is currently disabled.</p>
    <div class="space-x-2">
      <a href="{{ url('/') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Go to Home</a>
      <a href="{{ route('wa-links.index') }}" class="px-4 py-2 border rounded">My Links</a>
    </div>
  </div>
@endsection
