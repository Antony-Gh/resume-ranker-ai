@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <h2>Welcome to Resume Ranker AI</h2>
    <a href="{{ route('upload.resume') }}" class="btn btn-primary">Rank Your Resume</a>
</div>
@endsection