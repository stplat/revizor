<div class="app__aside is-opened" id="aside-slide">
  <div class="aside">
    <div class="aside__logo"><img src="{{ asset('images/logo-text.png') }}" alt=""></div>
    <div class="aside__menu">
      <ul class="menu">
        <li class="menu__item"><a href="" class="menu__link {{!request()->is('/') ?: 'is-active'}}">Панель
            управления</a></li>
        <li class="menu__item"><a href="{{ route('checks.index') }}"
                                  class="menu__link menu__link--check {{!request()->is('checks') ?: 'is-active'}}">Проверки</a>
        </li>
        <li class="menu__item"><a href="{{ route('applicants.index') }}"
                                                   class="menu__link menu__link--applicant {{!request()->is('applicants') ?: 'is-active'}}">Заявители</a>
        </li>
        <li class="menu__item"><a href="{{ route('users.index') }}"
                                  class="menu__link menu__link--user {{!request()->is('users') ?: 'is-active'}}">Пользователи</a>
        </li>
        <li class="menu__item"><a href="{{ route('settings') }}"
                                  class="menu__link menu__link--settings {{!request()->is('settings') ?: 'is-active'}}">Настройки</a>
        </li>
      </ul>
    </div>
  </div>
</div>
