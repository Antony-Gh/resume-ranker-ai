@extends('layouts.app')

@section('content')
<header class="bg-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <div class="flex items-center">
            <img alt="ResuRank logo" class="h-10 w-10 header_icon blue-background" height="40" src="{{ asset('images/icons/header_icon.png') }}" width="40" />
            <span class="text-2xl font-bold blue-text ml-2">
                ResuRank
            </span>
        </div>
        <nav class="flex space-x-6">
            <a class="text-gray-700 hover:text-blue-600 font-bold" href="#">
                About
            </a>
            <a class="text-gray-700 hover:text-blue-600 font-bold" href="#">
                Features
            </a>
            <a class="text-gray-700 hover:text-blue-600 font-bold" href="#">
                Pricing
            </a>
            <a class="text-gray-700 hover:text-blue-600 font-bold" href="#">
                How It Works
            </a>
            <a class="text-gray-700 hover:text-blue-600 font-bold" href="#">
                Contact
            </a>
        </nav>
        <div class="flex space-x-4">
            <a class="blue-background text-white px-4 py-2 border-12-px hover:bg-blue-700" href="#">
                Sign In
            </a>
            <a class="blue-text border border-blue-600 px-4 py-2 border-12-px hover:bg-blue-600 hover:text-white" href="#">
                Get Started for Free
            </a>
        </div>
    </div>
</header>
<main class="bg-gray-50 py-16">
    <div class="container mx-auto flex flex-col lg:flex-row items-center px-6">
        <div class="lg:w-1/2">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                AI-Powered
                <span class="text-blue-600">
                    Resume Ranking
                </span>
                Tool!
            </h1>
            <p class="text-gray-600 mb-6">
                ResumeRanker AI is an advanced tool that analyzes resumes against job descriptions using AI. It delivers ranked insights, streamlines candidate evaluation, and generates detailed reports for efficient hiring decisions.
            </p>
            <div class="flex space-x-4">
                <a class="bg-[#E0E7FF] text-[#4F46E5] px-4 py-2 rounded hover:bg-blue-700 flex items-center" href="#">
                    <i class="fas fa-file-alt mr-2">
                    </i>
                    Rank Your Resume
                </a>
                <a class="text-[#4F46E5] border border-[#4F46E5] px-4 py-2 rounded hover:bg-blue-600 hover:text-white flex items-center" href="#">
                    <i class="fas fa-credit-card mr-2">
                    </i>
                    Take a Subscription
                </a>
            </div>
        </div>
        <div class="lg:w-1/2 mt-10 lg:mt-0">
            <img alt="Illustration of resume ranking tool on a tablet" class="w-full" height="400" src="https://storage.googleapis.com/a1aa/image/InoPBv_0A79FUtvGjQn2vaxQSOcNGqJ_3LBP0pxbWB0.jpg" width="500" />
        </div>
    </div>
</main>
<footer class="bg-white py-4">
    <div class="container mx-auto text-center text-gray-600">
        www.resumeranker.com or email support@resumeranker.com
    </div>
</footer>
@endsection
