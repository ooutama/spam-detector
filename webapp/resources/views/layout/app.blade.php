<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @isset($title)
            {{ $title }} -
        @endisset
        {{ config('app.name') }}
    </title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script>
        var messages = {
            hamMessage: "{{ __('This message is ham.') }}",
            spamMessage: "{{ __('This message is spam.') }}",
            reportSentMessage: "{{ __('Your report has been sent. Thank you!') }}",
            reportMessage: "{{ __('If you think this is a mistake, please click') }}",
            reportLinkMessage: "{{ __('report') }}",
            checkButtonMessage: "{{ __('Check your SMS') }}",
            loadingButtonMessage: "{{ __('Loading ...') }}",
            textAreaMessage: "{{ __('Check if you should trust the received SMS.') }}",
            serverErrorMessage: "{{ __('A server error occured! Please try again later.') }}",
        };
    </script>
</head>

<body>
    <div id="app">
        <header class="header">
            <div class="container mx-auto">
                <div class="header-items">
                    <div class="header-logo">
                        <a href="/">
                            @php
                                $logo = explode(" ", config('app.name'), 2);    
                            @endphp
                            <div class="logo-text">
                            @if(count($logo) == 2)
                                {{ $logo[0] }}<span class="logo-text-colored">{{ $logo[1] }}</span>
                            @else
                                {{ $logo[0] }}
                            @endif
                            </div>
                        </a>
                    </div>
                    <div class="header-nav">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('home') }}"
                                    class="nav-item">{{ __('Home') }}</a>
                            </li>
                            <li>
                                <a href="#about-us"
                                    class="nav-item">{{ __('About us') }}</a>
                            </li>
                            {{-- <li>
                                <a href="#"
                                    class="nav-item">Contact
                                    us</a>
                            </li> --}}
                        </ul>
                        {{-- <ul class="nav">
                            <li>
                                <a href="#"
                                    class="nav-item">Sign
                                    in</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="btn-sm">Get
                                    started</a>
                            </li>
                        </ul> --}}
                        <button
                            class="nav-toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        @yield('content')
        <footer class="footer">
            <div class="container mx-auto">
                <div class="footer-items">
                    <div class="copyrights">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All Rights Reserved') }}</div>
                    <div>
                        <a href="https://github.com/outama-othmane/spam-detector"
                            class="source-code">{{ __('Source code') }}</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>