@include('layouts._head')
<body>
<div id="app" @auth v-cloak @endauth>
  @include('layouts._aside')
  <div class="app__content" id="content-slide">
    @include('layouts._header')
    <main class="app__main">
      @yield('content')
    </main>
    <div class="preloader">
      <div class="sk-circle">
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
        <div class="sk-circle-dot"></div>
      </div>
    </div>
  </div>
</div>

@include('layouts._footer')
