@include('layouts._head')
<body>
<div id="app" @auth v-cloak @endauth>
  @yield('content')
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
@include('layouts._footer')
