<div class="header">
  <div class="header__top">
    <div class="header__btn" id="aside-slide-btn">
      <button class="hamburger" type="button">
            <span class="hamburger-box">
              <span class="hamburger-inner"></span>
            </span>
      </button>
    </div>
    <div class="header__login">
      <img src="{{ asset('images/admin_avatar.png') }}" alt="" id="login">
      <div class="login-dropdown" id="login-dropdown">
        <ul>
          <li class="login-dropdown__item">
            <a href="{{ route('logout') }}" class="login-dropdown__link"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">Выход</a>
          </li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </div>
  </div>
  <div class="header__bottom">
    <div class="header__title">
      <div class="header-title">
        <div class="header-title__item">
          <h3>{!! $title ?? '' !!}</h3>
        </div>
        @if (request()->routeIs('checks.show'))
          <div class="header-title__item">
            <tip title="Информация о проверке">
              <div class="tip-dropdown__title">Основное</div>
              <ul class="tip-dropdown__list">
                <li data-key="Вид проверки:">{{ $data['check']->check_type }}</li>
                <li data-key="Период:">{{ $data['check']->period }}</li>
                <li data-key="Создана:">{{ $data['check']->start_datetime }}</li>
                <li data-key="Завершена:">{{ $data['check']->end_datetime }}</li>
              </ul>
            </tip>
          </div>
          <div class="header-title__item">

            <tip-check-settings :check="{{ $data['check'] }}"></tip-check-settings>
          </div>
        @endif
      </div>
    </div>
    <div class="header__breadcrumbs">
      <ul class="breadcrumbs">
        <li class="breadcrumbs-item"><a href="/">Главная</a></li>
        @if ($subtitle)
          <li class="breadcrumbs-item"><a href="{!! $subtitle_link ?? '' !!}">{!! $subtitle ?? '' !!}</a></li>
          <li class="breadcrumbs-item">{!! $title ?? '' !!}</li>
        @else
          <li class="breadcrumbs-item">{!! $title ?? '' !!}</li>
        @endif
      </ul>
    </div>
  </div>
</div>
