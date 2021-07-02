@extends('layout.app', ['title' => __('Home')])

@section('content')
    <main class="main-section" cstyle="min-height: calc(100vh - 70px);">
        <div class="container mx-auto">
            <div class="main-section-items" style="min-height: calc(100vh - 70px);">
                <div class="col1">
                    <h1 class="display-title">{{ __(':title Application', ['title' => config('app.name')]) }}</h1>
                    <p class="display-subtitle">
                        {{-- Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore. --}}
                        Check if the suspicious message is SPAM or not.
                    </p>
                </div>
                <div class="col2">
                    <spam-check-area store-message-url="{{ route('api.messages.store') }}" predict-message-url="{{ route('api.messages.predict', ['#id']) }}" report-url="{{ route('api.messages.report', ['#id']) }}"></spam-check-area>
                </div>
            </div>
        </div>
    </main>
    <section id="about-us" class="about-us-section">
        <div class="container mx-auto">
            <div class="mb-6">
                <h2 class="title">{{ __('About us') }}</h2>
            </div>
            <div class="relative">
                <p class="subtitle">
                    Students at the National School of Applied Sciences in Marrakech.
                    {{-- Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. --}}
                </p>
                <div class="cards">
                    <div class="card-1">
                        <a href="https://github.com/outama-othmane"
                            class="card">
                            <img src="https://avatars.githubusercontent.com/u/42810975" alt="" class="card-avatar">
                            <div class="card-name">
                                OUTAMA Othmane
                            </div>
                        </a>
                    </div>
                    <div class="card-2">
                        <a href="https://github.com/Aymane11"
                            class="card">
                            <img src="https://avatars.githubusercontent.com/u/24499930" alt="" class="card-avatar">
                            <div class="card-name">
                                Boumaaza Aymane
                            </div>
                        </a>
                    </div>
                    <div class="card-3">
                        <a href="https://github.com/ManalSjd"
                            class="card">
                            <img src="https://avatars.githubusercontent.com/u/81536351" alt="" class="card-avatar">
                            <div class="card-name">
                                SOUJOUD Manal
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection