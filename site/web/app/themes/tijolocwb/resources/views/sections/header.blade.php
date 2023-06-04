<header class="banner w-full z-50 bg-tijolo">
  <nav class="nav-primary container py-4">
    <div class="flex flex-wrap lg:flex-nowrap justify-between items-center mx-auto">
      <a class="brand" href="{{ home_url('/') }}">
        <h1 id="logo" class="text-white logohome relative bottom-4 hover:scale-105 transition duration-300 ease-in-out">
          <span class="text-8xl">Tijolo</span>
          <span class="text-lg absolute top-[85%] right-0">comidas e vinhos</span>
        </h1>
      </a>
        <div id="mobile-menu" class="flex lg:order-2 ml-8">
          {{-- <button type="button" data-collapse-toggle="mobile-menu-3" aria-controls="mobile-menu-3" aria-expanded="false"
            class="lg:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd"></path>
            </svg>
          </button> --}}
          {{-- <div class="hidden relative lg:block">
            @include('partials/inputsearch')
          </div> --}}
          <button aria-label="Botão menu sanduíche" data-collapse-toggle="mobile-menu-3" type="button"
            class="inline-flex items-center p-2 text-sm text-white rounded-lg lg:hidden"
            aria-controls="mobile-menu-3" aria-expanded="false">
            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"></path>
            </svg>
            <svg class="hidden w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>
        <div class="hidden justify-between items-center w-full lg:flex lg:order-1" id="mobile-menu-3">
          {{-- <div class="relative mt-3 lg:hidden">
            @include('partials/inputsearch')
          </div> --}}
          {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'flex flex-col pl-4 py-2 tracking-widest w-full justify-evenly
          lg:flex-row lg:mt-0 nav text-white text-xl md:text-3xl relative', 'echo' => false]) !!}
        </div>
      </div>
  </nav>
</header>